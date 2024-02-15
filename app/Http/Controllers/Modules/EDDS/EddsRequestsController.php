<?php

namespace App\Http\Controllers\Modules\EDDS;

use App;
use App\Classess\Helpers\BaseCrudController;
use App\EddsRequests;
use App\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPExcel_Shared_Date;
use PHPExcel_IOFactory;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EddsRequestsController extends BaseCrudController
{
    public function __construct(EddsRequests $model, Request $requests)
    {
        $this->model = $model;
        $this->template_prefix = 'edds';
        $this->fields_like = ['name', 'email', 'address', 'phone'];
        $this->store_validate = [
            'request_num' => 'required',
            'house_cnt' => 'integer|nullable',
            'szo_house_cnt' => 'integer|nullable'
        ];
        $this->update_validate = [
            'request_num' => 'required',
            'type' => 'required',
            'house_cnt' => 'integer|nullable',
            'szo_house_cnt' => 'integer|nullable'
        ];
        $this->pagination_limit = 20;
        $this->table_options = $this->model->table_opt;
        //Вывод кнопок в зависимости от уровня доступа
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            //Если это простой работник, исполнитель, то выыводим только его задачи
            if ($this->user->role == 'admin') {
                $this->table_options[] = [
                    'cell' => 'action',
                    'options' => [
                        'buttons' => [
                        ],
                        'show_edit_button' => true,
                        'show_delete_button' => true
                    ]
                ];
            } else {
                $this->table_options[] = [
                    'cell' => 'action',
                    'options' => [
                        'buttons' => [
//                                ['icon-class'=>'fa fa-plus','button-text'=>'задача','url'=>'#','confirm'=>'Сформировать новое обращение?'],
                        ],
                        'show_edit_button' => true,
                        'show_delete_button' => false
                    ]
                ];
            }
            return $next($request);
        });

        $this->detail_variable = true;
    }

    public function detailPage($id)
    {
        return ['table' => false];
    }

    public function inputStore(Request $request)
    {
        return $this->inputUpdate($request);
    }

    public function inputUpdate(Request $request)
    {
        $post['city'] = isset($request->city)?$request->city:'';
        $post['street'] = isset($request->street)?$request->street:'';
        $post['house'] = isset($request->house)?$request->house:'';
        $reqz['address_json'] = EddsRequestsController::getAddrFromPost($post);

        if (isset($request->request_date)) {
            $request_date = date("Y-m-d H:i:s", strtotime($request->request_date));
        } else {
            $request_date = NULL;
        }
        if (isset($request->date_start_problem)) {
            $date_start_problem = date("Y-m-d H:i:s", strtotime($request->date_start_problem));
        } else {
            $date_start_problem = NULL;
        }
        if (isset($request->date_end_problem)) {
            $date_end_problem = date("Y-m-d H:i:s", strtotime($request->date_end_problem));
        } else {
            $date_end_problem = NULL;
        }
        $req = [
            'address_json' => $reqz['address_json'],
            'type' => $request->type,
            'service_id' => $request->service_id,
            'request_date' => $request_date,
            'date_start_problem' => $date_start_problem,
            'date_end_problem' => $date_end_problem,
            'house_cnt' => ($request->house_cnt!='')?$request->house_cnt:0,
            'szo_house_cnt' => ($request->szo_house_cnt!='')?$request->szo_house_cnt:0,
            'description' => $request->description,
            'request_num' => $request->request_num,
            'user_id' => $request->user()->id
        ];
        if ($request->client_name) {
            $req += ['client_name' => $request->client_name];
        }
        if ($request->client_phone) {
            $req += ['client_phone' => $request->client_phone];
        }
        return $req;

    }

    //Преобразуем адрес из динамической формы добавления адресов в строку
    public function getAddrFromPost($post_data)
    {
        $str = (json_encode($post_data));
        $arr_j = json_decode($str);
        $arr = [];
        //Обработка динамических адресов. В базе будут храниться как json строка
        //Пример "[["78243554000","3","123,323,32"],["78243805001","25","1,3,4"],["78243805004","","1"],["78417000000","",""]]"
        //Это массив адресов, первое поле в элементе окато населенного пункта, второе улица, 3е номер/номера домов
        for ($i = 0; $i < count($arr_j->city); $i++) {
            if ($arr_j->city[$i] != '')
                $arr[] = [isset($arr_j->city[$i])?$arr_j->city[$i]:'',
                          isset($arr_j->street[$i])?$arr_j->street[$i]:'',
                          isset($arr_j->house[$i])?$arr_j->house[$i]:''
                ];
        }
        $result2db = json_encode($arr);

        return $result2db;

    }

    //Обновить id'шники после импорта
    public function update_usersIDS()
    {
        $users = \App\User::all()->pluck("bitrix_id", "id")->toArray();
        $users = array_flip($users);
        $requests = $this->model->all();
        foreach ($requests as $r) {
            echo $r->user_id;
            if (array_key_exists($r->user_id, $users))
                $this->model->where('id', '=', $r->id)->update(['user_id' => $users[$r->user_id]]);
        }
        dd($users);
    }

    public function genReportFast(Request $request)
    {

        $filename = "report_" . date('Ymdhms') . ".xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
        <head>
            <!--[if gte mso 9]>
            <xml>
                <x:ExcelWorkbook>
                    <x:ExcelWorksheets>
                        <x:ExcelWorksheet>
                            <x:Name>Sheet 1</x:Name>
                            <x:WorksheetOptions>
                                <x:Print>
                                    <x:ValidPrinterInfo/>
                                </x:Print>
                            </x:WorksheetOptions>
                        </x:ExcelWorksheet>
                    </x:ExcelWorksheets>
                </x:ExcelWorkbook>
            </xml>
            <![endif]-->
        </head><style>.num {mso-number-format:General;}.text{mso-number-format:"\@";}</style><table border=1>';

        $result = $this->model->orderByDesc('id')->get();
        $html .= '<thead><tr>';
        $html .= '<th class="text" style="width: 100px;border: 1px solid black;">№ обращения</th>';
        $html .= '<th class="text" style="width: 100px;border: 1px solid black;">ФИО/Название</th>';
        $html .= '<th class="text" style="width: 400px;border: 1px solid black;">Адрес происшествия</th>';
        $html .= '<th class="text" style="width: 100px;border: 1px solid black;">Тип заявки</th>';
        $html .= '<th class="text" style="width: 100px;border: 1px solid black;">Сервис</th>';
        $html .= '<th class="text" style="width: 100px;border: 1px solid black;">Описание</th>';
        $html .= '<th class="text" style="width: 100px;border: 1px solid black;">Дата/время обращения</th>';
        $html .= '<th class="text" style="width: 100px;border: 1px solid black;">Создатель</th>';
        $html .= '</tr></thead>';

        $date_2 = $request->input('date-2');
        $plus_day = date('Y-m-d', strtotime($date_2 . '+1 day'));
        foreach ($result as $row) {

            if(($row['request_date']>=$request->input('date-1'))&&($row['request_date']<=$plus_day)) {
//            if(($row['request_date']>'2023-01-01 08:54:00')&&($row['request_date']<'2023-12-31 23:54:00')) {
                file_put_contents('tester.txt', $row['request_date']);

                $html .= '<tbody><tr>';
                $html .= '<td class="text" style="width: 100px;border: 1px solid black;">' . $row['request_num'] . '</td>';
                $html .= '<td class="text" style="width: 100px;border: 1px solid black;">' . strip_tags($row->Client) . '</td>';
                $html .= '<td class="text" style="width: 200px;border: 1px solid black;">' . strip_tags($row->Address) . '</td>';
                $html .= '<td class="text" style="width: 100px;border: 1px solid black;">' . strip_tags($row->ResponseType) . '</td>';
                $html .= '<td class="text" style="width: 100px;border: 1px solid black;">' . strip_tags($row->ServiceName) . '</td>';
                $html .= '<td class="text" style="width: 400px;border: 1px solid black;">' . $row['description'] . '</td>';
                $html .= '<td class="text" style="width: 100px;border: 1px solid black;">' . $row['request_date'] . '</td>';
                $html .= '<td class="text" style="width: 100px;border: 1px solid black;">' . strip_tags($row->UName) . '</td>';
                $html .= '</tr></tbody>';
            }
        }
        $html .= '</table>';
        echo $html;
//ВЫГРУЗКА ЗДЕСЬ СВЕРХУ В ЦИКЛЕ
    }

    public function genReport()
    {
        function add_line($line_num, $objPHPExcel, $request_num, $name, $address, $type, $service_id, $description, $request_date, $user_id)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $line_num, $request_num);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $line_num, $name);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $line_num, $address);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $line_num, $type);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $line_num, $service_id);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $line_num, $description);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $line_num, $request_date);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $line_num, $user_id);

            //Стили ячеек
            $border_style = array('borders' => array('allborders' => array('style' =>
                PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '000'),)));
            $sheet = $objPHPExcel->getActiveSheet();
            $line = "A" . $line_num . ":H" . $line_num;
            $sheet->getStyle($line)->applyFromArray($border_style);
            $sheet->getStyle($line)->getAlignment()->setWrapText(true);
        }

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load(public_path() . '/report_templates/edds.xlsx');

        $i = 2;
        $result = $this->model->all();

        foreach ($result as $row) {
            add_line($i, $objPHPExcel, $row['request_num'], strip_tags($row->Client), strip_tags($row->Address),
                strip_tags($row->ResponseType), strip_tags($row->ServiceName), $row['description'],
                $row['request_date'], strip_tags($row->UName));
            $i++;
        }
        $filename = date("d.m.Y") . '_отчет.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }


}
