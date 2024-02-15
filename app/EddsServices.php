<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EddsServices extends Model
{
    protected $table = 'edds_services';
    protected $fillable = ['name','questions'];
    //нет полей timestamp
    public $timestamps = false;

    public $table_opt=[
        [
            'title' => 'Сервис',
            'field' => 'name',
            'cell' => 'text',
            'sortable' => true,
        ],
        [
            'title' => 'Вопрос',
            'field' => 'questions',
            'cell' => 'text',
            'sortable' => true
        ]
    ];
}
