<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // protected $fillable = ['name', 'updated_at', 'slug'];


    // public function Users()
    // {
    //     return $this->hasMany(User::class);
    // }


    // public function permissions() {

    //     return $this->belongsToMany(Permission::class,'roles_permissions');

    //  }

    //  public function Users() {

    //     return $this->belongsToMany(User::class,'users_roles');

    //  }

    //  public function myHelper()
    //  {
    //     $roles = Role::all();
    //     foreach($roles as $role)
    //     {
    //             $slug = Str::slug($role->name);
    //             $role->update(['slug' => $slug, 'updated_at' => now()]);
    //     }

    //     // return "finished";
    //  }

}
