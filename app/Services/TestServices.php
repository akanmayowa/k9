<?php

namespace App\Services;

use App\Site;
use App\User;
use Exception;
use App\Waybill;
use Carbon\Carbon;
use App\K9ReturnScan;
use App\K9ArrivalScan;
use App\Mail\SendTest;
use App\K9DeliveryScan;
use App\K9DepartureScan;
use App\K9CollectionScan;
use App\K9IssueParcelScan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;

class TestServices
{

    public function getCurrentTimeStamp()
    {
        return Carbon::now();
    }




    public function sendMail()
    {
        Mail::to('emacy_245@yahoo.com')->send(new SendTest);
    }


    public function parcelStats()
    {
        //Based on assumptions , these arrays are database tables
        $arrival_scans = collect(['77130066706167', 'NG000172796R', '77130099940752', '47234208553029', 'NG000102693R', 'NG000030135O', '47234208559600']);
        $delivery_scans = collect(['47234208553029', '47234208559600', 'NG000172796R']);
        $issue_parcel_scans = collect(['77130066706167', '77130099940752', 'NG000102693R']);
        $return_parcel_sacans = collect(['47234208559600', '47234208559600']);
        $collection_scans = collect(['NG000172796R']);
        $no_other_scans =  collect(['NG000030135O']);

        $arrival_scans_count = $arrival_scans->count();
        $delivery_scans_count= $delivery_scans->count();
        $issue_parcel_scans_count = $issue_parcel_scans->count();
        $return_parcel_sacans_count= $return_parcel_sacans->count();
        $collection_scans_count = $collection_scans->count();
        $no_other_scans_count= $no_other_scans->count();

        $arrival_after_sub_count = $arrival_scans_count -  $delivery_scans_count;
        $delivery_after_sub_count = $delivery_scans_count - ($issue_parcel_scans_count + $return_parcel_sacans_count + $collection_scans_count);
        $pending_as_in_not_in_return_and_not_in_collection_count = $arrival_scans_count- ($return_parcel_sacans_count +  $collection_scans_count);

        $this->printLine("Arrival ({$arrival_scans->count()}) After Sub ($arrival_after_sub_count )");
        $this->printLine("Delivery ({$delivery_scans->count()}) After Sub($delivery_after_sub_count) ");
        $this->printLine("Issue Parcel ({$issue_parcel_scans->count()})");
        $this->printLine("Return ({$return_parcel_sacans->count()})");
        $this->printLine("Collection Scans ({$collection_scans->count()})");
        $this->printLine("Pending ({$pending_as_in_not_in_return_and_not_in_collection_count})");

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


        $date = Carbon::yesterday();
        $arrival_records = K9ArrivalScan::where('SCAN_SITE_CODE', 234124)->whereDate('SCAN_DATE', $date)->take(100)->get(['BILL_CODE','SCAN_SITE_CODE']);
        $waybill_numbers = implode("', '", $arrival_records->pluck('BILL_CODE')->toArray());

        $departure_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE from `TAB_SCAN_SEND` where BILL_CODE IN('$waybill_numbers')");
        $arrival_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE from `TAB_SCAN_COME` where BILL_CODE IN('$waybill_numbers')");
        $delivery_records = DB::connection('K9_server')->select("select `BILL_CODE`, `SCAN_DATE`, SCAN_TYPE_CODE, SCAN_MAN_CODE, SCAN_SITE_CODE, PRE_OR_NEXT_STATION_CODE,DISPATCH_OR_SEND_MAN_CODE from `TAB_SCAN_DISP` where BILL_CODE IN('$waybill_numbers')");
        $collections_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `SIGN_DATE` AS SCAN_DATE, '6' AS SCAN_TYPE_CODE, RECORD_MAN_CODE AS SCAN_MAN_CODE, RECORD_SITE_CODE AS SCAN_SITE_CODE, 'null' AS PRE_OR_NEXT_STATION_CODE, SIGN_MAN, RECORD_MAN from `TAB_SIGN` where BILL_CODE IN('$waybill_numbers')");
        // $return_records  = DB::connection('K9_server')->select("select `BILL_CODE`, `REGISTER_DATE` AS SCAN_DATE, '8' AS SCAN_TYPE_CODE, REGISTER_MAN_CODE AS SCAN_MAN_CODE, RECORD_SITE_CODE AS SCAN_SITE_CODE, 'null' AS PRE_OR_NEXT_STATION_CODE from `TAB_PROBLEM` where BILL_CODE IN('$waybill_numbers')");
        $issue_parcels_records = DB::connection('K9_server')->select("select `BILL_CODE`, `REGISTER_DATE` AS SCAN_DATE, '7' AS SCAN_TYPE_CODE, REGISTER_MAN_CODE AS SCAN_MAN_CODE, REGISTER_SITE_CODE AS SCAN_SITE_CODE, 'null' AS PRE_OR_NEXT_STATION_CODE, PROBLEM_CAUSE from `TAB_PROBLEM` where BILL_CODE IN('$waybill_numbers')");



        // $departure_records = K9DepartureScan::whereIn('BILL_CODE', $arrival_records->pluck('BILL_CODE'))->where('SCAN_SITE_CODE', 234124)->whereDate('SCAN_DATE', '>=' , $date)->get(['BILL_CODE', 'SCAN_DATE', 'SCAN_SITE_CODE']);
        // $issue_parcels_records = K9IssueParcelScan::whereIn('BILL_CODE', $arrival_records->pluck('BILL_CODE'))->where('REGISTER_SITE_CODE', 234124)->whereDate('REGISTER_DATE', '>=' , $date)->get(['BILL_CODE', 'REGISTER_DATE', 'REGISTER_SITE_CODE']);
        // $delivery_records = K9DeliveryScan::whereIn('BILL_CODE', $arrival_records->pluck('BILL_CODE'))->where('SCAN_SITE_CODE', 234124)->whereDate('SCAN_DATE', '>=' , $date)->get(['BILL_CODE', 'SCAN_DATE', 'SCAN_SITE_CODE']);
        // $collections_records = K9CollectionScan::whereIn('BILL_CODE', $arrival_records->pluck('BILL_CODE'))->where('RECORD_SITE_CODE', 234124)->whereDate('RECORD_DATE', '>=' , $date)->get(['BILL_CODE', 'RECORD_DATE', 'RECORD_MAN_CODE AS SCAN_MAN_CODE', 'RECORD_SITE_CODE','DISPATCH_OR_SEND_MAN_CODE']);
        // // $return_parcel_sacans = K9ReturnScan::whereIn('R_BILLCODE', $arrival_records->pluck('BILL_CODE'))->whereDate('REGISTER_DATE', '>=' , $date)->get();

        $result = collect([]);
        $result = $result->merge($departure_records)->merge($arrival_records)->merge($delivery_records)->merge($collections_records)->merge($issue_parcels_records);


        $site_codes = $result->pluck('SCAN_SITE_CODE')->merge($result->pluck('PRE_OR_NEXT_STATION_CODE'));
        $scanner_codes = $result->pluck('SCAN_MAN_CODE');
        $scanners = User::whereIn('id', $scanner_codes->toArray())->orWhereIn('id', collect($delivery_records)->pluck('DISPATCH_OR_SEND_MAN_CODE')->toArray())->get(['id','name']);
        $sites = Site::whereIn('id', $site_codes->toArray())->get(['id','name']);


       $result2['scans'] =  $result->sortBy('SCAN_DATE')->values()->all(); // were might be able to ignore the sorting o
       $result2['scanners'] = $scanners;
       $result2['sites'] = $sites;
       $result2['tracked_waybills'] = collect($waybill_numbers)->unique()->toArray(); //you can ignore the unique too


        return $result2;


    }

    public function printLine($line)
    {
        echo "\n";
        echo $line."\n";
        echo "__________________________________________________\n";
    }

}
