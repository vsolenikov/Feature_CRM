<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EddsAddress extends Model
{
    protected $table = 'edds_address';
    protected $fillable = ['name','comment','active'];
    public $table_opt=[
        [
            'title' => 'Адрес',
            'field' => 'fullname',
            'cell' => 'text',
            'sortable' => true,
        ],
        [
            'title' => 'Управляющая компания',
            'field' => 'managed_by',
            'cell' => 'text',
            'sortable' => true
        ],

    ];
}
