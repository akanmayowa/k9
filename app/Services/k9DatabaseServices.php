<?php

namespace App\Services;

use App\Site;
use App\User;
use Exception;
use App\K9Site;
use App\Waybill;
use App\K9Employee;
use App\K9DepartureScan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class K9DatabaseServices
{
    function __construct()
    {
        // echo "k9DatabaseServices service has been created\n";

    }
/*
    $data[
        'start_date' => null,
        'end_date' => null
        'scan_site_id' => null,
        'site_of_scan' => null
        'next_site' => null
    ]

 sample call
$dbs = new K9DatabaseServices();
$dbs->getDepartureScans([
'scanner_id' => null,
'scan_site_id' => 234,
'next_site_id' => null,
'excluded_waybills' => null,
'start_date' => Carbon\Carbon::today()->toDateString(),
'end_date' => Carbon\Carbon::today()->toDateString(),
'order_by' => null,
'direction' => null
]);

sample call 2
 $dbs->getDepartureScans([
'scanner_id' => 23410600,
'scan_site_id' => 2341,
'next_site_id' => 234134,
'excluded_waybills' => ['47234208551574'],
'start_date' => Carbon\Carbon::today()->toDateString(),
'end_date' => Carbon\Carbon::today()->toDateString(),
'order_by' => 'BILL_CODE',
'direction' => 'ASC'
]);



*/
    public function getDepartureScans($data)
    {

        /*Todo Filter by Exact time of scan
            $start_time:
            $end_time:
        */

        try {

            // $start_date = // Carbon::today()->toDateString(); //Date range might change later
            // $end_date =  //Carbon::today()->toDateString();
            // $scan_site_id =  $data['site_id']; //Assummed only logged in user can access this controller

            // //Validate SCAN_SITE
            // if ($data['scan_site_id'] != null) {
            //     throw new Exception("Scan site is not specified");
            // }

            // if (!request()->has('next_site_id')) {
            //     throw new Exception("next site not specified!");
            // }

            // $scanner_id = null;
            // if (request()->has('scanner_id')) {
            //    $scanner_id = request()->get('scanner_id');
            // }

            // //Validate NEXT_SITE
            // $next_site_id = (int)request()->get('next_site_id');
            // $next_site = Site::find($next_site_id);





            // $query = k9DepartureScan::with('employee');



            /*
               //Should I use SCAN_DATE or REGISTER_DATE -- Please document this
                    Model::where(function ($query) use ($a,$b) {
                        $query->where('a', '=', $a)
                            ->orWhere('b', '=', $b);
                    })
                    ->where(function ($query) use ($c,$d) {
                        $query->where('c', '=', $c)
                            ->orWhere('d', '=', $d);
                    });

            */
            //     //[1, 2, 4]
            //     $waybills = k9DepartureScan::where('SCAN_SITE_CODE', $scan_site_id)->where('PRE_OR_NEXT_STATION_CODE', $next_site_id)->whereDate('SCAN_DATE', $date)->OrderBy('SCAN_DATE', 'DESC')->get();

            //     //[2]
            //     $already_departed = Waybill::where('scan_site_id', $scan_site_id)->where('next_site_id', $next_site_id)->whereDate('created_at', Carbon::today())->get('id');


            //    //[1, 4]
            //     $filtered_waybills = collect($waybills)->filter(function ($waybill, $key) use($already_departed) {
            //         return !($already_departed->contains($waybill->BILL_CODE));
            //     });


            //[2]
            // $already_departed = Waybill::where('scan_site_id', $scan_site_id)
            //     ->where('next_site_id', $next_site_id)
            //     ->whereDate('created_at', Carbon::today())
            //     ->pluck('id');
            //[1, 2, 4]

            //is laravel case sensitive ?
            $query = K9DepartureScan::query();
            if($data['scanner_id'] != null)
            {
                $query->where('SCAN_MAN_CODE', $data['scanner_id']);
            }

            if($data['scan_site_id'] != null)
            {
                $query->where('SCAN_SITE_CODE', $data['scan_site_id']);
            }

            if($data['next_site_id'] != null)
            {
                $query->where('PRE_OR_NEXT_STATION_CODE', $data['next_site_id']);
            }

            if($data['excluded_waybills'] != null)
            {
                $query->whereNotIn('BILL_CODE', $data['excluded_waybills']);
            }

            //Validation unkor ? Can end date be greather than start date ?
            $start_date =Carbon::today()->toDateString();
            if($data['start_date'] != null)
            {
                $start_date = $data['start_date'];
            }


            $end_date = Carbon::today()->toDateString();

            if($data['end_date'] != null)
            {
                $end_date = $data['end_date'];
            }
            //wheereBetween & protect $dtes column could not help ? No time to check why
            $query->whereDate('SCAN_DATE', '>=', $start_date)->whereDate('SCAN_DATE', '<=', $end_date);

            $order_by = 'BILL_CODE';
            $direction = 'ASC';
            if($data['order_by'] != null)
            {
               $order_by = $data['order_by'];
            }

            if($data['direction'] != null)
            {
               $direction = $data['direction'];
            }

            $query->OrderBy($order_by, $direction);



            // echo $query->count();
            // return;
            //Finally Get the query oo
            $waybills = $query->get();



            $filtered_waybills = $waybills;
            //  //[1, 4]
            //   $filtered_waybills = collect($waybills)->filter(function ($waybill, $key) use($already_departed) {
            //       return !($already_departed->contains($waybill->BILL_CODE));
            //   });


                return
                [
                'success' => true,
                 'message' => 'Departure Scans Retrieved successfully',
                 'data' => $filtered_waybills/*$waybills*/,
                 'request' => $data

                   ];
        } catch (Exception $ex) {

            return
                ['success' => false,
                'message' => 'could not retrieve departure scans',
                 'data' => null,
                  'scan_site_id' => $data['scan_site_id'],
                  'next_site_id' => $data['next_site_id'],
                ];
        }
    }


    public function getNumberOfDepartureScans($data)
    {

        /*Todo Filter by Exact time of scan
            $start_time:
            $end_time:
        */

        try {

            // $start_date = // Carbon::today()->toDateString(); //Date range might change later
            // $end_date =  //Carbon::today()->toDateString();
            // $scan_site_id =  $data['site_id']; //Assummed only logged in user can access this controller

            // //Validate SCAN_SITE
            // if ($data['scan_site_id'] != null) {
            //     throw new Exception("Scan site is not specified");
            // }

            // if (!request()->has('next_site_id')) {
            //     throw new Exception("next site not specified!");
            // }

            // $scanner_id = null;
            // if (request()->has('scanner_id')) {
            //    $scanner_id = request()->get('scanner_id');
            // }

            // //Validate NEXT_SITE
            // $next_site_id = (int)request()->get('next_site_id');
            // $next_site = Site::find($next_site_id);





            // $query = k9DepartureScan::with('employee');



            /*
               //Should I use SCAN_DATE or REGISTER_DATE -- Please document this
                    Model::where(function ($query) use ($a,$b) {
                        $query->where('a', '=', $a)
                            ->orWhere('b', '=', $b);
                    })
                    ->where(function ($query) use ($c,$d) {
                        $query->where('c', '=', $c)
                            ->orWhere('d', '=', $d);
                    });

            */
            //     //[1, 2, 4]
            //     $waybills = k9DepartureScan::where('SCAN_SITE_CODE', $scan_site_id)->where('PRE_OR_NEXT_STATION_CODE', $next_site_id)->whereDate('SCAN_DATE', $date)->OrderBy('SCAN_DATE', 'DESC')->get();

            //     //[2]
            //     $already_departed = Waybill::where('scan_site_id', $scan_site_id)->where('next_site_id', $next_site_id)->whereDate('created_at', Carbon::today())->get('id');


            //    //[1, 4]
            //     $filtered_waybills = collect($waybills)->filter(function ($waybill, $key) use($already_departed) {
            //         return !($already_departed->contains($waybill->BILL_CODE));
            //     });


            //[2]
            // $already_departed = Waybill::where('scan_site_id', $scan_site_id)
            //     ->where('next_site_id', $next_site_id)
            //     ->whereDate('created_at', Carbon::today())
            //     ->pluck('id');
            //[1, 2, 4]

            //is laravel case sensitive ?
            $query = K9DepartureScan::query();
            if($data['scanner_id'] != null)
            {
                $query->where('SCAN_MAN_CODE', $data['scanner_id']);
            }

            if($data['scan_site_id'] != null)
            {
                $query->where('SCAN_SITE_CODE', $data['scan_site_id']);
            }

            if($data['next_site_id'] != null)
            {
                $query->where('PRE_OR_NEXT_STATION_CODE', $data['next_site_id']);
            }

            if($data['excluded_waybills'] != null)
            {
                $query->whereNotIn('BILL_CODE', $data['excluded_waybills']);
            }

            //Validation unkor ? Can end date be greather than start date ?
            $start_date =Carbon::today()->toDateString();
            if($data['start_date'] != null)
            {
                $start_date = $data['start_date'];
            }


            $end_date = Carbon::today()->toDateString();

            if($data['end_date'] != null)
            {
                $end_date = $data['end_date'];
            }
            //wheereBetween & protect $dtes column could not help ? No time to check why
            $query->whereDate('SCAN_DATE', '>=', $start_date)->whereDate('SCAN_DATE', '<=', $end_date);

            $order_by = 'BILL_CODE';
            $direction = 'ASC';
            if($data['order_by'] != null)
            {
               $order_by = $data['order_by'];
            }

            if($data['direction'] != null)
            {
               $direction = $data['direction'];
            }

            $query->OrderBy($order_by, $direction);



           $number_of_departure_scans = $query->count();

            //Finally Get the query oo
            //$waybills = $query->get();



           // $filtered_waybills = $waybills;
            //  //[1, 4]
            //   $filtered_waybills = collect($waybills)->filter(function ($waybill, $key) use($already_departed) {
            //       return !($already_departed->contains($waybill->BILL_CODE));
            //   });


            return
                [
                'success' => true,
                 'message' => 'Number of Departure Scans Retrieved successfully',
                 'data' => $number_of_departure_scans,
                 'request' => $data

                   ];
        } catch (Exception $ex) {

            return
                ['success' => false,
                'message' => 'could not retrieve number of departure scans',
                 'data' => $data
                ];
        }
    }


    public function getArrivalScans($data)
    {
        return ['Not yet implemented'];
    }

    public function getNumberOfArrivalScans($data)
    {
        return ['Not yet implemented'];
    }

    // public function getDepartedWaybills()
    // {

    //     return true;
    //     $waybills_builder = null;

    //     try {


    //         $scan_site = 2341;
    //         $next_site = 234103;
    //         $scan_date = Carbon::yesterday()->toDateString();
    //         // $scan_date = Carbon::yesterday()->toDateString();
    //         // $scan_site = $scan_site;
    //         // $waybills = DB::connection('K9_server')->select("SELECT
    //         // BILL_CODE, SCAN_DATE,
    //         // SCAN_TYPE_CODE,
    //         // T3.EMPLOYEE_NAME,
    //         // T2.SITE_NAME AS NEXT_SITE_NAME, T1.SITE_NAME AS SCAN_SITE_NAME, EMPLOYEE_NAME
    //         // FROM NRLY.TAB_SCAN_SEND
    //         // JOIN TAB_SITE T1
    //         // ON SCAN_SITE_CODE = T1.SITE_CODE
    //         // INNER JOIN TAB_SITE T2
    //         // ON PRE_OR_NEXT_STATION_CODE = T2.SITE_CODE
    //         // INNER JOIN TAB_EMPLOYEE_VIEW T3
    //         // ON SCAN_MAN_CODE = T3.EMPLOYEE_CODE
    //         // where SCAN_SITE_CODE = $departure_site_id  and DATE(SCAN_DATE) = '$date' ORDER BY BILL_CODE");
    //         // $scan_site = null;
    //         // $next_site = null;
    //         // $waybills = k9DepartureScan::with('employee')->where('SCAN_SITE_CODE', request()->get('scan_site'))->where('PRE_OR_NEXT_STATION_CODE', request()->get('next_site'))->whereDate('SCAN_DATE', $date);

    //         $query = K9DepartureScan::with('employee');
    //         $waybills_builder = $query->where('SCAN_SITE_CODE', $scan_site)->where('PRE_OR_NEXT_STATION_CODE', $next_site)->whereDate('SCAN_DATE', $scan_date);
    //     } catch (Exception $ex) {
    //         //Log Erorr
    //     }


    //     return $waybills_builder;
    // }


    public function synchronizeSiteTable()
    {
        // return true;
        $k9_sites = K9Site::all();
        try {
            DB::beginTransaction();
            foreach ($k9_sites as $k9_site) {


                Site::updateOrCreate(
                    [
                        'id'   =>  $k9_site->SITE_CODE,
                    ],
                    [
                        //id already exist
                        'name' => $k9_site->SITE_NAME,
                        'created_by' => (int)$k9_site->CREATE_MAN_CODE,
                        'created_at' => Carbon::parse($k9_site->CREATE_DATE), //Carbon parse ?
                        'updated_at' => Carbon::now(),
                        'updated_by' => 0, //Auth::id(),
                        'updated_on_k9_at' => Carbon::parse($k9_site->MODIFY_DATE), //Carbon parse ?
                        'updated_on_k9_by' => (int)$k9_site->MODIFIER_CODE,
                        'parent_site_id' => (int)$k9_site->SUPERIOR_SITE_CODE,
                        'is_disabled' => (int)$k9_site->BL_NOT_INPUT, //assumption 1 is disabled. Test case (234401, 234502)ask mr White, which is disabled site column?
                        //like BL_Delete Column in Employee_View Table
                        'site_type_id' => (int)$k9_site->TYPE_CODE,
                        'country_id' => null,
                        'state_id' => null, //Provice. Disctrict , Area, County wahala
                        //I won't allow k9 info to override these settings made only in k9x
                        'can_dispatch_or_acknowledge_manifest' => 1, //Yes by default, Go change it manually
                        'address' => null,
                        'is_a_test_site' => 0, // They should not be able to send to or receive from Test WebSite in Real Life
                        // 'is_deleted' softdeletes in migration
                        'is_a_franchise' => 0 //go and set it manually jor, no column to know if a site is a franchasee now ?
                    ]
                );
            }

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
        }
    }

    /*
        This method 'synchronizes' the Employees table in k9 system with
        the Users table in this sytem.
        it makes sure both tables are in 'the same' state!
        {
          id, => unique, cannot change

        }
        How ?
            insert every employees in k9 into k9x

    */
    public function synchronizeEmployeeTableV2()
    {

        try {

            $k9_users = K9Employee::all();
            $default_password = Hash::make(config('custom.default_password', 123456));
            $saved = null;
            foreach ($k9_users as $k9_user) {

                $user = User::firstOrNew([
                    'id'   =>  (int)$k9_user->EMPLOYEE_CODE,
                ]);

                // If the model is brand new, we'll insert it into our database
                //i.e if a $user does not exist create and insert into db by applying a default password
                if (!$user->exists) {

                    $user->fill([
                        'name' => $k9_user->EMPLOYEE_NAME, // use english name
                        'password' => $default_password,
                        'created_by' => (int)$k9_user->CREATE_MAN, // this results to zero at times Noo create man code column
                        'created_at' => Carbon::parse($k9_user->CREATE_DATE), //Carbon parse ?
                        'updated_at' => Carbon::now(),
                        'updated_by' => 0, //Auth::id(),
                        'updated_on_k9_at' => Carbon::parse($k9_user->MODIFY_DATE), //Carbon parse ?
                        'updated_on_k9_by' => (int)$k9_user->MODIFY_MAN_CODE,
                        'site_id' => (int)$k9_user->OWNER_SITE_CODE,
                        'is_disabled' => (int)$k9_user->BL_DELETE,
                    ]);

                    //You might want to add it to  inserted list
                    $saved =  $user->save();
                }
                // If the user  already exists in the database we can just update our record
                //Only update it if anything of his attributes has been changed since it was inserted into the k9ex DB
                else {

                    $user->fill([
                        'name' => $k9_user->EMPLOYEE_NAME,
                        // 'password' => $default_password,
                        'created_by' => (int)$k9_user->CREATE_MAN, // this results to zero at times Noo create man code column
                        'created_at' => Carbon::parse($k9_user->CREATE_DATE), //Carbon parse ?
                        'updated_on_k9_at' => Carbon::parse($k9_user->MODIFY_DATE), //Carbon parse ?
                        'updated_on_k9_by' => (int)$k9_user->MODIFY_MAN_CODE,
                        'site_id' => (int)$k9_user->OWNER_SITE_CODE,
                        'is_disabled' => (int)$k9_user->BL_DELETE,
                    ]);

                    if ($user->isDirty()) {

                        $user->fill([
                            'updated_at' => Carbon::now(), // this will always make the below dirty  fail / NOT AGAIN
                            'updated_by' => 0, //Auth::id(),
                        ]);

                        //Add to updated list
                        $saved =  $user->save();
                    }

                    // else do nothing
                }
            }

            // DB::commit();
            //should we do something with $saved
            return ['success' => true, 'message' => 'Employee Table Syncronized success fully'];
        } catch (Exception $ex) {
            // DB::rollBack();
            //log error echo $ex->getMessage();
            return ['success' => false, 'message' => 'Failed to syncronize Employee Table\n'. $ex->getMessage()];
        }
    }

    public function synchronizeEmployeeTable()
    {
        $k9_users = K9Employee::all();
        // dd($k9_users->first());
        $default_password = Hash::make(config('custom.default_password', 123456));

        // $k9_user = $k9_users->first();

        // User::create(
        //     [
        //         'id'   =>  (int)$k9_user->EMPLOYEE_CODE,
        //         'name' => $k9_user->EMPLOYEE_NAME,
        //         'password' => $default_password,
        //         'created_by' => (int)$k9_user->CREATE_MAN, // this results to zero at times Noo create man code column
        //         'created_at' => Carbon::parse($k9_user->CREATE_DATE), //Carbon parse ?
        //         'updated_at' => Carbon::now(),
        //         'updated_by' => 0, //Auth::id(),
        //         'updated_on_k9_at' => Carbon::parse($k9_user->MODIFY_DATE), //Carbon parse ?
        //         'updated_on_k9_by' => (int)$k9_user->MODIFY_MAN_CODE,
        //         'site_id' => (int)$k9_user->OWNER_SITE_CODE,
        //         'is_disabled' => (int)$k9_user->BL_DELETE,
        //     ]
        // );
        //     $test = [];
        // $k9_users->chunk(10, function($users) use($default_password) {
        //     foreach ($users as $k9_user) {
        //       $test[] = 1;
        //     }
        // });


        // dd($test);
        try {
            // DB::beginTransaction();
            foreach ($k9_users as $k9_user) {
                // echo $k9_users->count();
                User::updateOrCreate(
                    [
                        'id'   =>  (int)$k9_user->EMPLOYEE_CODE,
                    ],
                    [
                        //id already exist
                        'name' => $k9_user->EMPLOYEE_NAME,
                        'password' => $default_password,
                        'created_by' => (int)$k9_user->CREATE_MAN, // this results to zero at times Noo create man code column
                        'created_at' => Carbon::parse($k9_user->CREATE_DATE), //Carbon parse ?
                        'updated_at' => Carbon::now(),
                        'updated_by' => 0, //Auth::id(),
                        'updated_on_k9_at' => Carbon::parse($k9_user->MODIFY_DATE), //Carbon parse ?
                        'updated_on_k9_by' => (int)$k9_user->MODIFY_MAN_CODE,
                        'site_id' => (int)$k9_user->OWNER_SITE_CODE,
                        'is_disabled' => (int)$k9_user->BL_DELETE,
                    ]
                );
            }

            // DB::commit();

            return ['success' => true, 'message' => 'Employee Table Syncronized success fully'];
        } catch (Exception $ex) {
            // DB::rollBack();
            //log error echo $ex->getMessage();
            return ['success' => false, 'message' => 'Failed to syncronize Employee Table'];
        }
    }
}
