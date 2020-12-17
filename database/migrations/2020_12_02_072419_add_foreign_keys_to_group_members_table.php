<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGroupMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->foreign('groups_id')->references('id')->on('groups')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('users_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropForeign('group_members_groups_id_foreign');
            $table->dropForeign('group_members_users_id_foreign');
        });
    }
}
