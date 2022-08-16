<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScanTimestamp extends Model
{
    protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at'];
    protected $guarded = [''];

    public function scan_site()
    {
        return $this->belongsTo(Site::class, 'scan_site_id', 'id');
    }

    public function next_site()
    {
        return $this->belongsTo(Site::class, 'next_site_id', 'id');
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function scanner()
    {
        return $this->belongsTo(User::class, 'scanner_id', 'id');
    }

    public function waybills_count()
    {

    }

}
