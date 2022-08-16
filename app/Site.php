<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{

    use SoftDeletes;
    public $incrementing = false;
    // protected $fillable = [
    // 'id',
    // 'name',
    // 'created_by',
    // 'updated_by',
    // 'created_at',
    // 'updated_at'
    // ];
        protected $guarded = [];
    public function Users()
    {
        return $this->hasMany(User::class);
    }


    public function isDC()
    {
        //Can't this be dynamic ?
        return ($this->site_type_id == 600003); // 600003 is for Distribution Centers
    }

    public function isFinanceCenter()
    {
          //Can't this be dynamic ?
        return ($this->site_type_id == 600001);
    }

    public function isTestSite()
    {
        return ($this->is_a_test_site == 1);
    }

    public function parent_site()
    {
        return $this->belongsTo(Site::class, 'parent_site_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function siteType()
    {
        return $this->belongsTo(SiteType::class, 'is_a_franchise', 'id');
    }
}
