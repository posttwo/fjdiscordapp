<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunnyjunkUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funnyjunk_users', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('user_id');
            $table->integer('fj_id');
            $table->string('username');
            $table->integer('level');
            $table->boolean('ismod');
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
        Schema::dropIfExists('funnyjunk_users');
    }
}
