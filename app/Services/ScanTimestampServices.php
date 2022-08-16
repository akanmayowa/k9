<?php

namespace App\Services;

use App\Site;
use Carbon\Carbon;
use App\ScanTimestamp;
use App\K9DepartureScan;
use App\Services\ManifestBagServices;
use App\Exceptions\ManifestBagException;
use App\Exceptions\ScanTimeGroupingException;

class ScanTimestampServices
{

    public function getCurrentTimeStamp()
    {
        return Carbon::now();
    }

    public function getScanTimestampsQuery($filters)
    {
        //:with(['scan_site', 'next_site',
        //0 means not dispatched
        $query = ScanTimestamp::query();
        $user_time_zone = 'Africa/Lagos'; // this should be in the user's Db
        $today =  Carbon::today('Africa/Lagos'); //Carbon::today();

        //UTC problem
        $query->where('dispatched', 0)
        ->where('created_by', $filters['user_id'])
        ->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) = '$today'");

        // ->where('created_by', $filters['user_id'])->whereDate('created_at', '>=', Carbon::today()->subHours(1));


        // ->whereDate('created_at', '>=', $filters['start_date'])->whereDate('created_at', '<=', $filters['end_date']);
        // $scanner_id = (int)($filters['scanner_id']);
        // if($scanner_id != 0)
        // {
        //     $query->where('scanner_id', $filters['scanner_id']);
        // }

        // if($filters['next_site_id'] != 0)
        // {
        //     $query ->where('next_site_id',  $filters['next_site_id']);
        // }


        $query->with(['scan_site', 'next_site', 'created_by_user', 'scanner'])->orderBy('id', 'DESC');

