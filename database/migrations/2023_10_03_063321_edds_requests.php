<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EddsRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edds_requests', function(Blueprint $table)
        {
            $table->integer('id', true);
            //№ обращения(автоматически увеличивается на 1
            $table->integer('request_num')->nullable();
            //Фио заявителя или название организации
            $table->string('client_name')->nullable();
            //Телефон заявителя
            $table->string('client_phone')->nullable();
            //Адресс происшествия
            $table->text('address_json')->nullable();
            //Тип заявки
            $table->string('type')->nullable();
            //id сервиса
            $table->integer('service_id')->nullable();
            //Описание заявки
            $table->text('description')->nullable();
            //id пользователя
            $table->integer('user_id')->nullable();
            //Количество домов
            $table->integer('house_cnt')->nullable();
            //Количество домов СЗО в заявке
            $table->integer('szo_house_cnt')->nullable();
            //Статус
            $table->string('status')->nullable();
            //Дата начала проблемы
            $table->dateTime('date_start_problem')->nullable();
            //Окончание проблемы
            $table->dateTime('date_end_problem')->nullable();
            //Дата обращения
            $table->dateTime('request_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('edds_requests');
    }
}
