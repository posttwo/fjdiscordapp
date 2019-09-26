<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
<<<<<<< Updated upstream

class AddModRatingNeedsReview extends Migration
=======
use Spatie\Permission\Models\Permission;

class AddModReviewerPermission extends Migration
>>>>>>> Stashed changes
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<<<<<<< Updated upstream
        Schema::table('f_j_contents', function($table) {
            $table->boolean('needsReview')->default(false);
        });
=======
        $p = new Permission();
        $p->name = 'mod.ratingReviewer';
        $p->description = 'Can review ratings';
        $p->save();
>>>>>>> Stashed changes
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
<<<<<<< Updated upstream
        Schema::table('f_j_contents', function($table) {
            $table->dropColumn('needsReview');
        });
=======
        //
>>>>>>> Stashed changes
    }
}
