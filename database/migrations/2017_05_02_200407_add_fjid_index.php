<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFjidIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('funnyjunk_users', function (Blueprint $table) {
            $table->unique('id');
            $table->index('fj_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->unique('id');
            $table->index('discord_id');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('funnyjunk_users', function (Blueprint $table) {
            $table->dropUnique('funnyjunk_users_id_unique');
            $table->dropIndex('funnyjunk_users_fj_id_index');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_id_unique');
            $table->dropIndex('users_discord_id_index');
        });
    }
}
