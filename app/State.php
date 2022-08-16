<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'name', 'capital', 'created_by', 'updated_by'
    ];

    public function sites()
    {
        return $this->hasMany(Site::class);
    }
}
