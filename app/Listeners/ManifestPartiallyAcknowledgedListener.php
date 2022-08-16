<?php

namespace App\Listeners;

use App\User;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\PersonalMessageServices;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\ManifestPartiallyAcknowledged;

class ManifestPartiallyAcknowledgedListener
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
     * @param  ManifestPartiallyAcknowledged  $event
     * @return void
     */
    public function handle(ManifestPartiallyAcknowledged $event)
    {
        $this->sendPersonalMessageNotifications($event->context);
    }



    public function sendPersonalMessageNotifications($context)
    {
        //Groups that you receive site Notification
        $elgible_roles = ['Site Supervisor'];
        $default_roles = ['Administrator'];

        $elgible_users = User::whereHas('roles', static function ($query) use ($elgible_roles, $default_roles, $context) {
            return $query->whereIn('name', $elgible_roles)->Where('site_id', $context['manifest']->scan_site_id)->orWhereIn('name', $default_roles);
        })->get();

        // $send_to_ids = $elgible_users->pluck('id');

        $send_to =  $elgible_users->pluck('id');
        $scan_site_name = $context['manifest']->scan_site->name; //$context['scan_site']->name;
        $next_site_name = $context['manifest']->next_site->name; // $context['next_site']->name;
        // $numbers_of_parcels = count($context['waybills']);
        $manifest_id = $context['manifest']->id;

        //https://github.com/nicmart/StringTemplate
        $message = "{$next_site_name} has just Partially Acknowledged Manifest ID ({$manifest_id}) \n sent from {$scan_site_name}.  Thanks";

        // dd($context, $message);
        $this->person_message_services->sendMessage($send_to, 'Partial Acknowlegement!', $message);
    }
}
