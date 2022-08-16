<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Manifest;

class TestViewController extends Controller
{
    public function index()
    {
        // $user = User::find(23410600);
        // $manifest = Manifest::find(2);
        // if($user->can('cancel', $manifest))
        // {
        //     return "Yes, you can cancel this manifest";
        // }
        // else {
        //     return "No. You cannot";
        // }
        // // return "Hello";

        return view('timeline');
    }



}
