<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EddsAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edds_address', function(Blueprint $table)
        {
            $table->integer('id', true);
            //УНИКАЛЬНЫЙ id адреса
            $table->string('aoguid')->nullable();
            //ОКАТО
            $table->string('okato')->nullable();
            //Почтовый индекс
            $table->string('postalcode')->nullable();
            //Адресс полностью
            $table->text('fullname')->nullable();
            //Улица
            $table->string('address')->nullable();
            //№ дома
            $table->string('housenum')->nullable();
            //id управляющей компании
            $table->integer('managed_by')->nullable();
            //id пользователя
            $table->string('updated_by')->nullable();
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
        //
        Schema::drop('edds_address');
    }
}
