<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign('for')->references('id')->on('groups')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('by')->references('id')->on('groups')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('environments_id')->references('id')->on('environments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('situations_id')->references('id')->on('situations')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_for_foreign');
            $table->dropForeign('schedules_by_foreign');
            $table->dropForeign('schedules_environments_id_foreign');
            $table->dropForeign('schedules_situations_id_foreign');
        });
    }
}
