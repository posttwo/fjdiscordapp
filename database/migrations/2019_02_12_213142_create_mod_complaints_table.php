<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mod_cases', function (Blueprint $table) {
            $table->increments('id');         //Internal CaseID
            $table->string('source_type'); //fj-user-complaint
            $table->unsignedInteger('source_id');  //Complaint
            $table->string('reference_type')->nullable();   //user | comment | content
            $table->unsignedInteger('reference_id')->nullable(); // 30830390
            $table->text('reference_url')->nullable();
            $table->unsignedInteger('fj_user_id')->nullable(); //DO NOT RELATE TO FUNNYJUNKUSER
            $table->unsignedInteger('severity')->nullable();   //1 Highest | 5 Lowest
            $table->string('queue', 255)->nullable();
            $table->unsignedInteger('status')->default(0); //0 = NEW | 1 = Processed | 2 = Assigned | 3 = Locked | 4 = Resolved | 5 = Reopenned
            $table->json('user_metadata')->nullable();
            $table->json('content_metadata')->nullable();
            $table->timestamps();


            $table->index(['reference_type', 'reference_id']);
            $table->index(['source_type', 'source_id']);
            $table->index('queue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mod_cases');
    }
}
