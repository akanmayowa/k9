<?php

namespace App\Services;

use App\Enums\BagStatus;
use App\Role;
use App\Site;
use App\User;
use Exception;
use App\Waybill;
use App\Manifest;
use Carbon\Carbon;
use App\OverdueFlag;
use App\K9ArrivalScan;
use App\ScanTimestamp;
use App\Enums\WaybillStatus;
use App\Enums\ManifestStatus;
use App\Services\SmsServices;
use App\Events\ManifestDispatched;
use Illuminate\Support\Facades\DB;
use App\Events\ManifestAcknowledged;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ManifestException;
use function GuzzleHttp\Psr7\build_query;

use GuzzleHttp\Exception\RequestException;
use App\Events\ManifestPartiallyAcknowledged;
use App\K9CollectionScan;
use App\K9DeliveryScan;
use App\K9DepartureScan;
use App\K9ReturnScan;

class ManifestServices
{
   private  $bag_services = null;
    public function __construct(BagServices $bag_services)
    {
        $this->bag_services = $bag_services;

    }

    function createManifest($create_manifest_data)
    {

        /*
            - has this seal number being used b4 ?
            - should we allow zero waybills
            - is the departue_site valid ?
            - is the next_site valid ?
            - is this user logged in ?
            - should this user be able to create manifest ?
       */
        //   dd($waybills);
        //Transaction should Begin here
        try {


            //Take this to controller
            $user = Auth::user(); // assumming user is logged in
            if ($user === null) {
                throw new ManifestException("Error, Could not dispatch Manifest\n User is not logged in");
            }


            $scan_site = Site::find($user->site_id);
            if ($scan_site === null) {
                throw new ManifestException("Error, Could not dispatch Manifest\n Invalid scan site supplied!");
            }


            $next_site = Site::find($create_manifest_data['next_site_id']);
            if ($next_site === null) {
                throw new ManifestException("Error, Invalid next site supplied!");
            }

            if ($next_site->id === $user->site_id) {
                throw new ManifestException("Error, Could not dispatch Manifest\n You cannot depart manifest to your site");
            }

            // Seal number validation should be next version

            if (Manifest::where('seal_number', $create_manifest_data['seal_number'])->exists()) {
                throw new ManifestException("Seal Number has already been used");
            }

            $bag_id = null;
            if (!empty($create_manifest_data['bag_number'])) {
                $bag_id = $create_manifest_data['bag_number'];
            }




            if (collect($create_manifest_data['waybills'])->isEmpty()) {
                throw new ManifestException("Error, Could not dispatch Manifest\nNo waybill in the bag");
            }


            $waybills = [];

            // dd($create_manifest_data);
            //-------------------TRANSACTION BEGINS-------------------
            DB::beginTransaction();
            //'waybills', 'next_site_id', 'transport_type_id', 'driver_name', 'driver_phonenumber', 'truck_platenumber', 'number_of_bags', 'truck_seal_number', 'groups
            $manifest =  \App\Manifest::create(
                [
                    'scan_site_id' => $scan_site->id,
                    'next_site_id' => $create_manifest_data['next_site_id'],
                    'status' => ManifestStatus::IN_TRANSIT, // LOCKED,
                    'is_flagged' => 0,
                    'acknowledged_by' => null,
                    'acknowledged_at' => null,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),

                    'transport_type_id' => $create_manifest_data['transport_type_id'],
                    'driver_name' => $create_manifest_data['driver_name'],
                    'driver_phonenumber' => $create_manifest_data['driver_phonenumber'],
                    'truck_platenumber' => $create_manifest_data['truck_platenumber'],
                    'seal_number' => $create_manifest_data['seal_number'],
                    'truck_seal_number' => $create_manifest_data['truck_seal_number'],
                    'shipment_type' => $create_manifest_data['shipment_type'],
                    'remark' => $create_manifest_data['manifest_remark'],
                    'bag_id' => $bag_id // newly added
                    //add seal number
                    //deleted_at column for soft delete
                ]
            );



            //   dd($create_manifest_data['waybills']);
            //Get waybills from k9 here using the group
            // dd($create_manifest_data['waybills']);
            foreach ($create_manifest_data['waybills'] as $waybill) {

                /* Validate waybill numbers here
        */

                // if()
                $waybills[] = [
                    'id' => $waybill->BILL_CODE,
                    'scan_site_id' => $manifest->scan_site_id,
                    'next_site_id' => $manifest->next_site_id,
                    'created_by' => $manifest->created_by,
                    'updated_by' => $manifest->updated_by,
                    'status' => WaybillStatus::IN_TRANSIT,
                    'shipment_type' => $manifest->shipment_type,
                    'departure_weight' => $waybill->WEIGHT,
                    'scanner' => $waybill->SCAN_MAN_CODE,
                    'scan_date' => $waybill->SCAN_DATE,
                    //enter optional related properties
                    'weight' => $waybill->BILL_WEIGHT,
                    'goods_type' => $waybill->GOODS_TYPE_CODE,
                    'main_id' => $waybill->MAIN_CODE,
                    'quantity' => $waybill->PIECE_NUMBER, // ?? do not work here
                    'send_company' => $waybill->SEND_MAN_COMPANY
                ];
            }

            // dd($waybills);

            $manifest->waybills()->createMany(
                $waybills
            );

            if($bag_id != null)
            {
                $this->bag_services->dispatch(
                    [
                        'bag_id' => $bag_id,
                        'current_manifest_or_transfer_id' => $manifest->id,
                        'departure_site' => $scan_site,
                        'destination_site' => $next_site,
                        'user' => $user,
                        'updated_at' => now(),
                    ]
                );
            }


            $groups = ScanTimestamp::where('id', $create_manifest_data['groups']->id)->update(['dispatched' => 1, 'updated_at' => Carbon::now()]);

            //---------------------TRANSACTION ENDS-------------
            DB::commit();

            $context = compact('manifest', 'scan_site', 'next_site', 'waybills', 'user');

            ManifestDispatched::dispatch($context);

            return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest Dispatched Successfully'];
        } catch (\PDOException $ex) {
            //should I handle PDOException error or Exception ?
            DB::rollBack();

            return ['success' => false, 'manifest' => null, 'message' => "Failed to dispatched manifest! " . $ex->getMessage()];
        }
    }


    public function getPossibleNextSitesFor($user_site)
    {
        try {

            //TODO: This kind of Validation should also be place in the store method!
            /*
                   sites not in nigeria?
                   Lagos finance Center -- can't transact
            */

            $sites_to_exclude = [$user_site->id];
            $site = Site::find($user_site->id);
            if($site == null)
            {
                throw new ManifestException("Parent site not set");
            }
            $query = Site::query()->whereNotIn('id', [$sites_to_exclude])->where('can_dispatch_or_acknowledge_manifest', '!=', 0);


            if ($user_site->is_a_test_site) {
                if (!$user_site->isDC()) {
                    //Regular Site can manifest to only dc
                    //with('state', 'Country') ?
                    $query = $query->where('is_a_test_site', 1)->where('site_type_id', '=', 600003);
                } else {

                    // $query = $query->where('is_a_test_site', 1);
                    $query = $query->where('is_a_test_site', '=', 1)->where('parent_site_id', $site->id)->orWhere('site_type_id',600003);
                }
            } else {
                if (!$user_site->isDC()) {
                    //Regular Site can manifest to only dc
                    //with('state', 'Country') ?
                    $query = $query->where('is_a_test_site', '!=', 1)->where('site_type_id', '=', 600003);
                } else {
                    // DCs can manifest to every other sites
                    $query =  $query->where('is_a_test_site', '!=', 1);
                }
            }

            return $query->pluck('name', 'id');
        } catch (Exception $ex) {

            dd("Oops, Something Just Happened! \n" . $ex->getMessage());
        }
    }

    public function getIncomingManifest()
    {
        $user = Auth::user(); // assumming user is logged in
        //asc or dsc ?
        return Manifest::orderBy('created_at', 'asc')->where(['next_site_id' => $user->site_id, 'status' => ManifestStatus::IN_TRANSIT])->get();
    }

    public function getManifest($manifest_id)
    {
        //:id,weight,arrival_weight,departure_weight,status,created_by_user,acknowledged_by_user
        $manifest = Manifest::with(['waybills', 'scan_site:id,name', 'next_site:id,name', 'created_by_user:id,name'])->where('id', (int)$manifest_id)->first();
        return $manifest;
    }

    public function getDispatchedManifest()
    {
        //what about null ?
        $user = Auth::user(); //asumming user is logged in
        return Manifest::with(['waybills', 'scan_site', 'next_site', 'created_by_user'])->where('scan_site_id', $user->site_id)->orderBy('created_at', 'desc')->get();
    }

    public function getAcknowledgedManifest()
    {
        $user = Auth::user(); //asumming user is logged in
        return Manifest::with(['scan_site', 'next_site', 'created_by_user'])->where(
            [
                ['next_site_id', '=', $user->site_id],
                ['status', '=', ManifestStatus::ACKNOWLEDGED]
            ]
        )->orWhere([
            ['next_site_id', '=', $user->site_id],
            ['status', '=', ManifestStatus::PARTIALLY_RECEIVED]
        ])->orderBy('created_at', 'desc')->get();
    }

    public function getAllManifest()
    {
        try {
            //Only for general managers
            //abs($manifest->created_at->diffInHours($now, true)-$escalation_interval)
            $now = \Carbon\Carbon::now();
            $escalation_interval = config('custom.escalation_interval_in_hours');
            $query = "status ASC, id ASC"; // Ordering columns
            return Manifest::with(['scan_site', 'next_site', 'acknowledged_by_user'])->orderByRaw($query)->get();
        } catch (Exception $ex) {
            //Do something here
        }
    }


    public function getManifests($filters)
    {
        try {
            //Only for general managers
            //abs($manifest->created_at->diffInHours($now, true)-$escalation_interval)
            $now = \Carbon\Carbon::now();
            $escalation_interval = config('custom.escalation_interval_in_hours');
            $query = "created_at desc"; // "status ASC, id ASC"; // Ordering columns
            //withCount(['acknowledged_waybills','pending_waybills','dispatched_waybills','waybills'])
            $build_query = Manifest::with(['waybills:id,manifest_id,status', 'created_by_user:id,name', 'scan_site:id,name', 'next_site:id,name', 'acknowledged_by_user:id,name']);



            $status = ($filters['status']);
            $scan_site_id = ($filters['scan_site_id']);
            $next_site_id = ($filters['next_site_id']);
            if ($status !== -1) {
                $build_query->where('status', $status);
            }

            if ($scan_site_id !== 0) // is this even correct what is zero ?
            {
                $build_query->where('scan_site_id', $scan_site_id);
            }

            if ($next_site_id !== 0) // is this even correct what is zero ?
            {
                $build_query->where('next_site_id', $next_site_id);
            }

            // if($filters['start_date'] != null)
            // {
            //     $build_query->whereDate('created_at', '>=', $filters['start_date']);

            // }

            // // Todo , Uncomment the end date

            // if($filters['end_date'] != null)
            // {
            //     $build_query->whereDate('created_at', '<=', $filters['end_date']);
            // }
            // //wheereBetween & protect $dtes column could not help ? No time to check why

            $user_time_zone = 'Africa/Lagos'; // temporal
            if ($filters['start_date'] != null) {
                // ->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) = '$today'");
                // $build_query->whereDate('created_at', '>=', $filters['start_date']); b4
                $build_query->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) >= '{$filters['start_date']}'");
            }

            //Todo , Uncomment the end date

            if ($filters['end_date'] != null) {
                // $build_query->whereDate('created_at', '<=', $filters['end_date']);
                $build_query->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) <= '{$filters['end_date']}'");
            }


            return $build_query->orderByRaw($query);
        } catch (Exception $ex) {
            //Do something here
        }
    }

    public function getDispatchedManifests($filters)
    {
        try {
            //Only for general managers
            //abs($manifest->created_at->diffInHours($now, true)-$escalation_interval)
            // $now = \Carbon\Carbon::now();
            // $escalation_interval = config('custom.escalation_interval_in_hours');
            $query = "created_at desc"; // "status ASC, id ASC"; // Ordering columns
            // $build_query = Manifest::with(['waybills:id,manifest_id,status', 'created_by_user:id,name', 'scan_site:id,name', 'next_site:id,name', 'acknowledged_by_user:id,name'])->where('scan_site_id', $filters['user']->site->id);
            $build_query = Manifest::withCount('waybills')->with(['created_by_user:id,name', 'scan_site:id,name', 'next_site:id,name', 'acknowledged_by_user:id,name'])->where('scan_site_id', $filters['user']->site->id);

            // $build_query = Manifest::with(['acknowledged_waybills', 'pending_waybills', 'dispatched_waybills', 'waybills', 'scan_site', 'next_site', 'acknowledged_by_user'])->where('scan_site_id', $filters['user']->site->id);
            // $status = ($filters['status']);
            // $scan_site_id = ($filters['scan_site_id']);
            $next_site_id = ($filters['next_site_id']);
            // if($status !== 0)
            // {
            //     $build_query->where('status', $status);
            // }

            // if($scan_site_id !== 0) // is this even correct what is zero ?
            // {
            //     $build_query->where('scan_site_id', $scan_site_id);
            // }
            if ($next_site_id !== 0) // is this even correct what is zero ?
            {
                $build_query->where('next_site_id', $next_site_id);
            }

            if ($filters['created_by'] !== 0) // is this even correct what is zero ?
            {
                $build_query->where('created_by', $filters['created_by']);
            }


            $user_time_zone = 'Africa/Lagos'; // temporal
            if ($filters['start_date'] != null) {
                // ->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) = '$today'");
                // $build_query->whereDate('created_at', '>=', $filters['start_date']); b4
                $build_query->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) >= '{$filters['start_date']}'");
            }

            //Todo , Uncomment the end date

            if ($filters['end_date'] != null) {
                // $build_query->whereDate('created_at', '<=', $filters['end_date']);
                $build_query->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) <= '{$filters['end_date']}'");
            }
            //wheereBetween & protect $dtes column could not help ? No time to check why


            return $build_query->orderByRaw($query);
        } catch (Exception $ex) {
            //Do something here
        }
    }

    public function getAcknowledgedManifests($filters)
    {

        try {
            $query = "created_at desc"; // "status ASC, id ASC"; // Ordering columns

            $build_query = Manifest::with(['waybills:id,status,manifest_id', 'created_by_user:id,name', 'next_site:id,name', 'scan_site:id,name', 'acknowledged_by_user:id,name'])
                ->where('next_site_id', '=', $filters['user']->site_id)->WhereIn('status', [ManifestStatus::PARTIALLY_RECEIVED, ManifestStatus::ACKNOWLEDGED]);
            //Manifest::with(['acknowledged_waybills','pending_waybills','dispatched_waybills','scan_site', 'next_site', 'acknowledged_by_user'])
            // $status = ($filters['status']);
            $scan_site_id = ($filters['scan_site_id']);
            // $next_site_id = ($filters['next_site_id']);
            // if($status !== 0)
            // {
            //     $build_query->where('status', $status);
            // }

            if ($scan_site_id !== 0) // is this even correct what is zero ?
            {
                $build_query->where('scan_site_id', $scan_site_id);
            }

            // if($next_site_id !== 0) // is this even correct what is zero ?
            // {
            //     $build_query->where('next_site_id', $next_site_id);
            // }

            // if($filters['start_date'] != null)
            // {
            //     $build_query->whereDate('acknowledged_at', '>=', $filters['start_date']);

            // }

            //Todo , Uncomment the end date

            // if($filters['end_date'] != null)
            // {
            //     $build_query->whereDate('acknowledged_at', '<=', $filters['end_date']);
            // }
            //wheereBetween & protect $dtes column could not help ? No time to check why


            return $build_query->orderByRaw($query);
        } catch (Exception $ex) {
            //Do something here
        }
    }

    public function getPartiallyAcknowledgedManifests($filters)
    {
        try {
            $build_query = Manifest::with(['waybills:id,status,manifest_id', 'created_by_user:id,name', 'next_site:id,name', 'scan_site:id,name', 'acknowledged_by_user:id,name'])
                ->where('next_site_id', '=', $filters['user']->site_id)->WhereIn('status', [ManifestStatus::PARTIALLY_RECEIVED]);
            $scan_site_id = ($filters['scan_site_id']);
            if ($scan_site_id !== 0) // is this even correct what is zero ?
            {
                $build_query->where('scan_site_id', $scan_site_id);
            }
            return $build_query;
        } catch (Exception $ex) {
            //Do something here
        }
    }

    public function getIncomingManifests($filters)
    {
        try {
            $query = "created_at desc"; // "status ASC, id ASC"; // Ordering columns

            $build_query =
             Manifest::withCount('waybills')
                ->with(['created_by_user:id,name', 'scan_site:id,name', 'acknowledged_by_user:id,name'])
                ->where('next_site_id', $filters['user']->site->id)
                ->where('status', ManifestStatus::IN_TRANSIT);

            // Manifest::with(['created_by_user:id,name', 'scan_site:id,name', 'acknowledged_by_user:id,name'])
            // ->where('next_site_id', $filters['user']->site->id)
            // ->where('status', ManifestStatus::IN_TRANSIT);
            // $status = ($filters['status']);
            $scan_site_id = ($filters['scan_site_id']);
            // $next_site_id = ($filters['next_site_id']);
            // if($status !== 0)
            // {
            //     $build_query->where('status', $status);
            // }

            // $build_query->where('status', $status);



            if ($scan_site_id !== 0) // is this even correct what is zero ?
            {
                $build_query->where('scan_site_id', $scan_site_id);
            }


            // if($next_site_id !== 0) // is this even correct what is zero ?
            // {
            //     $build_query->where('next_site_id', $next_site_id);
            // }

            // if($filters['start_date'] != null)
            // {
            //     $build_query->whereDate('created_at', '>=', $filters['start_date']);

            // }

            // //Todo , Uncomment the end date

            // if($filters['end_date'] != null)
            // {
            //     $build_query->whereDate('created_at', '<=', $filters['end_date']);
            // }


            // $user_time_zone = 'Africa/Lagos'; // temporal
            // if($filters['start_date'] != null)
            // {
            //     // ->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) = '$today'");
            //     // $build_query->whereDate('created_at', '>=', $filters['start_date']); b4
            //     $build_query->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) >= '{$filters['start_date']}'");

            // }

            //Todo , Uncomment the end date

            // if($filters['end_date'] != null)
            // {
            //     // $build_query->whereDate('created_at', '<=', $filters['end_date']);
            //     $build_query->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) <= '{$filters['end_date']}'");
            // }

            //wheereBetween & protect $dtes column could not help ? No time to check why


            return $build_query; //->orderByRaw($query);
        } catch (Exception $ex) {
            //Do something here
        }
    }


    public function getAllWaybills()
    {
        return Waybill::with(['manifest'])->get();
    }

    public function flagOverdue($data)
    {
        try {
            $manifest = Manifest::find($data['manifest_id']);
            if ($manifest === null) {
                throw new ManifestException("Manifest with ID {$data['manifest_id']} not found");
            }

            if ($manifest->status !== ManifestStatus::IN_TRANSIT) {
                throw new ManifestException("Sorry, you can only flag a manifest in transit");
            }

            $manifest_update = Manifest::where('id', $data['manifest_id'])->update(['flagged' => 1, 'updated_at' => now(),]);
            //TODO flag the waybills too

            // $flag_result = OverdueFlag::create([
            //     'manifest_id' => $manifest->id,
            //     'created_by' => Auth::id(),
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ]);
            return $manifest_update;
        } catch (Exception $ex) {
        }
    }

    public function cancelManifest($data)
    {
        try {

            //Should a regular user be abe to cancel manifest ?



            $manifest = Manifest::find($data['manifest_id']);
            if ($manifest === null) {
                throw new ManifestException("Manifest with ID {$data['manifest_id']} not found");
            }

            if ($manifest->status !== ManifestStatus::IN_TRANSIT) {
                throw new ManifestException("Sorry, you can only cancel a manifest in transit");
            }

            //or user is an admin ?/?
            if ($manifest->scan_site_id != $data['user']->site_id) {
                throw new ManifestException("operation failed!, only a user in the dispatched site can cancel this manifest ");
            }

            //You can only only in 24 hours

            DB::beginTransaction();
            //Remove the seal number
            $manifest_update =  Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::CANCELLED, 'updated_by' => $data['user']->id, 'updated_at' => now()]);
            $manifest_update =  Manifest::where('id', $data['manifest_id'])->delete();

            $waybills_update =  Waybill::whereIn('id', $manifest->waybills->pluck('id'))->where('manifest_id', $data['manifest_id'])->update(['status' => ManifestStatus::CANCELLED, 'updated_by' => $data['user']->id, 'updated_at' => now()]);
            Waybill::whereIn('id', $manifest->waybills->pluck('id'))->where('manifest_id', $data['manifest_id'])->delete();

            //Release the bag attached to it

            DB::commit();

            return $manifest_update && $waybills_update;
        } catch (Exception $ex) {
            DB::rollBack();

            return $ex->getMessage();
            //Do something
        }
    }


    // public function acknowledgeAllManifestWaybills()
    // {
    //     //Get all waybills sent to this site
    // }

    public function acknowledgeManifest($data)
    {
        try {

            /* 5th July 2021
                You might also validate for seal Number on the acknowledgement side
                No need. they cannot even acknowlege the wrong bag

            */



            // $user = Auth::user(); // assumming user is logged in
            // if ($user == null) {
            //     throw new ManifestException("User is not logged in");
            // }


            $user = $data['user'];


            //change DB column to string , also with other Ids from ak9
            $manifest_id = $data['manifest_id'];
            $waybills =  $data['waybills'];
            // dd($waybills);
            $manifest = Manifest::with('waybills', 'scan_site', 'next_site')->find($manifest_id);

            if ($manifest === null) {
                throw new ManifestException("Oops, could not find manifest");
            }

            // dd($manifest->status, ManifestStatus::PARTIALLY_RECEIVED,  ManifestStatus::IN_TRANSIT, ($manifest->status != ManifestStatus::IN_TRANSIT && $manifest->status != ManifestStatus::PARTIALLY_RECEIVED));
            //You cannot acknwoledge a Deleted / Cancelled / Partially_Acknwolededged / Acknowledge
            if ($manifest->status != ManifestStatus::IN_TRANSIT && $manifest->status != ManifestStatus::PARTIALLY_RECEIVED) {
                throw new ManifestException("You can not acknowledge  a manifest with " . strtoupper(ManifestStatus::STATUS_TEXT[$manifest->status]) . " status");
            }

            // if (!(\Carbon\Carbon::now()->isSameday($manifest->created_at))) {
            //     throw new ManifestException("Oops, Acknowlegment time has elasped!, you cannot acknwoleged this manifest again");
            // }


            // dd($manifest->next_site_id, $user->site_id);
            //A Lagos DC user cannot acknowledge
            //An administrator cannot acknowledge
            //is this user accepting the manifest in the same site as the person who created the manifest ?
            if ($manifest->next_site_id != $user->site_id) {
                throw new ManifestException("You cannot not acknowledge this manifest because it was not dispatched to your site");
            }

            if (collect($waybills)->isEmpty()) {
                throw new ManifestException("You cannot acknowledge a manifest without specifying some waybills");
            }

            $waybills_to_store = $waybills;

            // collect($waybills)->whereIn('id', $waybills)->pluck('id');
            // dd($waybills_to_store);
            // foreach ($waybills as $waybill) {

            //     //Waybill validation here
            //     $waybills_to_store[] = [
            //         //should I compare it to it here ? Alibaba Shipment
            //         'id' => $waybill,
            //         'scan_site_id' => $manifest->scan_site_id,
            //         'next_site_id' => $manifest->next_site_id,
            //         'created_by' => $manifest->created_by,
            //         'updated_by' => $manifest->updated_by,
            //         'status' => WaybillStatus::ACKNOWLEDGED
            //     ];
            // }

            //Relax the checks, Most of this user cannot not hack Jack
            // $manifest_waybills = $manifest->waybills->pluck('id');
            // $incorrect_waybills =  collect($waybills_to_store->pluck('id'))->diff($manifest_waybills);
            // $correct_waybills = collect($waybills_to_store->pluck('id'))->intersect($manifest_waybills);


            // if ($incorrect_waybills->isNotEmpty()) {
            //     throw new ManifestException("Waybill Number [{$incorrect_waybills->implode(',')}] do not belong to manifest ID {$manifest->id} \n ");
            // }


            // dd($manifest_waybills, $waybills_to_store->pluck('id'), $incorrect_waybills, $correct_waybills);

            DB::beginTransaction();
            // dd($manifest);

            //Does merge remove duplicate ?
            $total_acknowledged_waybill_count = collect($waybills_to_store)->count();
            //$manifest->waybills->where('status', WaybillStatus::ACKNOWLEDGED)->pluck('id')->merge($waybills_to_store)->count();
            Waybill::whereIn('id', $waybills_to_store)
                ->where('manifest_id', $manifest_id)
                ->where('status', WaybillStatus::IN_TRANSIT) // or status equals Pre acknwoleged orhwere ManifestStatus::PARTIALLY_RECEIVED
                ->update([
                    'status' => WaybillStatus::ACKNOWLEDGED,
                    'updated_at' => now(),
                    'updated_by' => $user->id,
                    'acknwoledged_by' => $user->id,
                    'acknwoledged_at' => now()
                ]);
            // dd($total_acknowledged_waybill_count);
            //  Waybill::where('status', WaybillStatus::ACKNOWLEDGED)->pluck('id');
            // if ($correct_waybills->count() < count($manifest_waybills)) {
            // dd($total_acknowledged_waybill_count < count($manifest->waybills));

            //Add acknwoledgement date acknowledged_at
            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {

                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::PARTIALLY_RECEIVED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
                //Waybills which are not acknowledged to pending
            } else {

                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::ACKNOWLEDGED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
            }

            //Release the bag -- to make it available to a site
            //ToDo

            if($manifest->bag_id !== null)
            {
                $this->bag_services->acknowledgeDispatch(['bag_id' => $manifest->bag_id , 'user' => $user]);
            }

            DB::commit();


            $context = compact('manifest', 'waybills', 'user');

            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {

                //Announce it
                ManifestPartiallyAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest PARTIALLY Acknowleged Successfully'];
            } else {
                //Annouce it
                ManifestAcknowledged::dispatch($context);

                //you might want to replace manifest with manifest_id
                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest Acknowleged Successfully'];
            }
        } catch (Exception $ex) {

            DB::rollBack();

            //Todo rethrow the Manifest Exception ?
            return ['success' => false, 'manifest' => null, 'message' => $ex->getMessage()];
        }
    }



    public function getManifestWaybills($data)
    {
        //Validation ?

        $query = Waybill::query();
        $query->where('manifest_id', $data['manifest_id']);
        if ($data['status'] == "pending") {
            $query->where('status', '!=', 1);
        } else if ($data['status'] == "acknowledged") {
            $query->where('status', 1);
        }

        $waybills = $query->get(['id', 'status']);

        return $waybills;
    }

    public function acknowledgeTheseManifests($data)
    {
        //Unique manifest ids?
        $result = [];
        foreach ($data as $manifest_data) {
            $result[$manifest_data['manifest_id']] = [$this->acknowledgeAnyManifest($manifest_data)];
            //write results to a file and reload the Ui every seconds
        }

        return $result;
    }

    public function acknowledgeTheseManifestsV2($data)
    {
        //Unique manifest ids?
        $result = [];
        foreach ($data['manifest_ids'] as $manifest_id) {
            $result[$manifest_id] = [$this->acknowledgeAnyManifest(['user' => $data['user'], 'manifest_id' => $manifest_id])];
            //write results to a file and reload the Ui every seconds
        }

        return $result;
    }



    public function acknowledgeAnyManifest($data)
    {
        //This method requires access to interent
        try {
            $user = $data['user'];
            $manifest = Manifest::with('waybills')->where('id', (int)$data['manifest_id'])->first();

            if ($manifest === null) {
                throw new ManifestException("Oops, could not find manifest");
            }

            //this check can be skipped by only selecting manifests that have this quality
            if ($manifest->status != ManifestStatus::IN_TRANSIT && $manifest->status != ManifestStatus::PARTIALLY_RECEIVED) {
                throw new ManifestException("You can not acknowledge  a manifest with " . strtoupper(ManifestStatus::STATUS_TEXT[$manifest->status]) . " status");
            }

            //A Lagos DC user cannot acknowledge //An administrator cannot acknowledge
            //the comment below is incorrect lol
            //is this user accepting the manifest in the same site as the person who created the manifest ?
            if ($manifest->next_site_id != $user->site_id) {
                throw new ManifestException("You cannot not acknowledge this manifest because it was not dispatched to your site");
            }

            $arrived_manifest_waybills_on_k9 = K9ArrivalScan::where('SCAN_SITE_CODE', $manifest->next_site_id)
                ->where('PRE_OR_NEXT_STATION_CODE', $manifest->scan_site_id)
                ->whereDate('SCAN_DATE', '>=', $manifest->created_at)
                ->whereIn('BILL_CODE', $manifest->waybills->where('status', '!=',  WaybillStatus::ACKNOWLEDGED)->pluck('id'))
                ->pluck('BILL_CODE'); // get the date is was arrived too, for dispatch get the dat it was scanned too this can be used to know when these scans where done on k9


            $waybills = $arrived_manifest_waybills_on_k9;

            if (collect($waybills)->isEmpty()) {
                throw new ManifestException("Oops, No  waybills to acknowledge. Kindly arrive them on k9 first");
            }

            $waybills_to_store = $waybills; // collect($waybills)->whereIn('id', $waybills)->pluck('id');

            DB::beginTransaction();

            //Quickly make this amends of adding status contraint on the original method
            $total_acknowledged_waybill_count = $manifest->waybills->where('status', WaybillStatus::ACKNOWLEDGED)->pluck('id')->merge($waybills_to_store)->count();

            Waybill::whereIn('id', $waybills_to_store)
                ->where('manifest_id', $data['manifest_id'])
                ->where('status', WaybillStatus::IN_TRANSIT) // or status equals Pre acknwoleged orhwere ManifestStatus::PARTIALLY_RECEIVED
                ->update([
                    'status' => WaybillStatus::ACKNOWLEDGED,
                    'updated_at' => now(),
                    'updated_by' => $user->id,
                    'acknwoledged_by' => $user->id,
                    'acknwoledged_at' => now()
                ]);


            //Add acknwoledgement date acknowledged_at
            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {
                //I can even use the $manifest variable up there and use save()
                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::PARTIALLY_RECEIVED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
                //Waybills which are not acknowledged to pending
            } else {

                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::ACKNOWLEDGED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
            }
            DB::commit();


            $context = compact('manifest', 'waybills', 'user');

            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {

                //Announce it
                ManifestPartiallyAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest PARTIALLY Acknowleged Successfully'];
            } else {
                //Annouce it
                ManifestAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest Acknowleged Successfully'];
            }
        } catch (Exception $ex) {
            DB::rollBack();

            //Todo rethrow the Manifest Exception ?
            return ['success' => false, 'manifest' => null, 'message' => $ex->getMessage()];
        }
    }

    public function Resolve_Manifest($data)
    {

        try {
            $user = $data['user'];
            $manifest = Manifest::with(['waybills:id,status,manifest_id', 'next_site:id,site_type_id,name'])->where('id', (int)$data['manifest_id'])->first();
            if ($manifest === null) {
                throw new ManifestException("Could not resolve, Manifest not found");
            }

            if ($manifest->next_site->isDC()) {
                throw new ManifestException('Could not resolve, This feature does not work when Next site is a DC');
            }

            if ($manifest->status != ManifestStatus::IN_TRANSIT && $manifest->status != ManifestStatus::PARTIALLY_RECEIVED) {
                throw new ManifestException("You can not resolve  a manifest with " . strtoupper(ManifestStatus::STATUS_TEXT[$manifest->status]) . " status");
            }

            if ($manifest->next_site_id != $user->site_id) {
                throw new ManifestException("You cannot not resolve this manifest because it was not dispatched to your site");
            }


            $waybills_to_check = $manifest->waybills->where('status', 0)->pluck('id');
            // $waybills_already_arrived =  $manifest->waybills->where('status', '!=', 0)->pluck('id');

            if (collect($waybills_to_check)->isEmpty()) {
                throw new ManifestException("Cold not resolve, No waybills to acknowledge");
            }

            // echo "Checking arival table....\n";
            $arrived_manifest_waybills_on_k9 = K9ArrivalScan::where('SCAN_SITE_CODE', $manifest->next_site_id)
                //->where('PRE_OR_NEXT_STATION_CODE', $manifest->scan_site_id) regular site only
                ->whereDate('SCAN_DATE', '>=', $manifest->created_at)
                ->whereIn('BILL_CODE', $waybills_to_check)
                ->pluck('BILL_CODE');

            // echo "Arrived waybills\n";
            // $arrived_manifest_waybills_on_k9->dump();

            DB::beginTransaction();

            //Quickly make this amends of adding status contraint on the original method
            $total_acknowledged_waybill_count = $manifest->waybills->where('status', WaybillStatus::ACKNOWLEDGED)->pluck('id')->merge($arrived_manifest_waybills_on_k9)->count();

            Waybill::whereIn('id', $arrived_manifest_waybills_on_k9)
                ->where('manifest_id', $data['manifest_id'])
                ->where('status', WaybillStatus::IN_TRANSIT) // or status equals Pre acknwoleged orhwere ManifestStatus::PARTIALLY_RECEIVED
                ->update([
                    'status' => WaybillStatus::ACKNOWLEDGED,
                    'updated_at' => now(),
                    'updated_by' => $user->id,
                    'acknwoledged_by' => $user->id,
                    'acknwoledged_at' => now(),
                    'acknownledgement_remark' => 'arrived on k9 with wrong Last site'
                ]);


            //Add acknwoledgement date acknowledged_at
            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {
                //I can even use the $manifest variable up there and use save()
                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::PARTIALLY_RECEIVED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
                //Waybills which are not acknowledged to pending
            } else {

                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::ACKNOWLEDGED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
            }
            DB::commit();


            $context = compact('manifest', 'waybills', 'user');

            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {

                //Announce it
                ManifestPartiallyAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest PARTIALLY Acknowleged Successfully'];
            } else {
                //Annouce it
                ManifestAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest Acknowleged Successfully'];
            }
        } catch (Exception $ex) {
            DB::rollBack();

            //Todo rethrow the Manifest Exception ?
            return ['success' => false, 'manifest' => null, 'message' => $ex->getMessage()];
        }
    }



    public function Resolve_Manifest_withReturnScan($data)
    {

        try {
            $user = $data['user'];
            $manifest = Manifest::with(['waybills:id,status,manifest_id', 'next_site:id,site_type_id,name'])->where('id', (int)$data['manifest_id'])->first();
            if ($manifest === null) {
                throw new ManifestException("Could not resolve, Manifest not found");
            }

            if ($manifest->next_site->isDC()) {
                throw new ManifestException('Could not resolve, This feature does not work when Next site is a DC');
            }

            if ($manifest->status != ManifestStatus::IN_TRANSIT && $manifest->status != ManifestStatus::PARTIALLY_RECEIVED) {
                throw new ManifestException("You can not resolve  a manifest with " . strtoupper(ManifestStatus::STATUS_TEXT[$manifest->status]) . " status");
            }

            if ($manifest->next_site_id != $user->site_id) {
                throw new ManifestException("You cannot not resolve this manifest because it was not dispatched to your site");
            }


            $waybills_to_check = $manifest->waybills->where('status', 0)->pluck('id');
            // $waybills_already_arrived =  $manifest->waybills->where('status', '!=', 0)->pluck('id');

            if (collect($waybills_to_check)->isEmpty()) {
                throw new ManifestException("Cold not resolve, No waybills to acknowledge");
            }

            // echo "Checking arival table....\n";
            $arrived_manifest_waybills_on_k9 = K9ReturnScan::where('REGISTER_SITE_CODE', $manifest->next_site_id)
                //->where('PRE_OR_NEXT_STATION_CODE', $manifest->scan_site_id) regular site only
                ->whereDate('REGISTER_DATE', '>=', $manifest->created_at)
                ->whereIn('BILL_CODE', $waybills_to_check)
                ->pluck('BILL_CODE');

            // echo "Arrived waybills\n";
            // $arrived_manifest_waybills_on_k9->dump();

            DB::beginTransaction();

            //Quickly make this amends of adding status contraint on the original method
            $total_acknowledged_waybill_count = $manifest->waybills->where('status', WaybillStatus::ACKNOWLEDGED)->pluck('id')->merge($arrived_manifest_waybills_on_k9)->count();

            Waybill::whereIn('id', $arrived_manifest_waybills_on_k9)
                ->where('manifest_id', $data['manifest_id'])
                ->where('status', WaybillStatus::IN_TRANSIT) // or status equals Pre acknwoleged orhwere ManifestStatus::PARTIALLY_RECEIVED
                ->update([
                    'status' => WaybillStatus::ACKNOWLEDGED,
                    'updated_at' => now(),
                    'updated_by' => $user->id,
                    'acknwoledged_by' => $user->id,
                    'acknwoledged_at' => now(),
                    'acknownledgement_remark' => 'arrived on k9 with wrong Last site'
                ]);


            //Add acknwoledgement date acknowledged_at
            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {
                //I can even use the $manifest variable up there and use save()
                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::PARTIALLY_RECEIVED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
                //Waybills which are not acknowledged to pending
            } else {

                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::ACKNOWLEDGED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
            }
            DB::commit();


            $context = compact('manifest', 'waybills', 'user');

            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {

                //Announce it
                ManifestPartiallyAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest PARTIALLY Acknowleged Successfully'];
            } else {
                //Annouce it
                ManifestAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest Acknowleged Successfully'];
            }
        } catch (Exception $ex) {
            DB::rollBack();

            //Todo rethrow the Manifest Exception ?
            return ['success' => false, 'manifest' => null, 'message' => $ex->getMessage()];
        }
    }


    //T3 Don't do delivery scan
    //be careful as some random site can do collection
    //is next site == collection site ?

    public function Resolve_Manifest_withCollectionScan($data)
    {

        try {
            $user = $data['user'];
            $manifest = Manifest::with(['waybills:id,status,manifest_id', 'next_site:id,site_type_id,name'])->where('id', (int)$data['manifest_id'])->first();
            if ($manifest === null) {
                throw new ManifestException("Could not resolve, Manifest not found");
            }

            if ($manifest->next_site->isDC()) {
                throw new ManifestException('Could not resolve, This feature does not work when Next site is a DC');
            }

            if ($manifest->status != ManifestStatus::IN_TRANSIT && $manifest->status != ManifestStatus::PARTIALLY_RECEIVED) {
                throw new ManifestException("You can not resolve  a manifest with " . strtoupper(ManifestStatus::STATUS_TEXT[$manifest->status]) . " status");
            }

            if ($manifest->next_site_id != $user->site_id) {
                throw new ManifestException("You cannot not resolve this manifest because it was not dispatched to your site");
            }


            $waybills_to_check = $manifest->waybills->where('status', 0)->pluck('id');
            // $waybills_already_arrived =  $manifest->waybills->where('status', '!=', 0)->pluck('id');

            if (collect($waybills_to_check)->isEmpty()) {
                throw new ManifestException("Cold not resolve, No waybills to acknowledge");
            }

            // echo "Checking arival table....\n";
            $collected_manifest_waybills_on_k9 = K9CollectionScan::where('RECORD_SITE_CODE', $manifest->next_site_id)
                //->where('PRE_OR_NEXT_STATION_CODE', $manifest->scan_site_id) regular site only
                ->whereDate('SIGN_DATE', '>=', $manifest->created_at)
                ->whereIn('BILL_CODE', $waybills_to_check)
                ->pluck('BILL_CODE');

            // echo "Arrived waybills\n";
            // $arrived_manifest_waybills_on_k9->dump();

            DB::beginTransaction();

            //Quickly make this amends of adding status contraint on the original method
            $total_acknowledged_waybill_count = $manifest->waybills->where('status', WaybillStatus::ACKNOWLEDGED)->pluck('id')->merge($collected_manifest_waybills_on_k9)->count();

            Waybill::whereIn('id', $collected_manifest_waybills_on_k9)
                ->where('manifest_id', $data['manifest_id'])
                ->where('status', WaybillStatus::IN_TRANSIT) // or status equals Pre acknwoleged orhwere ManifestStatus::PARTIALLY_RECEIVED
                ->update([
                    'status' => WaybillStatus::ACKNOWLEDGED,
                    'updated_at' => now(),
                    'updated_by' => $user->id,
                    'acknwoledged_by' => $user->id,
                    'acknwoledged_at' => now(),
                    'acknownledgement_remark' => 'I saw this waybill in their collection list '
                ]);


            //Add acknwoledgement date acknowledged_at
            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {
                //I can even use the $manifest variable up there and use save()
                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::PARTIALLY_RECEIVED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
                //Waybills which are not acknowledged to pending
            } else {

                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::ACKNOWLEDGED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
            }
            DB::commit();


            $context = compact('manifest', 'waybills', 'user');

            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {

                //Announce it
                ManifestPartiallyAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest PARTIALLY Acknowleged Successfully'];
            } else {
                //Annouce it
                ManifestAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest Acknowleged Successfully'];
            }
        } catch (Exception $ex) {
            DB::rollBack();

            //Todo rethrow the Manifest Exception ?
            return ['success' => false, 'manifest' => null, 'message' => $ex->getMessage()];
        }
    }



    public function Resolve_Manifest_withDepartureScan($data)
    {

        try {
            $user = $data['user'];
            $manifest = Manifest::with(['waybills:id,status,manifest_id', 'next_site:id,site_type_id,name'])->where('id', (int)$data['manifest_id'])->first();
            if ($manifest === null) {
                throw new ManifestException("Could not resolve, Manifest not found");
            }

            // if ($manifest->next_site->isDC()) {
            //     throw new ManifestException('Could not resolve, This feature does not work when Next site is a DC');
            // }

            if ($manifest->status != ManifestStatus::IN_TRANSIT && $manifest->status != ManifestStatus::PARTIALLY_RECEIVED) {
                throw new ManifestException("You can not resolve  a manifest with " . strtoupper(ManifestStatus::STATUS_TEXT[$manifest->status]) . " status");
            }

            if ($manifest->next_site_id != $user->site_id) {
                throw new ManifestException("You cannot not resolve this manifest because it was not dispatched to your site");
            }


            $waybills_to_check = $manifest->waybills->where('status', 0)->pluck('id');
            // $waybills_already_arrived =  $manifest->waybills->where('status', '!=', 0)->pluck('id');

            if (collect($waybills_to_check)->isEmpty()) {
                throw new ManifestException("Cold not resolve, No waybills to acknowledge");
            }

            // echo "Checking arival table....\n";
            $arrived_manifest_waybills_on_k9 = K9DepartureScan::where('SCAN_SITE_CODE', $manifest->next_site_id)
                //->where('PRE_OR_NEXT_STATION_CODE', $manifest->scan_site_id) regular site only
                ->whereDate('SCAN_DATE', '>=', $manifest->created_at)
                ->whereIn('BILL_CODE', $waybills_to_check)
                ->pluck('BILL_CODE');

            // echo "Arrived waybills\n";
            // $arrived_manifest_waybills_on_k9->dump();

            DB::beginTransaction();

            //Quickly make this amends of adding status contraint on the original method
            $total_acknowledged_waybill_count = $manifest->waybills->where('status', WaybillStatus::ACKNOWLEDGED)->pluck('id')->merge($arrived_manifest_waybills_on_k9)->count();

            Waybill::whereIn('id', $arrived_manifest_waybills_on_k9)
                ->where('manifest_id', $data['manifest_id'])
                ->where('status', WaybillStatus::IN_TRANSIT) // or status equals Pre acknwoleged orhwere ManifestStatus::PARTIALLY_RECEIVED
                ->update([
                    'status' => WaybillStatus::ACKNOWLEDGED,
                    'updated_at' => now(),
                    'updated_by' => $user->id,
                    'acknwoledged_by' => $user->id,
                    'acknwoledged_at' => now(),
                    'acknownledgement_remark' => 'I saw it in their departure list'
                ]);


            //Add acknwoledgement date acknowledged_at
            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {
                //I can even use the $manifest variable up there and use save()
                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::PARTIALLY_RECEIVED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
                //Waybills which are not acknowledged to pending
            } else {

                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::ACKNOWLEDGED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
            }
            DB::commit();


            $context = compact('manifest', 'waybills', 'user');

            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {

                //Announce it
                ManifestPartiallyAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest PARTIALLY Acknowleged Successfully'];
            } else {
                //Annouce it
                ManifestAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest Acknowleged Successfully'];
            }
        } catch (Exception $ex) {
            DB::rollBack();

            //Todo rethrow the Manifest Exception ?
            return ['success' => false, 'manifest' => null, 'message' => $ex->getMessage()];
        }
    }


    //you might merge delivery and collection together

    public function Resolve_Manifest_withDeliveryScan($data)
    {

        try {
            $user = $data['user'];
            $manifest = Manifest::with(['waybills:id,status,manifest_id', 'next_site:id,site_type_id,name'])->where('id', (int)$data['manifest_id'])->first();
            if ($manifest === null) {
                throw new ManifestException("Could not resolve, Manifest not found");
            }

            // if ($manifest->next_site->isDC()) {
            //     throw new ManifestException('Could not resolve, This feature does not work when Next site is a DC');
            // }

            if ($manifest->status != ManifestStatus::IN_TRANSIT && $manifest->status != ManifestStatus::PARTIALLY_RECEIVED) {
                throw new ManifestException("You can not resolve  a manifest with " . strtoupper(ManifestStatus::STATUS_TEXT[$manifest->status]) . " status");
            }

            if ($manifest->next_site_id != $user->site_id) {
                throw new ManifestException("You cannot not resolve this manifest because it was not dispatched to your site");
            }


            $waybills_to_check = $manifest->waybills->where('status', 0)->pluck('id');
            // $waybills_already_arrived =  $manifest->waybills->where('status', '!=', 0)->pluck('id');

            if (collect($waybills_to_check)->isEmpty()) {
                throw new ManifestException("Cold not resolve, No waybills to acknowledge");
            }

            // echo "Checking arival table....\n";
            $arrived_manifest_waybills_on_k9 = K9DeliveryScan::where('SCAN_SITE_CODE', $manifest->next_site_id)
                //->where('PRE_OR_NEXT_STATION_CODE', $manifest->scan_site_id) regular site only
                ->whereDate('SCAN_DATE', '>=', $manifest->created_at)
                ->whereIn('BILL_CODE', $waybills_to_check)
                ->pluck('BILL_CODE');

            // echo "Arrived waybills\n";
            // $arrived_manifest_waybills_on_k9->dump();

            DB::beginTransaction();

            //Quickly make this amends of adding status contraint on the original method
            $total_acknowledged_waybill_count = $manifest->waybills->where('status', WaybillStatus::ACKNOWLEDGED)->pluck('id')->merge($arrived_manifest_waybills_on_k9)->count();

            Waybill::whereIn('id', $arrived_manifest_waybills_on_k9)
                ->where('manifest_id', $data['manifest_id'])
                ->where('status', WaybillStatus::IN_TRANSIT) // or status equals Pre acknwoleged orhwere ManifestStatus::PARTIALLY_RECEIVED
                ->update([
                    'status' => WaybillStatus::ACKNOWLEDGED,
                    'updated_at' => now(),
                    'updated_by' => $user->id,
                    'acknwoledged_by' => $user->id,
                    'acknwoledged_at' => now(),
                    'acknownledgement_remark' => 'I saw it in their DELIVERY list'
                ]);


            //Add acknwoledgement date acknowledged_at
            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {
                //I can even use the $manifest variable up there and use save()
                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::PARTIALLY_RECEIVED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
                //Waybills which are not acknowledged to pending
            } else {

                Manifest::where('id', $data['manifest_id'])->update(['status' => ManifestStatus::ACKNOWLEDGED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
            }
            DB::commit();


            $context = compact('manifest', 'waybills', 'user');

            if ($total_acknowledged_waybill_count < count($manifest->waybills)) {

                //Announce it
                ManifestPartiallyAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest PARTIALLY Acknowleged Successfully'];
            } else {
                //Annouce it
                ManifestAcknowledged::dispatch($context);

                return ['success' => true, 'manifest' => $manifest, 'message' => 'Manifest Acknowleged Successfully'];
            }
        } catch (Exception $ex) {
            DB::rollBack();

            //Todo rethrow the Manifest Exception ?
            return ['success' => false, 'manifest' => null, 'message' => $ex->getMessage()];
        }
    }


}
