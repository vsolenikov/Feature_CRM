<?php

namespace App\Http\Controllers\Modules\EDDS;

use App;
use App\Classess\Helpers\BaseCrudController;
use App\EddsUk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EddsUkController extends BaseCrudController
{
    public function __construct(EddsUk $model, Request $requests)
    {
        $this->model = $model;
        $this->template_prefix = 'edds.uk';
        $this->fields_like = ['name', 'email', 'address', 'phone'];
        $this->store_validate = [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ];
        $this->update_validate = [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ];
        $this->pagination_limit = 10;
        $this->table_options = $this->model->table_opt;
        //Вывод кнопок

        $this->table_options[] = [
            'cell' => 'action',
            'options' => [
                'buttons' => [
                    //['icon-class'=>'fa fa-plus','button-text'=>'обращение','url'=>'/requests/createCi?id=','confirm'=>'Сформировать новое обращение?'],
                ],
                'show_edit_button' => true,
                'show_delete_button' => false
            ]
        ];
        $this->detail_variable = true;
    }

    //В блоке ЕДДС нет детальной страницы
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
        $val = 0;
        if ($request->is_firm == 'on') $val = 1;
        $req = [

            'name' => $request->name,
            'is_firm' => $val,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'comment' => $request->comment,
        ];
        return $req;
    }

    public function getServices(){
        return json_encode(EddsUk::select('id','name')->get()->toArray());
    }
}
