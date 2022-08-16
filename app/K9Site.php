<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class K9Site extends Model
{
    protected $table = 'NRLY.TAB_SITE';
    protected $connection = 'K9_server';
    protected $primaryKey = 'SITE_CODE';
    public $incrementing = false;
}
