<?php

namespace App;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use SoftDeletes;
    use Notifiable;
    use HasRoles;
    // use HasPermissionsTrait;

    public $incrementing = false;
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // public function Role()
    // {
    //   return  $this->belongsTo(Role::class);
    // }


    public function site()
    {
        return $this->belongsTo(Site::class);
    }


    public function Manifests()
    {
        return $this->hasMany(Manifest::class, 'manifested_by', 'id');
    }


    // public function assignRole($role_name)
    // {
    //     $role = Role::where('name', $role_name)->first();

    //     $dispatchManifest = new Permission();
	// 	$dispatchManifest->slug = 'dispatch-manifest';
	// 	$dispatchManifest->name = 'Dispatch Manifest';
	// 	$dispatchManifest->save();
	// 	$dispatchManifest->roles()->attach($role);

    //     // $user = User::find(2340570);
    //     $this->roles()->attach($role);

    //     return "successful";
    // }


    public function personal_messages()
    {
        return $this->hasMany(PersonalMessage::class, 'to_user_id', 'id')->orderBy('created_at', 'desc');
    }

    public function summary_personal_messages()
    {
        return $this->hasMany(PersonalMessage::class, 'to_user_id', 'id')->where('read', 0)->orderBy('created_at', 'desc')->take(2);
    }


    public function getPersonalMessageCountAttribute()
    {
        return $this->hasMany(PersonalMessage::class, 'to_user_id', 'id')->count();
    }


    public function getUnreadPersonalMessageCountAttribute()
    {
        return $this->hasMany(PersonalMessage::class, 'to_user_id', 'id')->where('read', 0)->count();
    }

}
