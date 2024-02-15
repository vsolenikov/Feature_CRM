<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Classess\XssHtml;
class EddsRequests extends Model
{
    protected $fillable = ['request_num', 'client_name', 'address_json', 'type', 'service_id', 'description', 'request_date',
        'client_phone','date_start_problem','date_end_problem','house_cnt','szo_house_cnt','user_id'];
    protected $appends = ['ServiceName','ResponseType','Address'];

    public $table_opt = [
        [
            'title' => '№ обращения',
            'field' => 'request_num',
            'cell' => 'text',
            'sortable' => true,
        ],
        [
            'title' => 'ФИО/Название',
            'field' => 'Client',
            'cell' => 'unescaped_text',
            'sortable' => false
        ],
        [
            'title' => 'Адрес происшествия',
            'field' => 'Address',
            'cell' => 'unescaped_text',
            'sortable' => false
        ],
        [
            'title' => 'Тип заявки',
            'field' => 'ResponseType',
            'cell' => 'unescaped_text',
            'sortable' => false,
        ],
        [
            'title' => 'Сервис',
            'field' => 'ServiceName',
            'cell' => 'unescaped_text',
            'sortable' => false,
        ],
        [
            'title' => 'Описание',
            'field' => 'description',
            'cell' => 'text',
            'sortable' => true,
        ],
        [
            'title' => 'Дата/время обращения',
            'field' => 'DateReq',
            'cell' => 'text',
            'sortable' => false
        ],
        [
            'title' => 'Создатель',
            'field' => 'UName',
            'cell' => 'unescaped_text',
            'sortable' => false
        ],
    ];

    /**
     * @return string
     * Мутатор типа заявки
     */
    public function getResponseTypeAttribute()
    {
        $type_arr = explode(',', $this->type);
        $type = '';
        foreach ($type_arr as $t) {
            if ($t == 'alert') {
                $type .= '<b><i class="fa fa-warning" style="color:red;"></i> Авария</b><br/>';
            }
            if ($t == 'fast') {
                $type .= '<b><i class="fa fa-rocket" style="color:blue;"></i> Жалоба</b><br/>';
            }
            if ($t == 'plan') {
                $type .= '<b><i class="fa fa-power-off" style="color:blue;"></i> Плановое отключение</b><br/>';
            }
            if ($t == 'other') {
                $type .= '<b><i class="fa fa-info" style="color:blue;"></i> Разное</b><br/>';
            }
        }
        return $type;
    }

    /**
     * @return string
     * Имя сервиса
     */
    public function getServiceNameAttribute()
    {
        $service_name=new XssHtml\XssHtml($this->service->name);
        return '<span class="c-badge c-badge--success">' . $service_name->getHtml() . '</span>';
    }
    public function getUNameAttribute(){
        if(isset($this->user->name))
            return $this->user->name.'<br/>'.'<p class="u-text-mute u-text-xsmall">'.$this->user->position.'</p>';
        else
            return 'Пользователь не найден';
    }
    public function getClientAttribute()
    {
        $client_name=new XssHtml\XssHtml($this->client_name);
        $client_phone=new XssHtml\XssHtml($this->client_phone);
        return '<b>' . $client_name->getHtml() . '<br/>' . $client_phone->getHtml() . '</b>';
    }

    public function getAddressAttribute()
    {
        $address = 'Не указали в заявке';
        if (isset($this->address_id) || isset($this->address_json)) {
            if ($this->address_id = '') {
                $addr = EddsStreets::find($this->address_id);
                $address = $addr->fullname;
            } elseif ($this->address_json != '') {
                $address = EddsRequests::decodeAddress($this->address_json);
            }
        }
        if ($this->address_json) {
            $address .= '<hr/>' . 'Домов:' . $this->house_cnt . '<br/>Из них СЗО:' . $this->szo_house_cnt;
        }

        return $address;
    }
    public function getAddressListAttribute()
    {
        $address = 'Не указали в заявке';
        if (isset($this->address_id) || isset($this->address_json)) {
            if ($this->address_id = '') {
                $addr = EddsStreets::find($this->address_id);
                $address = $addr->fullname;
            } elseif ($this->address_json != '') {
                $address = EddsRequests::decodeAddress($this->address_json);
            }
        }

        return $address;
    }

    public function getDateReqAttribute()
    {
        return date('d.m.Y H:i:s', strtotime($this->request_date));
    }

    /*
     * Через эту функцию мы получим данные в шаблоне следующим образом
     * {{$edds->service->name}} - получим имя сервиса
     * {{$request->citizens->id}} - получим его id
    */
    public function service()
    {
        return $this->belongsTo(\App\EddsServices::class, 'service_id');
    }
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    //Выводим человекопонятный адрес
    public static function decodeAddress($result_from_db)
    {
        $result = '';
        foreach (json_decode($result_from_db) as $addr) {
            $city = EddsCities::where('code', 'like', '%' . $addr[0] . '%')->take(1)->get();
            $result .= $city[0]->name;
            if ($addr[1] != '') {
                $street = EddsStreets::find($addr[1]);

                $result .= '-' . $street->name;
            }
            if ($addr[2] != '') {
                $result .= '-' . $addr[2];
            }
            $result .= '<br/>';
        }
        return $result;
    }
    //информация по отключениям

    /**
     * @param string $type - 'plan' - плановые отключения, 'alert' - аварии
     * @param bool $date_end
     * @return EddsRequests[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getOffline($type='plan', $date_end=false){
        $DATE_START=\Carbon\Carbon::parse("15.11.2019 00:30:00");
        $NOW=\Carbon\Carbon::parse("now");
        //Дата окончания либо не заполнена,либо пуста
        if($date_end){
            return EddsRequests::where('type','=',$type)->where('created_at','>',$DATE_START)->whereNull('date_end_problem')->get();
        }else{
            return EddsRequests::where('type','=',$type)->where('created_at','>',$DATE_START)->where('date_end_problem','>',$NOW)->get();
        }
    }

}
