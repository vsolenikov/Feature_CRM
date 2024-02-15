<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EddsUk extends Model
{
    protected $table = 'edds_uk';
    protected $fillable = ['name','comment','active'];
    public $table_opt=[
        [
            'title' => 'УК',
            'field' => 'name',
            'cell' => 'text',
            'sortable' => true,
        ],
        [
            'title' => 'Комментарий',
            'field' => 'comment',
            'cell' => 'text',
            'sortable' => true
        ],

    ];
}
