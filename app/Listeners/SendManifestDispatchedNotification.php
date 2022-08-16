<?php

namespace App\Listeners;

use App\Role;
use App\User;
use App\Events\ManifestDispatched;
use App\Services\PersonalMessageServices;
use App\Services\SmsServices;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendManifestDispatchedNotification
{

    // public $sms_services = null;
    public $person_message_services = null;
    public $email_services = null;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(/*SmsServices $sms_services,*/PersonalMessageServices $personal_message_services)
    {
        // $this->sms_services = $sms_services;
        $this->person_message_services = $personal_message_services;
    }

    /**
     * Handle the event.
     *
     * @param  ManifestDispatched  $event
     * @return void
     */
    public function handle(ManifestDispatched $event)
    {
        // $this->sendSMSNotifications($event->context);
        $this->sendPersonalMessageNotifications($event->context);
    }

    public function sendPersonalMessageNotifications($context)
    {
        //Groups that you receive site Notification
        $elgible_roles =  config('custom.manifest_notification_groups', ['Site Supervisor', 'Operations']); //['Site Supervisor', 'Operations'];
        $default_roles =  config('custom.default_manifest_notification_groups', ['Administrator']); // ['Administrator'];

        $elgible_users = User::whereHas('roles', static function ($query) use ($elgible_roles, $default_roles, $context) {
            return $query->whereIn('name', $elgible_roles)->Where('site_id', $context['manifest']->next_site_id)->orWhereIn('name', $default_roles);
        })->get();

        // $send_to_ids = $elgible_users->pluck('id');

        $send_to =  $elgible_users->pluck('id');
        $scan_site_name = $context['scan_site']->name;
        $next_site_name = $context['next_site']->name;
        $numbers_of_parcels = count($context['waybills']);
        $manifest_id = $context['manifest']->id;

        //https://github.com/nicmart/StringTemplate
        $message = "{$scan_site_name}
                    has just dispatched manifest ID ({$manifest_id})
                    containing ({$numbers_of_parcels}) parcels  to {$next_site_name}.\n
                     Kindly Acknowledge receipt within 24hrs. Thanks";

        // dd($context, $message);
        //Subject should be configurable
        $this->person_message_services->sendMessage($send_to, 'Manifest Dispatched', $message);
    }


    public function sendSMSNotifications($context)
    {
        //sms notifcation allowed ?

        $elgible_roles = Role::whereIn('name', ['Site Supervisor', 'Administrator'])->pluck('id')->toArray();
        $next_site_notification_group = User::where(['site_id' => $context['manifest']->scan_site_id])->whereIn('role_id', $elgible_roles)->pluck('phone_number');

        // $owneremail =  config('custom.smslive247.sms_owner_email');
        // $subacct =  config('custom.smslive247.sms_sub_account');
        // $subacctpwd = "speedaf@1";
        // $sender = "Speedaf-Ex"; /* sender id */ //SpeedafNG.com will be better
        // $sendto = implode(',', $next_site_notification_group->toArray()); /* destination numbers */
        $scan_site_name = $context['scan_site']->name;
        $next_site_name = $context['next_site']->name;
        $numbers_of_parcels = count($context['waybills']);
        $manifest_id = $context['manifest']->id;

        $message = "{$scan_site_name}
                    has just dispatched manifest ID ({$manifest_id})
                    containing ({$numbers_of_parcels}) parcels  to {$next_site_name}.\n
                     Kindly Acknowledge receipt within 24hrs. Thanks";


        $this->sms_services->sendManifestDispatchedMessage(null, $message);


        //    $this->sms_notification_services->sendMessage2([
        //     'manifest_id' => $manifest->id,
        //     'next_site' => $next_site->name,
        //     'scan_site' => $scan_site->name,
        //     'receiver' => implode(',', $departure_notification_group->toArray()) //"07067011296"
        // ]);

        // [
        //     'manifest_id' => $context['manifest']->id,
        //     'scan_site' => $context['scan_site']->name,
        //     'next_site' => $context['next_site']->name,
        //     'waybill_count' => count($$context['waybills']),
        //     'receivers' => implode(',', $next_site_notification_group->toArray()) //"07067011296"
        // ]


        //email notification allowed ?

        //on site notification allowed ?

    }
}
