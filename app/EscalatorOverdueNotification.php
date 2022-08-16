<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EscalatorOverdueNotification extends Model
{
    protected $fillable = ['content', 'created_at',
    'updated_at'];
}