        return $query;


    }



    public function getGroupsQuery($filters)
    {
        //:with(['scan_site', 'next_site',
        //0 means not dispatched
        $query = ScanTimestamp::query();


        $query->where('dispatched', 0)->where('scan_site_id', $filters['scan_site_id'])

        ->whereDate('created_at', '>=', $filters['start_date'])->whereDate('created_at', '<=', $filters['end_date']);
        $scanner_id = (int)($filters['scanner_id']);
        if($scanner_id != 0)
        {
            $query->where('scanner_id', $filters['scanner_id']);
        }

        if($filters['next_site_id'] != 0)
        {
            $query ->where('next_site_id',  $filters['next_site_id']);
        }



        $query->with(['scan_site', 'next_site', 'created_by_user', 'scanner'])->orderBy('id', 'DESC');

        return $query;


    }

    public function endScan($data)
    {
        $scan_timestamp = ScanTimestamp::find($data['group_id']);
        if(is_null($scan_timestamp))
        {

            throw new ScanTimeGroupingException("Operation Failed, Invalid grouping !");
        }

        if(!is_null($scan_timestamp->end_date))
        {

            throw new ScanTimeGroupingException("Operation Failed, This Groping has already ended !");
        }

        if($scan_timestamp->created_by != $data['user_id'])
        {
            throw new ScanTimeGroupingException("Operation Failed, Only the user who started this grouping can end it!");
        }

        $scan_timestamp->end_date = $data['end_date'];
        $scan_timestamp->updated_at =  Carbon::now();
        $scan_timestamp->save();
    }

    public function deleteGroup($data)
    {
        $scan_timestamp = ScanTimestamp::find($data['group_id']);


        if(is_null($scan_timestamp))
        {

            throw new ScanTimeGroupingException("Cancel Operation Failed, Invalid  supplied !");
        }
        if(is_null($scan_timestamp->created_by))
        {

            throw new ScanTimeGroupingException("Cancel Operation Failed,Invalid created by  supplied !");
        }


        if($scan_timestamp->dispatched == 1)
        {

            throw new ScanTimeGroupingException("Operation Failed, You can not cancel a group that has been dispatched !");
        }

        if($scan_timestamp->created_by != $data['user_id'])
        {
            throw new ScanTimeGroupingException("Operation Failed, Only the user who started this grouping can canel it!");
        }

        $scan_timestamp->delete();

        // $scan_timestamp->cancelled = 1;
        // $scan_timestamp->updated_at =  Carbon::now();
        // $scan_timestamp->save();
    }

    public function getScanTimestampsAsList($filter)
    {
        //:with(['scan_site', 'next_site',
        //the group he created today
        //Next site is, scan_site is ?
        // dd($filter);
        $scan_timestamps = ScanTimestamp::with(['scan_site', 'next_site', 'created_by_user'])
                                // ->where('created_by', $filter['user_id']) enable for only the groups that user created
                                ->where('scan_site_id', $filter['scan_site_id'])
                                ->where('next_site_id', $filter['next_site_id'])
                                ->whereDate('created_at', Carbon::today())->orderBy('id', 'DESC')
                                ->get();
        return $scan_timestamps;
    }

    /*

     $ts = ['start_date' => $start_date,
      'end_date'=> $end_date, 'scan_type'=> 2,
       'cancelled'=> 0, 'scan_site_id' => 2341,
        'next_site_id' => 2343, 'created_by'
    => 2340570, 'updated_by'=> 2340570,
    'created_at' => Carbon\Carbon::now(),
    'updated_at' => Carbon\Carbon::now(),
     'tag' => 'TS433'];

*/
    public function createScanTimestamp($data)
    {
        // $scan_timestamp = [];
        //Does tag already exists ?


        $scan_site_name = Site::find($data['scan_site_id'])->name;
        $next_site_name = Site::find($data['next_site_id'])->name;
        $data['scan_type'] = 2; //Departure
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        $unique_code = Helpers::generateCode(5);
        $data['seal_number'] = $unique_code;


        // if(strlen($data['seal_number'])<1)
        // {
        //     throw new ManifestBagException("Kindly provide a seal Number");
        // }
    //Unique tak?

    //You might want to use ajax validation here
        // if(ManifestBagServices::sealNumberExists($data['seal_number']))
        // {
        //     throw new ManifestBagException("Seal Number already in use");
        // }

        if(strlen($data['tag'])<1)
        {

            $data['tag'] = null;
            // $data['tag'] = $scan_site_name ."/". $next_site_name ."/". $unique_code;
        }


        // if(ScanTimestamp::where('seal_number', $data['seal_number'])->exists())
        // {
        //     throw new ManifestBagException("Seal Number has already been used for grouping");
        // }

        //Does the combination of next_site, scan_site, start_time, end_time already exist ?
        if(ScanTimestamp::where([
            'scan_site_id' => $data['scan_site_id'],
            'next_site_id' => $data['next_site_id']
        ])->where('start_date', '=', $data['start_date'])->where('end_date', '=', $data['end_date'])->exists())
        {
            throw new ManifestBagException("Group with specified period and scanner already exist");
        }

        ScanTimeStamp::create($data);

    }


    public function timeTeller($last_waybill)
    {
        $query = K9DepartureScan::query();
        // foreach($groups as $group) {

            // $waybills = $query->where(function($query) use($groups, $already_departed){
            //     $query->where('SCAN_SITE_CODE', $groups[0]['scan_site_id'])
            //     //Only the person who createds the group can do the k9 scan
            //     ->where('PRE_OR_NEXT_STATION_CODE', $groups[0]['next_site_id'])
            //     ->whereNotIn('BILL_CODE', $already_departed)
            //     ->where('SCAN_DATE', '>=', $groups[0]['start_date'])
            //     ->where('SCAN_DATE', '<=', $groups[0]['end_date']);
            //     if($groups[0]['scanner_id'] != null) // Not yet tested
            //     {
            //         $query->where('SCAN_MAN_CODE', $groups[0]['scanner_id']);

            //     }
            //  });

    }

    //Converts one time formart to  another
    public function convertTimeFormat($time, $to_format=12)
    {
        if($to_format == '12')
        {

            return Carbon::parse($time)->format('g:i:s A'); //12 hour format


        }
        else {
                return  Carbon::parse($time)->format('H:i:s'); //24 hour format
        }
    }

}
