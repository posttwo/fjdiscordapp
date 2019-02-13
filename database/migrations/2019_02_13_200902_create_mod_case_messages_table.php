<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModCaseMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mod_case_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mod_case_id');
            $table->text('title');
            $table->text('description');
            $table->boolean('internal');
            $table->unsignedInteger('fj_user_id')->nullable();
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
        Schema::dropIfExists('mod_case_messages');
    }
}
