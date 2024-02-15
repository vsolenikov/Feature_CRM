<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatestratInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strat_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cords');
            $table->string('name');
            $table->string('work_hours')->nullable();
            $table->string('contacts')->nullable();
            $table->text('comment')->nullable();
            $table->string('type');
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
        Schema::dropIfExists('strat_info');
    }
}
