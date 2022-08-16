<?php

namespace App\Services;

use App\Site;
use App\User;
use Exception;
use App\Waybill;
use App\K9ArrivalScan;
use App\K9DepartureScan;
use App\Enums\WaybillStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WaybillServices
{


    public function getWaybills($filters)
    {
        try {


            $query = "created_at Desc"; // Ordering columns

            $build_query = Waybill::with(['scan_site:id,name', 'next_site:id,name']);

            // if(isset($status))
            // {
                if($filters['status'] != -1) //All
                {

                    $build_query->where('status', $filters['status']);
                }


                if($filters['next_site_id'] != 0) //All
                {

                    $build_query->where('next_site_id', $filters['next_site_id']);
                }



                if($filters['scan_site_id'] != 0) //All
                {

                    $build_query->where('scan_site_id', $filters['scan_site_id']);
                }

                $build_query->whereDate('created_at', '>=', $filters['start_date']);
                $build_query->whereDate('created_at', '<=', $filters['end_date']);

            return $build_query->orderByRaw($query);

        } catch (Exception $ex) {
            //Do something here
        }
    }

public function getDispatchedWaybills($filters)
{

    try {

        $query = "created_at desc"; // "status ASC, id ASC"; // Ordering columns

        $build_query = Waybill::with(['manifest', 'acknowledged_by_user'])
        ->where('scan_site_id', $filters['user']->site->id);
        $next_site_id = ($filters['next_site_id']);
        // if($status !== 0)
        // {
        //     $build_query->where('status', $status);
        // }

        // if($scan_site_id !== 0) // is this even correct what is zero ?
        // {
        //     $build_query->where('scan_site_id', $scan_site_id);
        // }

        if($next_site_id !== 0) // is this even correct what is zero ?
        {
            $build_query->where('next_site_id', $next_site_id);
        }

        if($filters['start_date'] != null)
        {
            $build_query->whereDate('created_at', '>=', $filters['start_date']);

        }

        //Todo , Uncomment the end date

        if($filters['end_date'] != null)
        {
            $build_query->whereDate('created_at', '<=', $filters['end_date']);
        }
        //wheereBetween & protect $dtes column could not help ? No time to check why


        return $build_query->orderByRaw($query);
    } catch (Exception $ex) {
        //Do something here
    }
}

public function getIncomingWaybills($filters)
{
    try {
        $query = "created_at desc"; // "status ASC, id ASC"; // Ordering columns

        $build_query = Waybill::with(['scan_site', 'next_site', 'acknowledged_by_user'])->where('next_site_id', $filters['user']->site->id);
        // $status = ($filters['status']);
        $scan_site_id = ($filters['scan_site_id']);
        // $next_site_id = ($filters['next_site_id']);
        // if($status !== 0)
        // {
        //     $build_query->where('status', $status);
        // }

        if($scan_site_id !== 0) // is this even correct what is zero ?
        {
            $build_query->where('scan_site_id', $scan_site_id);
        }

        // if($next_site_id !== 0) // is this even correct what is zero ?
        // {
        //     $build_query->where('next_site_id', $next_site_id);
        // }

        if($filters['start_date'] != null)
        {
            $build_query->whereDate('created_at', '>=', $filters['start_date']);

        }

        //Todo , Uncomment the end date

        if($filters['end_date'] != null)
        {
            $build_query->whereDate('created_at', '<=', $filters['end_date']);
        }
        //wheereBetween & protect $dtes column could not help ? No time to check why


        return $build_query->orderByRaw($query);
    } catch (Exception $ex) {
        //Do something here
    }
}

public function getPendingWaybills($filters)
    {
        try {
            $build_query = Waybill::whereHas('manifest', function($query)
            {
                $query->where('status','=', 3); // Partially Ack
            })->where('status', '=', 0);


            $status = ($filters['status']);
            $scan_site_id = ($filters['scan_site_id']);

            return $build_query;
        } catch (Exception $ex) {
            //Do something here
        }
    }


    public function getAcknowledgedWaybills($filters)
    {

        try {
            $query = "created_at desc"; // "status ASC, id ASC"; // Ordering columns

            $build_query = Waybill::with(['manifest', 'acknowledged_by_user'])
            ->where(
                [
                    ['next_site_id', '=', $filters['user']->site->id],
                    ['status', '=', WaybillStatus::ACKNOWLEDGED]
                ]
            )->orWhere(   [
                ['next_site_id', '=',$filters['user']->site->id],
                ['status', '=', WaybillStatus::PENDING]
            ]);


            $status = ($filters['status']);
            $scan_site_id = ($filters['scan_site_id']);
            // $next_site_id = ($filters['next_site_id']);
            // if($status !== 0)
            // {
            //     $build_query->where('status', $status);
            // }

            // if($scan_site_id !== 0) // is this even correct what is zero ?
            // {
            //     $build_query->where('scan_site_id', $scan_site_id);
            // }

            // if($next_site_id !== 0) // is this even correct what is zero ?
            // {
            //     $build_query->where('next_site_id', $next_site_id);
            // }


            //Created_date is not equal to acknwoledged date

            if($filters['start_date'] != null)
            {
                $build_query->whereDate('created_at', '>=', $filters['start_date']);

            }

            //Todo , Uncomment the end date

            if($filters['end_date'] != null)
            {
                $build_query->whereDate('created_at', '<=', $filters['end_date']);
            }
            //wheereBetween & protect $dtes column could not help ? No time to check why


            return $build_query->orderByRaw($query);
        } catch (Exception $ex) {
            //Do something here
        }
    }


    public function getDispatchedWaybillsSummary($data)
    {
        $query ="select  s.name as site_name, count(m.id) as total_waybill from waybills m join sites s on s.id = m.next_site_id  where scan_site_id ={$data['scan_site_id']} and CAST(m.created_at as Date) = '{$data['date']}' group by next_site_id";
        return  DB::Select($query);
    }

    public function getIncomingWaybillsSummary($data)
    {
        $query ="select  s.name as site_name, count(m.id) as total_waybill from waybills m join sites s on s.id = m.scan_site_id  where next_site_id ={$data['scan_site_id']} and CAST(m.created_at as Date) = '{$data['date']}' group by scan_site_id";
        return  DB::Select($query);
    }

    public function trackOnK9($waybill_numbers)
    {

        $waybill_numbers_1  = $waybill_numbers;

        $waybill_numbers = implode("', '", $waybill_numbers);
        $result = [];

        //use eloquent ?
        $pickup_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE , SCAN_SITE_CODE,  PRE_OR_NEXT_STATION_CODE,DISPATCH_OR_SEND_MAN_CODE from `TAB_SCAN_REC` where BILL_CODE IN('$waybill_numbers')");
        $departure_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE from `TAB_SCAN_SEND` where BILL_CODE IN('$waybill_numbers')");
        $arrival_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE from `TAB_SCAN_COME` where BILL_CODE IN('$waybill_numbers')");
        $delivery_records = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE,DISPATCH_OR_SEND_MAN_CODE from `TAB_SCAN_DISP` where BILL_CODE IN('$waybill_numbers')");
        $collections_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SIGN_DATE` AS SCAN_DATE, '6' AS SCAN_TYPE_CODE, RECORD_MAN_CODE AS SCAN_MAN_CODE, RECORD_SITE_CODE AS SCAN_SITE_CODE, 'null' AS PRE_OR_NEXT_STATION_CODE, SIGN_MAN, RECORD_MAN from `TAB_SIGN` where BILL_CODE IN('$waybill_numbers')");
        $return_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `REGISTER_DATE` AS SCAN_DATE, '5' AS SCAN_TYPE_CODE, RETURN_MAN_CODE AS SCAN_MAN_CODE, REGISTER_SITE_CODE AS SCAN_SITE_CODE, SEND_SITE_CODE ,RETURN_REASON from NRLY.TAB_BILL_RETURN where BILL_CODE IN('$waybill_numbers')");
        $issue_parcels_records = DB::connection('K9_server')->select("select `BILL_CODE`, `REGISTER_DATE` AS SCAN_DATE, '7' AS SCAN_TYPE_CODE, REGISTER_MAN_CODE AS SCAN_MAN_CODE, REGISTER_SITE_CODE AS SCAN_SITE_CODE, 'null' AS PRE_OR_NEXT_STATION_CODE, PROBLEM_CAUSE from `TAB_PROBLEM` where BILL_CODE IN('$waybill_numbers')");

        $result = collect([]);
        $result = $result->merge($pickup_records)->merge($departure_records)->merge($arrival_records)->merge($delivery_records)->merge($collections_records)->merge($issue_parcels_records)->merge($return_records);


        $site_codes = $result->pluck('SCAN_SITE_CODE')->merge($result->pluck('PRE_OR_NEXT_STATION_CODE'));
        $scanner_codes = $result->pluck('SCAN_MAN_CODE');
        $scanners = User::whereIn('id', $scanner_codes->toArray())->orWhereIn('id', collect($delivery_records)->pluck('DISPATCH_OR_SEND_MAN_CODE')->toArray())->orWhereIn('id', collect($pickup_records)->pluck('DISPATCH_OR_SEND_MAN_CODE')->toArray())->orWhereIn('id', collect($return_records)->pluck('RETURN_MAN_CODE')->toArray())->get(['id','name']);
        $sites = Site::whereIn('id', $site_codes->toArray())->get(['id','name']);


       $result2['scans'] =  $result->sortBy('SCAN_DATE')->values()->all();
       $result2['scanners'] = $scanners;
       $result2['sites'] = $sites;
       $result2['tracked_waybills'] = collect($waybill_numbers_1)->unique()->toArray();


        return $result2;
    }




    public function scanRecordTable()
    {
        /*

        //use eloquent ?
        $pickup_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE , SCAN_SITE_CODE,  PRE_OR_NEXT_STATION_CODE,DISPATCH_OR_SEND_MAN_CODE from `TAB_SCAN_REC` where BILL_CODE IN('$waybill_numbers')");
        $departure_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE from `TAB_SCAN_SEND` where BILL_CODE IN('$waybill_numbers')");
        $arrival_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE from `TAB_SCAN_COME` where BILL_CODE IN('$waybill_numbers')");
        $delivery_records = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE,DISPATCH_OR_SEND_MAN_CODE from `TAB_SCAN_DISP` where BILL_CODE IN('$waybill_numbers')");
        $collections_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SIGN_DATE` AS SCAN_DATE, '6' AS SCAN_TYPE_CODE, RECORD_MAN_CODE AS SCAN_MAN_CODE, RECORD_SITE_CODE AS SCAN_SITE_CODE, 'null' AS PRE_OR_NEXT_STATION_CODE, SIGN_MAN, RECORD_MAN from `TAB_SIGN` where BILL_CODE IN('$waybill_numbers')");
        // $return_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `REGISTER_DATE` AS SCAN_DATE, '8' AS SCAN_TYPE_CODE, REGISTER_MAN_CODE AS SCAN_MAN_CODE, RECORD_SITE_CODE AS SCAN_SITE_CODE, 'null' AS PRE_OR_NEXT_STATION_CODE from `TAB_PROBLEM` where BILL_CODE IN('$waybill_numbers')");
        $issue_parcels_records = DB::connection('K9_server')->select("select `BILL_CODE`, `REGISTER_DATE` AS SCAN_DATE, '7' AS SCAN_TYPE_CODE, REGISTER_MAN_CODE AS SCAN_MAN_CODE, REGISTER_SITE_CODE AS SCAN_SITE_CODE, 'null' AS PRE_OR_NEXT_STATION_CODE, PROBLEM_CAUSE from `TAB_PROBLEM` where BILL_CODE IN('$waybill_numbers')");


        */
        $start_date = Carbon::yesterday()->subDays(4);
        $end_date = Carbon::today();
        $scan_site_id = 234124;


        $date = Carbon::yesterday()->subDays(5);
        $arrival_records = K9ArrivalScan::where('SCAN_SITE_CODE', $scan_site_id)->whereDate('SCAN_DATE', $date)->take(100);
        $waybill_numbers = implode("', '", $arrival_records->pluck('BILL_CODE', 'SCAN_DATE')->toArray());
        $date_as_string = $date;
        $whereDate = " SCAN_DATE >= '$date_as_string'";
        $departure_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE from `TAB_SCAN_SEND` where BILL_CODE IN('$waybill_numbers') AND $whereDate AND SCAN_SITE_CODE = $scan_site_id");
        // $arrival_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE from `TAB_SCAN_COME` where BILL_CODE IN('$waybill_numbers')");
        $delivery_records = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE,DISPATCH_OR_SEND_MAN_CODE from `TAB_SCAN_DISP` where BILL_CODE IN('$waybill_numbers') AND $whereDate AND SCAN_SITE_CODE = $scan_site_id");
        $collections_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SIGN_DATE` AS SCAN_DATE, '6' AS SCAN_TYPE_CODE, RECORD_MAN_CODE AS SCAN_MAN_CODE, RECORD_SITE_CODE AS SCAN_SITE_CODE, 'null' AS PRE_OR_NEXT_STATION_CODE, SIGN_MAN, RECORD_MAN from `TAB_SIGN` where BILL_CODE IN('$waybill_numbers') AND SIGN_DATE >= '$date_as_string' AND RECORD_SITE_CODE = $scan_site_id");
        $return_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `REGISTER_DATE` AS SCAN_DATE, '5' AS SCAN_TYPE_CODE, RETURN_MAN_CODE AS SCAN_MAN_CODE, REGISTER_SITE_CODE AS SCAN_SITE_CODE, SEND_SITE_CODE ,RETURN_REASON from NRLY.TAB_BILL_RETURN where BILL_CODE IN('$waybill_numbers')");
        $issue_parcels_records = DB::connection('K9_server')->select("select `BILL_CODE`, `REGISTER_DATE` AS SCAN_DATE, '7' AS SCAN_TYPE_CODE, REGISTER_MAN_CODE AS SCAN_MAN_CODE, REGISTER_SITE_CODE AS SCAN_SITE_CODE, 'null' AS PRE_OR_NEXT_STATION_CODE, PROBLEM_CAUSE from `TAB_PROBLEM` where BILL_CODE IN('$waybill_numbers') AND REGISTER_DATE >= '$date_as_string' AND REGISTER_SITE_CODE = $scan_site_id");

        $result = collect([]);
        //A regular loop might be better
        $result = $result->merge($departure_records)->merge($arrival_records)->merge($delivery_records)->merge($collections_records)->merge($issue_parcels_records)->merge($return_records);


        $site_codes = $result->pluck('SCAN_SITE_CODE')->merge($result->pluck('PRE_OR_NEXT_STATION_CODE'));
        $scanner_codes = $result->pluck('SCAN_MAN_CODE');
        $scanners = User::whereIn('id', $scanner_codes->toArray())->orWhereIn('id', collect($delivery_records)->pluck('DISPATCH_OR_SEND_MAN_CODE')->toArray())->orWhereIn('id', collect($return_records)->pluck('RETURN_MAN_CODE'))->get(['id','name']);
        $sites = Site::whereIn('id', $site_codes->toArray())->get(['id','name']);


       $result2['scans'] =  $result->sortBy('SCAN_DATE')->values()->all(); // were might be able to ignore the sorting o
       $result2['scanners'] = $scanners;
       $result2['sites'] = $sites;
       $result2['tracked_waybills'] = $arrival_records->pluck('BILL_CODE'); //you can ignore the unique too
       $result2['period_considerd'] = $date;


        return $result2;


    }


    public function scanRecords($filters)
    {
        $scan_site_id = $filters['scan_site_id'];
        $start_date = $filters['start_date'];
        $end_date = $filters['end_date'];
        $date_as_string = "";
        $whereDate =   "SCAN_DATE >= '$start_date'"; //" SCAN_DATE >= '$date_as_string'";
        $records  =  DB::connection('K9_server')->select("select arrival.BILL_CODE, (select count(BILL_CODE) from `TAB_SCAN_SEND` where BILL_CODE = arrival.BILL_CODE AND $whereDate AND SCAN_SITE_CODE = $scan_site_id) as departure_count, (select count(BILL_CODE) from `TAB_SCAN_COME` where BILL_CODE = arrival.BILL_CODE) as arrival_count, (select count(BILL_CODE) from `TAB_SCAN_DISP` where BILL_CODE = arrival.BILL_CODE AND  SCAN_SITE_CODE = $scan_site_id) as delivery_count, (select count(BILL_CODE) from `TAB_SIGN` where BILL_CODE = arrival.BILL_CODE AND RECORD_SITE_CODE = $scan_site_id) as collection_count, (select COUNT(BILL_CODE) from NRLY.TAB_BILL_RETURN where BILL_CODE = arrival.BILL_CODE) as return_count, (select count(BILL_CODE) from `TAB_PROBLEM` where BILL_CODE = arrival.BILL_CODE  AND REGISTER_SITE_CODE = $scan_site_id) as issue_parcel_count  from `TAB_SCAN_COME` as arrival where SCAN_SITE_CODE = $scan_site_id  AND $whereDate ");
        return $records;
    }


    public function getWaybillsArrivalStatus($filters)
    {

        //     $query =  K9DepartureScan::query();
        //     $query->where('SCAN_SITE_CODE', 			$filters['scan_site_id'])
        //             ->where('PRE_OR_NEXT_STATION_CODE', $filters['next_site_id'])
        //             ->where('SCAN_DATE', '>=', 			$filters['start_date'])
        //             ->where('SCAN_DATE', '<=', 			$filters['end_date']);

        // // if ($filters['scanner_id'] != null) // Not yet tested
        // // {
        // //     $query->where('SCAN_MAN_CODE', $filters['scanner_id']);
        // // }

        // $dispatched_waybills =  $query->get(['BILL_CODE', 'SCAN_DATE', 'SCAN_SITE_CODE']);

        //     // echo "scan_start_date: ". $filters['start_date'];
        //     // echo $query->pluck('BILL_CODE');

        // $arrived_waybills =  K9ArrivalScan::where('SCAN_SITE_CODE', $filters['next_site_id'])
        //     ->where('PRE_OR_NEXT_STATION_CODE', $filters['scan_site_id'])
        //     ->where('SCAN_DATE', '>=', $filters['start_date'])
        //     ->whereIn('BILL_CODE', $query->pluck('BILL_CODE'))
        //     ->pluck('BILL_CODE');


            $result = DB::connection('K9_server')->select("Select BILL_CODE, SCAN_DATE, (select max(SCAN_DATE) from TAB_SCAN_COME arrival where arrival.BILL_CODE = s.BILL_CODE and arrival.SCAN_SITE_CODE = {$filters['next_site_id']} and arrival.PRE_OR_NEXT_STATION_CODE = {$filters['scan_site_id']}  and SCAN_DATE >= '{$filters['start_date']}' ) arrival_date  from TAB_SCAN_SEND s where SCAN_SITE_CODE = {$filters['scan_site_id']} AND PRE_OR_NEXT_STATION_CODE = {$filters['next_site_id']} AND  SCAN_DATE >= '{$filters['start_date']}' AND SCAN_DATE <= '{$filters['end_date']}'");
            // return compact('dispatched_waybills', 'arrived_waybills');

            return $result;
        }


        public function getWaybillsArrivalStatusSummary()
        {
            $filters = ['scan_site_id' => 2341, 'next_site_id' => 234124, 'start_date' => '2021-11-18 02:39:40', 'end_date' => '2021-11-18 17:09:14', 'scanner_id' => null];
            $query =  K9DepartureScan::query();
            $query->where('SCAN_SITE_CODE', $filters['scan_site_id'])->where('PRE_OR_NEXT_STATION_CODE', $filters['next_site_id'])->where('SCAN_DATE', '>=', 			$filters['start_date'])->where('SCAN_DATE', '<=', 			$filters['end_date']);

            if ($filters['scanner_id'] != null) // Not yet tested
            {
                $query->where('SCAN_MAN_CODE', $filters['scanner_id']);
            }

          $dispatched_waybills =  $query->count();

            // echo "scan_start_date: ". $filters['start_date'];
            // echo $query->pluck('BILL_CODE');

        $arrived_waybills =  K9ArrivalScan::where('SCAN_SITE_CODE', $filters['next_site_id'])->where('PRE_OR_NEXT_STATION_CODE', $filters['scan_site_id'])->where('SCAN_DATE', '>=', $filters['start_date'])->whereIn('BILL_CODE', $query->pluck('BILL_CODE'))->count();
       // SELECT SITE_NAME, COUNT(BILL_CODE)  from NRLY.TAB_SCAN_SEND JOIN TAB_SITE ON PRE_OR_NEXT_STATION_CODE = TAB_SITE.SITE_CODE WHERE SCAN_SITE_CODE = 2341 AND SCAN_DATE >= '2021-11-23 00:00:01'  AND SCAN_DATE <= '2021-11-23 11:59:59' GROUP BY PRE_OR_NEXT_STATION_CODE
       // SELECT SITE_NAME, COUNT(BILL_CODE)  from NRLY.TAB_SCAN_COME JOIN TAB_SITE ON SCAN_SITE_CODE = TAB_SITE.SITE_CODE WHERE PRE_OR_NEXT_STATION_CODE = 2341 AND  SCAN_DATE >= '2021-11-23 00:00:01'  AND SCAN_DATE <= '2021-11-23 11:59:59' GROUP BY SCAN_SITE_CODE
        echo "$dispatched_waybills - $arrived_waybills";
    }


    public function getK9DepartureScanSummary($filters)
    {
        $result = DB::connection('K9_server')->select("SELECT SITE_NAME, COUNT(BILL_CODE) as WAYBILL_COUNT from NRLY.TAB_SCAN_SEND JOIN TAB_SITE ON PRE_OR_NEXT_STATION_CODE = TAB_SITE.SITE_CODE WHERE SCAN_SITE_CODE = {$filters['scan_site_id']} AND SCAN_DATE >= '{$filters['start_date']}'  AND SCAN_DATE <= '{$filters['end_date']}' GROUP BY PRE_OR_NEXT_STATION_CODE");
        return $result;
    }



    public function getK9IncomingScanSummary($filters)
    {
        $result = DB::connection('K9_server')->select("SELECT SITE_NAME, COUNT(BILL_CODE) as WAYBILL_COUNT from NRLY.TAB_SCAN_SEND JOIN TAB_SITE ON SCAN_SITE_CODE = TAB_SITE.SITE_CODE WHERE PRE_OR_NEXT_STATION_CODE = {$filters['next_site_id']} AND SCAN_DATE >= '{$filters['start_date']}'  AND SCAN_DATE <= '{$filters['end_date']}' GROUP BY SCAN_SITE_CODE");
        return $result;
    }

}
