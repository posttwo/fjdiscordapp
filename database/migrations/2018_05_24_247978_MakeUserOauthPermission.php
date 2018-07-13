<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

class MakeUserOauthPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $p = new Permission();
        $p->name = 'user.canUseFJMemeForSingleSignOn';
        $p->description = 'Allows a user to use FJMeme for Single Sign On using OAuth2';
        $p->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $p = Permission::where('name', 'user.canUseFJMemeForSingleSignOn')->firstOrFail();
        $p->delete();
    }
}
