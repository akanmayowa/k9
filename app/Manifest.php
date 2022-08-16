<?php

namespace App;

use App\Enums\WaybillStatus;
use App\ManifestBag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manifest extends Model
{

    use SoftDeletes;
    //DB::statement("ALTER TABLE your_table_here SET AUTO_INCREMENT = 9999;");
    protected $dates = ['acknowledge_at', 'deleted_at', 'created_at', 'updated_at'];
    //use protected guard array
    // protected $fillable =
    // [
    //     // 'seal_number',
    //     // 'means_of_movement',
    // 'id', //https://stackoverflow.com/questions/60041436/how-to-let-id-autoincrement-start-from-certain-number-in-laravel-migration
    // 'scan_site_id',
    // 'next_site_id',
    // 'status',
    // 'is_flagged',
    // // 'is_deleted',
    // 'acknowledged_by', //change this to received by because partially / full will now be implemented on the UI
    // 'created_by',
    // 'updated_by',
    // 'acknowledge_at',
    // 'created_at',
    // 'updated_at',
    // ''
    // ];

    protected $guarded = [];

    public function waybills()
    {
        return $this->hasMany(Waybill::class);
    }

    public function acknowledged_waybills()
    {
        return $this->hasMany(Waybill::class)->where('status', 1);
    }

    public function pending_waybills()
    {
        return $this->hasMany(Waybill::class)->where('status', '!=', 1);
    }


    public function dispatched_waybills()
    {
        return $this->hasMany(Waybill::class);
    }

    // public function items()
    // {
    //     return $this->hasMany(Waybill::class);
    // }

    // public function getIdAttribute($value) {
    //     return str_pad($value,11,'0',STR_PAD_LEFT);
    // }

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

    public function acknowledged_by_user()
    {
        return $this->belongsTo(User::class, 'acknowledged_by', 'id');
    }

    public function getAcknowledgedWaybillsCountAttribute()
    {
        return $this->hasMany(Waybill::class)->where('status', '=', WaybillStatus::ACKNOWLEDGED)->count();
    }

    public function bags()
    {
        return $this->hasMany(ManifestBag::class, 'manifest_id', 'id');
    }

    public function getIdDisplayAttribute()
    {
        return 'MF'.str_pad($this->id, 11, '0', STR_PAD_LEFT);
    }


}
