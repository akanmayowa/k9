<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteType extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $fillable = [
        'id',
        'name',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function sites_with_type()
    {
        return $this->hasMany(Site::class);
    }
}
