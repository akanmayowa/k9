<?php

namespace App\Http\Controllers;

use App\EscalatorOverdueNotification;
use Illuminate\Http\Request;

class EscalatorController extends Controller
{
    public function notifications(){

        $notifications = EscalatorOverdueNotification::orderBy('created_at', 'Desc')->get();;
        return view('escalator.notification', compact('notifications'));
    }
}
