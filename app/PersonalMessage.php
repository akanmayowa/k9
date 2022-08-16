<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalMessage extends Model
{
    protected $fillable =
    [
        'message',
        'subject',
        'from_user_id',
        'to_user_id',
        'read',
        'created_at',
        'updated_at'
    ];

    public function to_user()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    public function from_user()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }
}
