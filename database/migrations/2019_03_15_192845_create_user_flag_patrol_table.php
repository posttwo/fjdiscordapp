<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFlagPatrolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_flag_patrols', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cid')->nullable();
            $table->string('type')->nullable();
            $table->unsignedInteger('patrolled_by')->nullable();
            $table->unsignedInteger('flags')->default(0);
            $table->boolean('flagged')->nullable();
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
        Schema::dropIfExists('user_flag_patrols');
    }
}
