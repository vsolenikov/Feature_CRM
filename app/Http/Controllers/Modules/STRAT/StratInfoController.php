<?php

namespace App\Http\Controllers\Modules\Strat;

use App;
use App\Classess\Helpers\BaseCrudController;
use App\Modules\Strat\StratInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StratInfoController extends BaseCrudController
{
    public function __construct(StratInfo $model, Request $requests)
    {
        $this->model = $model;
        $this->template_prefix = 'Strat';
        $this->store_validate = [
            'name' => 'required',
            'cords' => 'required',
        ];
        $this->update_validate = [
            'name' => 'required',
            'cords' => 'required',
        ];
        $this->pagination_limit = 20;
        $this->table_options = $this->model->table_opt;

        $this->middleware(function ($request, $next) {
            $user_acc=explode(',',Auth::user()->module_access);

            if (in_array('Strat',$user_acc)){
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
                        ]
                    ]
                ];
            }
            return $next($request);
        });
        $this->detail_variable = false;
    }

    public function detailPage($id)
    {

        return [];
    }

    public function inputStore(Request $request)
    {
        return $this->inputUpdate($request);
    }

    public function inputUpdate(Request $request)
    {

        $req = [
            'name' => $request->name,
            'cords' => $request->cords,
            'address' => $request->address,
            'price' => $request->price,
            'nal_psd' => $request->nal_psd,
            'year_realease' => $request->year_realease,
            'type' => $request->type,
        ];
        return $req;
    }

    /**
     * @return array
     */
    public function save(Request $request)
    {
        $arr['ecology']['total']=$request->ecology_total;

        $arr['jkh']['total']=$request->jkh_total;

        $arr['roads']['total']=$request->roads_total;

        $arr['economy']['total']=$request->economy_total;

        $arr['society']['total']=$request->society_total;

        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/data/strat.json', json_encode($arr));
        return redirect('/Strat');
    }
    public function ShowMap(Request $request){
        $u_info=Auth::user();
        $organizations=StratInfo::all()->toArray();
        return view('Strat.map',[
            'u_info'=>$u_info,'organisations'=>$organizations
        ]);
    }

public function ShowMapEclg(Request $request)
    {
        $u_info = Auth::user();
        $organizations = StratInfo::all()->toArray();
        return view('Strat.map_eclg', [
            'u_info' => $u_info, 'organisations' => $organizations
        ]);
    }
    public function ShowMapJkh(Request $request)
    {
        $u_info = Auth::user();
        $organizations = StratInfo::all()->toArray();
        return view('Strat.map_jkh', [
            'u_info' => $u_info, 'organisations' => $organizations
        ]);
    }
    public function ShowMapRoads(Request $request)
    {
        $u_info = Auth::user();
        $organizations = StratInfo::all()->toArray();
        return view('Strat.map_roads', [
            'u_info' => $u_info, 'organisations' => $organizations
        ]);
    }
    public function ShowMapEconom(Request $request)
    {
        $u_info = Auth::user();
        $organizations = StratInfo::all()->toArray();
        return view('Strat.map_ecnm', [
            'u_info' => $u_info, 'organisations' => $organizations
        ]);
    }
    public function ShowMapSoc(Request $request)
    {
        $u_info = Auth::user();
        $organizations = StratInfo::all()->toArray();
        return view('Strat.map_soc', [
            'u_info' => $u_info, 'organisations' => $organizations
        ]);
    }

    public function ShowMapPublic(Request $request){
        $u_info=Auth::user();
        $organizations=StratInfo::all()->toArray();
        return view('Strat.public.map',[
            'u_info'=>$u_info,'organisations'=>$organizations
        ]);
    }
    public function AllJson(){
        return StratInfo::all()->toJson();
    }
}
