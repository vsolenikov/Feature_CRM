<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EddsServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edds_services', function(Blueprint $table)
        {
            $table->integer('id', true);
            //Название
            $table->string('name')->nullable();
            //Вопросы
            $table->text('questions')->nullable();
            //Количество дней дэдлайна
            $table->integer('SERVICE_DEADLINE_DAYS')->nullable();
            //Закрепленные компании
            $table->text('attached_companies')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('edds_services');
    }
}
