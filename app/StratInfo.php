<?php

namespace App\Modules\Strat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class StratInfo extends Model
{
 protected $table = 'strat_razvitie';
    protected $fillable = ['cords','address','name','price','nal_psd','year_realease','type'];
    public $table_opt=[
        [
            'title' => 'Наимнование объекта',
            'field' => 'name',
            'cell' => 'text',
            'sortable' => false,
        ],
        [
            'title' => 'Стоимость (тыс.р.)',
            'field' => 'price',
            'cell' => 'text',
            'sortable' => false,
        ],
        [
            'title' => 'Наличие ПСД',
            'field' => 'nal_psd',
            'cell' => 'text',
            'sortable' => false,
        ],
        [
            'title' => 'Год реализации',
            'field' => 'year_realease',
            'cell' => 'text',
            'sortable' => false,
        ],
    ];

}
