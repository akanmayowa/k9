<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bag extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    public $incrementing = false; // primary key is a string
    protected $appends = []; // this adds custom attributes to ajax request response
    protected $hidden = [];

    public function getDisplayIdAttribute()
    {
        $pad_with = '0';
        $pad_length = 4;
        $padded_number =  str_pad($this->number, $pad_length, $pad_with, STR_PAD_LEFT);

        return $this->type .'-'. $padded_number;
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'next_or_current_site_id', 'id');
    }
}
