<?php

use App\Role;
use App\User;
use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Add slug column to role
        $administrator_role = Role::where('name','Administrator')->first();

        $dispatchManifest = new Permission();
		//$dispatchManifest->slug = 'dispatch-manifest';
		$dispatchManifest->name = 'Dispatch Manifest';
        $dispatchManifest->guard_name = 'web';
		$dispatchManifest->save();
		$dispatchManifest->roles()->attach($administrator_role);

        $user = User::find(2340570);
        $user->roles()->attach($administrator_role);
		//$user->permissions()->attach($dev_perm);

    }
}
