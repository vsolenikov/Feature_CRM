<?php

namespace App\Http\Controllers\Modules\EDDS;

use App;
use App\Classess\Helpers\BaseCrudController;
use App\EddsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EddsServicesController extends BaseCrudController
{
    public function __construct(EddsServices $model, Request $requests)
    {
        $this->model = $model;
        $this->template_prefix = 'edds.services';
        $this->fields_like = ['name'];
        $this->store_validate = [
            'name' => 'required',
        ];
        $this->update_validate = [
            'name' => 'required',
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
        $this->detail_variable=true;
    }
    //В блоке ЕДДС нет детальной страницы
    public function detailPage($id)
    {

        return ['table'=>false];
    }

    public function inputStore(Request $request)
    {
        return $this->inputUpdate($request);
    }

    public function inputUpdate(Request $request)
    {
        $req= [

            'name' => $request->name,
            'questions' =>$request->questions,
        ];
        return $req;
    }

    public function getAnswers(Request $request){
        return json_encode(EddsServices::find($request->ID)->toArray());
    }
}
