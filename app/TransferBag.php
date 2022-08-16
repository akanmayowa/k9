<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferBag extends Model
{
    protected $guarded = ['id'];
    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }


    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function acknowledged_by_user()
    {
        return $this->belongsTo(User::class, 'acknowledged_by', 'id');
    }
}
