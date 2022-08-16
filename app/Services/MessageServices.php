<?php

namespace App\Services;

class MessageServices
{
        public $sms_services = null;
        public $personal_message_services = null;
        public $email_services = null;

    public function __construct(PersonalMessageServices $personal_message_services)
    {
        $this->persoal_message_services = $personal_message_services;
    }

    public function sendMessageToUsers($data)
    {
        $data['message_type'] = 'PM';

        if($data['message_type']== 'PM')
        {

            $this->personal_message_services->sendMessage($data['users_id'], $data['subject'], $data['content']);
        }

    }

}
