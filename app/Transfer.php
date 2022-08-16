<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $guarded = ['id'];
    public function transfer_bags()
    {
        return $this->hasMany(TransferBag::class);
    }

    public function departure_site()
    {
        return $this->belongsTo(Site::class, 'departure_site_id', 'id');
    }

    public function destination_site()
    {
        return $this->belongsTo(Site::class, 'destination_site_id', 'id');
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
