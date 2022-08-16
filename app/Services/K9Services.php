<?php

namespace App\Services;

use App\Site;
use App\User;
use Exception;
use App\K9Site;
use App\Waybill;
use App\K9Employee;
use App\K9DepartureScan;
use App\K9User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class K9Services
{
    function __construct()
    {
        echo "k9DatabaseServices service has been created\n";
    }



        public function synchronizeSiteTable($user)
        {
                try
                {
                    $last_created_at_site =  Site::max('created_at');
                    $newly_added_sites = K9Site::where('CREATE_DATE', '>', $last_created_at_site)->get();
                    $sites_to_add = [];
                    foreach ($newly_added_sites as $k9_site)
                    {
                    $sites_to_add[] =
                        [
                            'id'   =>  $k9_site->SITE_CODE,
                            'name' => $k9_site->SITE_NAME,
                            'created_by' => (int)$k9_site->CREATE_MAN_CODE,
                            'created_at' => Carbon::parse($k9_site->CREATE_DATE),
                            'updated_at' => Carbon::now(),
                            'updated_by' => $user->id,
                            'updated_on_k9_at' => Carbon::parse($k9_site->MODIFY_DATE),
                            'updated_on_k9_by' => (int)$k9_site->MODIFIER_CODE,
                            'parent_site_id' => (int)$k9_site->SUPERIOR_SITE_CODE,
                            'is_disabled' => (int)$k9_site->BL_NOT_INPUT,
                            'address' => $k9_site->DEFAULT_SEND_PLACE,
                            'site_type_id' => (int)$k9_site->TYPE_CODE,
                            'is_a_franchise' => ((int)$k9_site->SITE_NATURE_CODE == 10001),
                        ];
                    }
                    if(count($sites_to_add) > 0)
                    {
                        Site::insert($sites_to_add);
                    }

                    $last_updated_at_site = Site::max('updated_on_k9_at');
                    $newly_update_sites = K9Site::where('MODIFY_DATE', '>', $last_updated_at_site)->get();

                    foreach($newly_update_sites as $k9_site)
                    {
                        Site::where('id', (int)$k9_site->SITE_CODE)->update([
                            'name' => $k9_site->SITE_NAME,
                            'created_by' => (int)$k9_site->CREATE_MAN_CODE,
                            'created_at' => Carbon::parse($k9_site->CREATE_DATE),
                            'updated_at' => Carbon::now(),
                            'updated_by' => $user->id,
                            'updated_on_k9_at' => Carbon::parse($k9_site->MODIFY_DATE),
                            'updated_on_k9_by' => (int)$k9_site->MODIFIER_CODE,
                            'parent_site_id' => (int)$k9_site->SUPERIOR_SITE_CODE,
                            'is_disabled' => (int)$k9_site->BL_NOT_INPUT,
                            'address' => $k9_site->DEFAULT_SEND_PLACE,
                            'site_type_id' => (int)$k9_site->TYPE_CODE,
                            'is_a_franchise' => ((int)$k9_site->SITE_NATURE_CODE == 10001),
                        ]);
                 }

                    return [
                        'success' => true,
                        'message' => 'Sites Table Synchronized successfully',
                        'data' => [
                            'newly_added_sites' => $newly_added_sites->pluck('SITE_CODE', 'SITE_NAME'),
                            'newly_update_sites' => $newly_update_sites->pluck('SITE_CODE', 'SITE_NAME')
                        ]
                    ];
            }

                catch (Exception $ex)
                {
                    return ['success' => false, 'message' => 'Failed to synchronize Sites Table\n' . $ex->getMessage()];

                }

        }

    public function synchronizeEmployeeTable($current_user)
    {
        try {
            $last_created_at = User::max('created_at');
            $newly_added_users = K9Employee::where('CREATE_DATE', '>', $last_created_at)->get();
            $users_to_add = [];
            $default_password = Hash::make(config('custom.default_password', 123456));
            foreach ($newly_added_users as $user) {
                $users_to_add[] =   [
                    'id'   =>  (int)$user->EMPLOYEE_CODE,
                    'name' => $user->EMPLOYEE_NAME,
                    'password' => $default_password,
                    'created_by' => (int)$user->CREATE_MAN,
                    'created_at' => Carbon::parse($user->CREATE_DATE),
                    'updated_at' => Carbon::now(),
                    'updated_by' => $current_user->id,
                    'updated_on_k9_at' => Carbon::parse($user->MODIFY_DATE),
                    'updated_on_k9_by' => (int)$user->MODIFY_MAN_CODE,
                    'site_id' => (int)$user->OWNER_SITE_CODE,
                    'is_disabled' => (int)$user->BL_DELETE,
                    'timezone' =>  config('custom.default_timezone', 'Africa/Lagos')
                ];
            }
            if (count($users_to_add) > 0)
             {
                User::insert($users_to_add);
             }
            $last_updated_at = User::max('updated_on_k9_at');
            $newly_update_users = K9Employee::where('MODIFY_DATE', '>', $last_updated_at)->get();
            foreach ($newly_update_users as $user) {
                User::where('id', (int)$user->EMPLOYEE_CODE)->update(
                    [
                        'name' => $user->EMPLOYEE_NAME,
                        // 'password' => $default_password,
                        'created_by' => (int)$user->CREATE_MAN,
                        'created_at' => Carbon::parse($user->CREATE_DATE),
                        'updated_on_k9_at' => Carbon::parse($user->MODIFY_DATE),
                        'updated_on_k9_by' => (int)$user->MODIFY_MAN_CODE,
                        'site_id' => (int)$user->OWNER_SITE_CODE,
                        'is_disabled' => (int)$user->BL_DELETE,
                        'updated_at' => Carbon::now(),
                        'updated_by' => $current_user->id,
                    ]
                );
            }
            return [
                'success' => true,
                'message' => 'Employee Table Synchronized successfully',
                'data' => [
                    'newly_added_users' => $newly_added_users->pluck('EMPLOYEE_CODE', 'EMPLOYEE_NAME'),
                    'newly_update_users' => $newly_update_users->pluck('EMPLOYEE_CODE', 'EMPLOYEE_NAME')
                ]
            ];
        } catch (Exception $ex)
        {
            return ['success' => false, 'message' => 'Failed to synchronize Employee Table\n' . $ex->getMessage()];
        }
    }


      public function synchronizeSiteTableWithUpdateFxn($user)
        {
            $k9_site = K9Site::all();
            $site  = Site::all();
            try{
                    $site ->update([
                        'id' =>$k9_site->SITE_NAME,
                        'created_by' => (int)$k9_site->CREATE_MAN_CODE,
                        'created_at' => Carbon::parse($k9_site->CREATE_DATE),
                        'updated_at' => Carbon::now(),
                        'updated_by' => $user,
                        'updated_on_k9_at' => Carbon::parse($k9_site->MODIFY_DATE), //Carbon parse ?
                        'updated_on_k9_by' => (int)$k9_site->MODIFIER_CODE,
                        'parent_site_id' => (int)$k9_site->SUPERIOR_SITE_CODE,
                        'is_disabled' => (int)$k9_site->BL_NOT_INPUT,
                        'site_type_id' => (int)$k9_site->TYPE_CODE,
                        'country_id' => null,
                        'state_id' => null,
                        'address' => null,
                        'is_a_test_site' => 0,
                        'is_a_franchise' => ((int)$k9_site->SITE_NATURE_CODE == 10001)
                    ]);
                    return [
                        'success' => true,
                        'message' => 'Site Table Synchronized successfully',
                    ];
                }
            catch(Exception $ex)
                {
                    return ['success' => false, 'message' => 'Failed to synchronize Employee Table\n' . $ex->getMessage()];
                }

        }



          public function synchronizeSiteTableWithCreateFxn($user)
            {
                $k9_site = K9Site::all();
                $site  = Site::all();
                try{
                        $site->create([
                            'id' =>$k9_site->SITE_NAME,
                            'created_by' => (int)$k9_site->CREATE_MAN_CODE,
                            'created_at' => Carbon::parse($k9_site->CREATE_DATE),
                            'updated_at' => Carbon::now(),
                            'updated_by' => $user,
                            'updated_on_k9_at' => Carbon::parse($k9_site->MODIFY_DATE), //Carbon parse ?
                            'updated_on_k9_by' => (int)$k9_site->MODIFIER_CODE,
                            'parent_site_id' => (int)$k9_site->SUPERIOR_SITE_CODE,
                            'is_disabled' => (int)$k9_site->BL_NOT_INPUT,
                            'site_type_id' => (int)$k9_site->TYPE_CODE,
                            'country_id' => null,
                            'state_id' => null,
                            'address' => null,
                            'is_a_test_site' => 0,
                            'is_a_franchise' => ((int)$k9_site->SITE_NATURE_CODE == 10001)
                        ]);
                        return [
                            'success' => true,
                            'message' => 'Site Table Synchronized successfully',
                        ];
                    }
                catch   (Exception $ex)
                    {
                        return ['success' => false, 'message' => 'Failed to synchronize Employee Table\n' . $ex->getMessage()];
                    }
            }

}
