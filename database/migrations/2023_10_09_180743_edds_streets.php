<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EddsStreets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('edds_streets', function(Blueprint $table)
        {
            $table->integer('id', true);
            //ОКАТО
            $table->string('code')->nullable();
            //Города
            $table->text('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('edds_streets');
    }}
