<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFJContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_j_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
            $table->string('fullsize_image');
            $table->string('thumbnail');
            $table->boolean('in_nsfw');
            $table->boolean('flagged');
            $table->string('owner');
            $table->string('title');

            $table->integer("rating_pc")->nullable();
            $table->integer("rating_skin")->nullable();
            $table->string('rating_category')->nullable();
            $table->string('flagged_as')->nullable();

            $table->unsignedInteger('attributedTo')->nullable();
            $table->boolean('hasIssue')->default(false);

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
        Schema::dropIfExists('f_j_contents');
    }
}
