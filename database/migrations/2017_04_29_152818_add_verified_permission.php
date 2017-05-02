<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

class AddVerifiedPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $p = new Permission();
        $p->name = 'user.verified';
        $p->description = 'Requires user to be verified on FunnyJunk';
        $p->save();

        $p = new Permission();
        $p->name = 'user.patreon';
        $p->description = 'Requires user to be an FJ Patreon';
        $p->save();

        $p = new Permission();
        $p->name = 'user.occreator';
        $p->description = 'Requires user to have an OC Creator Badge';
        $p->save();

        $p = new Permission();
        $p->name = 'user.level100';
        $p->description = 'Requires user to have an account level of 100';
        $p->save();

        $p = new Permission();
        $p->name = 'user.level200';
        $p->description = 'Requires user to have an account level of 200';
        $p->save();

        $p = new Permission();
        $p->name = 'user.level400';
        $p->description = 'Requires user to have an account level of 400';
        $p->save();

        $p = new Permission();
        $p->name = 'user.level10';
        $p->description = 'Requires user to have an account level of 10';
        $p->save();

        $p = new Permission();
        $p->name = 'admin.roles';
        $p->description = 'Administrative';
        $p->save();

        $p = new Permission();
        $p->name = 'admin.logs';
        $p->description = 'Administrative';
        $p->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::getQuery()->delete();
    }
}
