<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('environments_id')->index();
            $table->unsignedBigInteger('for')->index();
            $table->unsignedBigInteger('by')->index();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('situations_id')->index();
            $table->timestamps();
            $table->index(['environments_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
