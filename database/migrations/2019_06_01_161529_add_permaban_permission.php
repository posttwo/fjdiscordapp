<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
class AddPermabanPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $p = new Permission();
        $p->name = 'mod.permabanuser';
        $p->description = 'Allows user to permaban FJ User';
        $p->save();
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $p = Permission::where('name', 'mod.permabanuser')->firstOrFail();
        $p->delete();
    }
}
