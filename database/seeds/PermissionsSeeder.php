<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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


        
    }
}
