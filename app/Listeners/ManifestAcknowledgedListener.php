<?php

namespace App\Listeners;

use App\Role;
use App\User;
use App\Events\ManifestAcknowledged;
use App\Services\PersonalMessageServices;
use App\Services\SmsServices;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
class ManifestAcknowledgedListener
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
     * @param  ManifestAcknowledged  $event
     * @return void
     */
    public function handle(ManifestAcknowledged $event)
    {
        $this->sendPersonalMessageNotifications($event->context);
    }



    public function sendPersonalMessageNotifications($context)
    {
        //Groups that you receive site Notification
        // $elgible_roles = ['Site Supervisor'];
        // $default_roles = ['Administrator'];
       //Groups that you receive site Notification
       $elgible_roles =  config('custom.manifest_notification_groups', ['Site Supervisor', 'Operations']); //['Site Supervisor', 'Operations'];
       $default_roles =  config('custom.default_manifest_notification_groups', ['Administrator']); // ['Administrator'];

        $elgible_users = User::whereHas('roles', static function ($query) use ($elgible_roles, $default_roles, $context) {
            return $query->whereIn('name', $elgible_roles)->Where('site_id', $context['manifest']->scan_site_id)->orWhereIn('name', $default_roles);
        })->get();

        //Send Message to acknowleging site too

        // $send_to_ids = $elgible_users->pluck('id');

        $send_to =  $elgible_users->pluck('id');
        $scan_site_name = $context['manifest']->scan_site->name; //$context['scan_site']->name;
        $next_site_name = $context['manifest']->next_site->name; // $context['next_site']->name;
        // $numbers_of_parcels = count($context['waybills']);
        $manifest_id = $context['manifest']->id;

        //https://github.com/nicmart/StringTemplate
        $message = "{$next_site_name} has just Acknowledged Manifest ID ({$manifest_id}) \n sent from {$scan_site_name}.  Thanks";

        // dd($context, $message);
        $this->person_message_services->sendMessage($send_to, 'Manifest Acknowledged!', $message);
    }

}
