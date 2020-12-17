<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEnvironmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('environments', function (Blueprint $table) {
            $table->foreign('blocks_id')->references('id')->on('blocks')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('groups_id')->references('id')->on('groups')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('environments', function (Blueprint $table) {
            $table->dropForeign('environments_blocks_id_foreign');
            $table->dropForeign('environments_groups_id_foreign');
        });
    }
}
