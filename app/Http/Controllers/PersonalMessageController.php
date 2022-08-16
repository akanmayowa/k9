<?php

namespace App\Http\Controllers;

use App\PersonalMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PersonalMessageServices;

class PersonalMessageController extends Controller
{
    public $personal_message_services = null;

    public function __construct(PersonalMessageServices $personal_message_services)
    {
        $this->middleware('auth');

        $this->personal_message_services = $personal_message_services;
    }


    public function index()
    {
        $personal_messages = [];//$this->personal_message_services->getPersonalMessagesFor(Auth::id());
        return view("personal-messages.index", compact('personal_messages'));
    }

    public function read($id){

        try {

            $personal_message =  $this->personal_message_services->read($id);
            // dd($personal_message);
            return view("personal-messages.read", compact('personal_message'));

        } catch (\Exception $ex) {

            return "error o";
        }
    }

}
