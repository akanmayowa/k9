<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

        //Shows Listing of all Roles
        public function index() {

              $all_roles = Role::get(['name', 'id']);
              return response()->json(['success' => true, 'data' => $all_roles, 'message' => 'Roles retrieved successfully']);
        }


        public function store()
        {

                        //     $role = Role::where('name', $role_name)->first();

            //     $dispatchManifest = new Permission();
            // 	$dispatchManifest->slug = 'dispatch-manifest';
            // 	$dispatchManifest->name = 'Dispatch Manifest';
            // 	$dispatchManifest->save();
            // 	$dispatchManifest->roles()->attach($role);

            //     // $user = User::find(2340570);
            //     $this->roles()->attach($role);

            //     return "successful";
            $roles = array_map('intval', request()->input('roles'));
            $user_id = (int) request()->input('user_id');
            $user = User::find($user_id);
            $roles = Role::whereIn('id', $roles)->pluck('name');

            $user->syncRoles($roles);

           return response()->json(['success'=> true, 'message' => 'Roles has been updated successfully', $roles, $user_id]);
        }



}
