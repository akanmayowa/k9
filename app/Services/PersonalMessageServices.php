<?php

namespace App\Services;

use Exception;
use App\PersonalMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PersonalMessageServices {


   public function __construct(){


    }

    public function sendMessageToAllUsers($from, $subject, $content)
    {
        //get all active usrs from db
        $to_users = []; //
        //call sendMessage
        $this->sendMessage($to_users, $subject, $content);

    }

    public function sendMessageToSiteUsers($sites, $from, $subject, $content)
    {
        //get all active usrs from db who are in the $sites
        $to_users = []; //
        //call sendMessage
        $this->sendMessage($to_users, $subject, $content);

    }


    public function sendMessageToRoleUsers($roles, $from, $subject, $content)
    {
        //get all active usrs from db who are in the $roles
        $to_users = []; //
        //call sendMessage
        $this->sendMessage($to_users, $subject, $content);

    }

    public function sendMessageToUsersWhoAreIn($sites, $roles, $from, $subject, $content)
    {
        //get all active usrs from db who are in the $roles and roles
        $to_users = []; //
        //call sendMessage
        $this->sendMessage($to_users, $subject, $content);

    }

    public function sendMessage($to_users, $subject, $content)
    {
        // dd($to_users, $subject, $content);
        $PMs = [];
        foreach($to_users as $user)
        {
            $PMs[] =
                [
                    'from_user_id' => 0,
                    'to_user_id' => $user,
                    'subject'=> $subject,
                    'message' => $content,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

        }

        // dd($PMs);

        try {

                PersonalMessage::insert(
                    $PMs
                );


                //log return ['success' => true, 'message' => 'Message Sent Successfully'];

        } catch(Exception $ex)
        {
                dd($ex->getMessage());
            //log return ['success' => false, 'message' => $ex->getMessage()];
        }

    }


    public function getPersonalMessagesFor($user_id)
    {
            try {

                $personal_messages = PersonalMessage::where('to_user_id', $user_id)->orderby('created_at', 'desc')->get();
                return $personal_messages;
                //log return ['success' => true, 'message' => 'Message Sent Successfully'];

        } catch(Exception $ex)
        {
                dd($ex->getMessage());
            //log return ['success' => false, 'message' => $ex->getMessage()];
        }
    }

    public function read($message_id)
    {
            try {

                DB::beginTransaction();

                PersonalMessage::where('id', $message_id)->update(['read' => 1,  'updated_at' => now()]);
                $personal_message = PersonalMessage::with(['to_user', 'from_user'])->where('id', $message_id)->orderby('created_at', 'desc')->first();
                DB::commit();

                return $personal_message;

        } catch(Exception $ex)
        {
            DB::rollBack();

            return $ex->getMessage();
        }
    }

}

?>
