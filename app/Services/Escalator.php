<?php

namespace App\Services;

use App\EscalatorOverdueNotification;
use App\Role;
use App\User;
use App\Manifest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Escalator
{

    public $sms_Services = null;
    function __construct(SmsServices $sms_Services)
    {
        $this->sms_Services = $sms_Services;
    }
    public function Run()
    {
        //24 hours after dispatch time
        $escalation_interval = config('custom.escalation_interval_in_hours');

        $overdue_date = \Carbon\Carbon::now()->subHours($escalation_interval);
        // var_dump(\Carbon\Carbon::now()->subHours($escalation_interval)->diffForHumans());

        //Get all manifest that has not been acknowledged and were created 24hours ago
        $overdue_manifest_count = Manifest::where([
            ['dispatched_at', '<=', $overdue_date],
            ['status', '=', 0] //0 In transit
        ])->count();

        $minimum_overdue_count = 0;
        if ($overdue_manifest_count > $minimum_overdue_count) {

            //Notify Manifest Moderator(s)
            $elgible_roles = Role::whereIn('name', ['Manifest Moderator', 'Administrator', 'Site Supervisor'])->pluck('id')->toArray();

            $manifest_moderators =
                User::whereIn('role_id', $elgible_roles)->pluck('phone_number');

            // $this->sms_Services->sendOverDueMessage([
            //     'overdue_manifest_count' => $overdue_manifest_count,
            //     'receiver' => implode(',', $manifest_moderators->toArray()) //"07067011296"
            // ]);
            $now = \Carbon\Carbon::now();
            $message="Date/Time: {$now} \nTo: {implode(',', $manifest_moderators} \nOverDue Alert, \n{$overdue_manifest_count} overdue manifests found! \nKindly Check SpeedafUtility app for details \nThanks";
            Storage::append( 'Notification.txt', $message);
            //Enter into db
            EscalatorOverdueNotification::create(
                [
                    'content' => "{$overdue_manifest_count} overdue manifests found! \nKindly Check SpeedafUtility app for details \nThanks",
                ]
            );
        }
    }


}
