<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EddsUk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edds_uk', function(Blueprint $table)
        {
            $table->integer('ID', true);
            //� ���������(������������� ������������� �� 1
            $table->integer('active')->nullable();
            //�������� �����������
            $table->string('name')->nullable();
            //�����������
            $table->text('comment')->nullable();
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
        Schema::drop('edds_uk');
    }
}
