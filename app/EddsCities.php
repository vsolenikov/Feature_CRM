<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EddsCities extends Model
{
    protected $table = 'edds_cities';
    protected $fillable = ['code','name'];
    public $table_opt=[
        [
            'title' => 'окато',
            'field' => 'code',
            'cell' => 'text',
            'sortable' => true,
        ],
        [
            'title' => 'город',
            'field' => 'name',
            'cell' => 'text',
            'sortable' => true
        ],

    ];
}
