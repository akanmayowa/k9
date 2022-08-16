<?php



/*

            $owneremail="emacy_245@yahoo.com";
            $subacct="Speedaf-Ex";
            $subacctpwd="speedaf@1";

*/

return [
    'default_timezone' => 'Africa/Lagos',
    'escalation_interval_in_hours' => 0.0833333,
    'smslive247' => [
        'sms_owner_email' => "emacy_245@yahoo.com",
        'sms_sub_account' => "Speedaf-Ex",
        'sms_sub_account_password' => "speedaf@1",
    ],
    'default_password' => 123456,
    'manifest_notification_groups' => [
        'Site Supervisor',
        'Operations'
    ],

    'default_manifest_notification_groups' => [
        'Administrator'
    ],

    'transport_type' => ['Air',
     'Shuttle',
      '3rd Party',
      'others'],

      'shipment_type' => [
        'Forward',
        'Reverse'
      ]
];





?>
