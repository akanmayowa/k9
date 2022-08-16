<?php

namespace App\Listeners;

use App\Events\ManifestDeleted;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\PersonalMessageServices;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManifestDeletedListener
{

        // public $sms_services = null;
        public $person_message_services = null;
        public $email_services = null;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PersonalMessageServices $personal_message_services)
        {
            // $this->sms_services = $sms_services;
            $this->person_message_services = $personal_message_services;
        }


    /**
     * Handle the event.
     *
     * @param  ManifestDeleted  $event
     * @return void
     */
    public function handle(ManifestDeleted $event)
    {
        //
    }
}
