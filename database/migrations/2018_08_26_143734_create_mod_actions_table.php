<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mod_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('date');
            $table->longText('info');
            $table->string('url')->nullable();
            $table->string('category');
            $table->unsignedInteger('user_id');
            $table->string('reference_type');
            $table->unsignedInteger('reference_id');
            $table->integer('is_public');
            $table->integer('modifier')->nullable();
            $table->string('fullsize_image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('in_nsfw')->default(false);
            $table->boolean('flagged')->default(false);
            $table->boolean('fullsize_exist')->default(false);
            $table->longText('text')->nullable();
            $table->string('owner')->nullable();
            $table->string('title')->nullable();
            $table->string('role_name');

            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mod_actions');
    }
}
