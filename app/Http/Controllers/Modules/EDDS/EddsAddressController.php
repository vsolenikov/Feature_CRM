<?php

namespace App\Http\Controllers\Modules\EDDS;

use App;
use App\Classess\Helpers\BaseCrudController;
use App\EddsAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EddsAddressController extends BaseCrudController
{
    public function __construct(EddsAddress $model, Request $requests)
    {
        $this->model = $model;
        $this->template_prefix = 'edds.address';
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
    public function getCities(){
        return json_encode(\App\EddsCities::all()->toArray());
    }
    public function getStreets(Request $request){
        return json_encode(\App\EddsStreets::where(['code'=>$request->get_street])->get()->toArray());
    }
    public function getHouseCnt(Request $request)
    {
        return json_encode(\App\EddsAddress::where('okato','like','%'.$request->code.'%')->where('address','like','%'.$request->street.'%')->get()->count());

    }
}
