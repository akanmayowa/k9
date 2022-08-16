<?php

namespace App\Services;

use App\Imports\ScanRecordImport;
use App\Scan;
use App\ScanResult;
use Maatwebsite\Excel\Facades\Excel;

class ScanErrorDetector
{
    public $waybills_scans = [];
    public $all_pickup_scans;
    public $all_departure_scans;
    public $all_arrival_scans;
    public $all_delivery_scans;
    public $all_collection_scans;
    public $waybill_scan_errors = [];
    public $current_waybill_number;
    public $scan_results = [];
    public $scan_result;

    public function ReadFile($file_name)
    {

        $contents = Excel::toArray(new ScanRecordImport, request()->file('file'))[0];
        $first_content = array_shift($contents); //remove the first row
        foreach($contents as $data)
        {
            dd($contents);
            //remove blank lines
            if($data['waybill'] == "")
            {
                continue;
            }
            $this->fixColumns($data, $last_site, $next_site);
            if (!array_key_exists($data['waybill'], $this->waybills_scans)) {

                $this->waybills_scans[$data['waybill']] = [new Scan($data['waybill'], $data['scan_type'], $data['site_of_scan'], $last_site, $next_site, $data['time_of_scan'], $data['scanner'])];
            } else {
                $this->waybills_scans[$data['waybill']][] = new Scan($data['waybill'], $data['scan_type'], $data['site_of_scan'], $last_site, $next_site, $data['time_of_scan'], $data['scanner']);
            }
            //dd($array['scan_type']);
        }
        // dd($this->waybills_scans);
        // foreach()

        // $row = 1;
        // $last_site = "";
        // $next_site = "";
        // $processed = [];
        // if (($handle = fopen($file_name, "r")) !== FALSE) {
        //     fgetcsv($handle); //skip first row
        //     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        //         $this->fixColumns($data, $last_site, $next_site);
        //         if (!array_key_exists($data['waybill'], $this->waybills_scans)) {

        //             $this->waybills_scans[$data['waybill']] = [new Scan($data['waybill'], $data['scan_type'], $data['site_of_scan'], $last_site, $next_site, $data['time_of_scan'], $data['scanner'])];
        //         } else {
        //             $this->waybills_scans[$data['waybill']][] = new Scan($data['waybill'], $data['scan_type'], $data['site_of_scan'], $last_site, $next_site, $data['time_of_scan'], $data['scanner']);
        //         }
        //     }
        //     fclose($handle);

        //     //echo "<p><b>* $file_name</b>....................Successfully Read...........</p><br/><hr>";
        //     // dd($this->waybills_scans);
        // }
    }

    public function ReadFile2($file_name)
    {


        $row = 1;
        $last_site = "";
        $next_site = "";
        $processed = [];
        if (($handle = fopen($file_name, "r")) !== FALSE) {
            fgetcsv($handle); //skip first row
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                $this->fixColumns2($data, $last_site, $next_site);
                if (!array_key_exists($data[5], $this->waybills_scans)) {

                    $this->waybills_scans[$data[5]] = [new Scan($data[5], $data[0], $data[1], $last_site, $next_site, $data[3], $data[4])];
                } else {
                    $this->waybills_scans[$data[5]][] = new Scan($data[5], $data[0], $data[1], $last_site, $next_site, $data[3], $data[4]);
                }
            }
            fclose($handle);

            //echo "<p><b>* $file_name</b>....................Successfully Read...........</p><br/><hr>";
            // dd($this->waybills_scans);
        }
    }

    function pre()
    {
        echo "Hi";
    }


    private function fixColumns2($data, &$last_site, &$next_site)
    {
        if (($data[0] == "Pick-up")  || ($data[0] == "Delivery") || ($data[0] == "Signature")) {
            $last_site = "";
            $next_site = "";
        }

        if ($data[0] == "Depature") {
            $last_site = "";
            $next_site = $data[2];
        }

        if ($data[0] == "Arrival") {
            $last_site = $data[2];
            $next_site = "";
        }
    }


    private function fixColumns($data, &$last_site, &$next_site)
    {
        if (($data['scan_type'] == "Pick-up")  || ($data['scan_type'] == "Delivery") || ($data['scan_type'] == "Signature")) {
            $last_site = "";
            $next_site = "";
        }

        if ($data['scan_type'] == "Depature") {
            $last_site = "";
            $next_site = $data['lastnext_site'];
        }

        if ($data['scan_type'] == "Arrival") {
            $last_site = $data['lastnext_site'];
            $next_site = "";
        }
    }
    private function find($scans, $property, $searchValue)
    {
        $item = null;
        foreach ($scans as $scan) {
            if ($scan->{$property} == $searchValue) {
                $item = $scan;
                break;
            }
        }

        return $item;
    }



    function pre2($data)
    {
        echo "<pre>";

        print_r($data);

        echo "</pre>";
    }

    function DectAllErrors()
    {



       // echo "<pre>* Scan Compliance Process Started.......</pre><hr>";
        foreach ($this->waybills_scans as $waybill => $scans) {
            $this->scan_result =  new ScanResult;
            $this->scan_result->waybill_number = $waybill;



            $this->current_waybill_number = $waybill;
            //echo "<ol>";

            $this->all_pickup_scans = array_filter($this->waybills_scans[$waybill], function ($waybill_scan, $waybills_number) {
                return ($waybill_scan->type_of_scan === "Pick-up");
            }, ARRAY_FILTER_USE_BOTH);

            $this->all_departure_scans = array_filter($this->waybills_scans[$waybill], function ($waybill_scan, $waybills_number) {
                return ($waybill_scan->type_of_scan === "Depature");
            }, ARRAY_FILTER_USE_BOTH);

            $this->all_arrival_scans = array_filter($this->waybills_scans[$waybill], function ($waybill_scan, $waybills_number) {
                return ($waybill_scan->type_of_scan === "Arrival");
            }, ARRAY_FILTER_USE_BOTH);

            $this->all_delivery_scans = array_filter($this->waybills_scans[$waybill], function ($waybill_scan, $waybills_number) {
                return ($waybill_scan->type_of_scan === "Delivery");
            }, ARRAY_FILTER_USE_BOTH);

            $this->all_collection_scans = array_filter($this->waybills_scans[$waybill], function ($waybill_scan, $waybills_number) {
                return ($waybill_scan->type_of_scan === "Signature");
            }, ARRAY_FILTER_USE_BOTH);


            // echo "<pre>";
            // print_r($this->all_arrival_scans);
            // echo "</pre>";
            // $this->pre2($this->all_pickup_scans);
            $this->DetectWrongLastSite();
            // $this->DetectSameSiteDelivery();
            $this->DectectPickupError();
            $this->DetectDeliveryScanDoneByWrongSite();
            // $this->DetectDeliveryScanNotDoneByCollectionSite();


            // $this->DectectArrivalScanError();
            if($this->scan_result->has_error === true)
            {
               // echo "<B style='color:red'>$this->current_waybill_number</B>";
            }
            else {
               // echo "<B style='color:green'>$this->current_waybill_number</B>";
                // echo "<B style='color:green'>OKAY</B>";
            }

            foreach($this->scan_result->errors as $error)
            {
                //echo "<li>$error</li>";
            }


           // echo "</ol><br>------------------------------------<br/>";
            // $this->pre2($this->scan_result);


            $this->scan_results[] = $this->scan_result;
        }
        return $this->scan_results;
        //echo "<pre>* Scan Compliance Process End.......</pre><hr>";
    }


    function DetectWrongLastSite()
    {
        foreach ($this->all_arrival_scans as $arrival_scan) {
            $departure_scan_for_current_arrival_scan = $this->find($this->all_departure_scans, "next_site", $arrival_scan->site_of_scan);
            if ($arrival_scan->site_of_scan === $arrival_scan->last_site) {
                $this->scan_result->has_error = true;
                $this->scan_result->errors[] = "<p>Wrong last site (<span style='color:yellow'>". $arrival_scan->last_site. "</span>) stated by " . $arrival_scan->site_of_scan .  ") Last site is suppose to be(<span style=font-weight:bold; color:green>,". $departure_scan_for_current_arrival_scan->site_of_scan. "</span>)</p>";
              //  echo '<p>Error, Incorrect last site stated by ' . $arrival_scan->site_of_scan . "</p>";
                return;
            }


            if ($departure_scan_for_current_arrival_scan === null) {
                return;
            }

            if ($arrival_scan->last_site !== $departure_scan_for_current_arrival_scan->site_of_scan) {
                $this->scan_result->has_error = true;
                $this->scan_result->errors[] = "<p>Wrong last site (<span style='color:yellow'>". $arrival_scan->last_site. "</span>) stated by (,". $arrival_scan->site_of_scan. ") Last site is suppose to be(<span style=font-weight:bold; color:green>,". $departure_scan_for_current_arrival_scan->site_of_scan. "</span>)</p>";
                //echo '<p><B style="color:red">[X]</B> Error, Wrong last site (<span style="color:yellow">'. $arrival_scan->last_site. '</span>) stated by (', $arrival_scan->site_of_scan, '). Last site is suppose to be(<span style="font-weight:bold; color:green">', $departure_scan_for_current_arrival_scan->site_of_scan, "</span>)</p>";
                return;
            }
        }
    }


    function DectectPickupError()
    {
        $search_result = $this->find($this->waybills_scans[$this->current_waybill_number], "type_of_scan", "Pick-up");
        if ($search_result === null) {
            $this->scan_result->has_error = true;
            $this->scan_result->errors[] = 'Pick up scan not done';
            //echo 'Error, Pick up scan not done';
            return;
        }

        $waybill_pickup_scans = array_filter($this->waybills_scans[$this->current_waybill_number], function ($waybills_scan) {
            return ($waybills_scan->type_of_scan === "Pick-up");
        });

        //Multiple pick up scan
        if (count($waybill_pickup_scans) > 1) {
            //More than one pickup detected
            //echo"Error:( ", $search_result->site_of_scan, " ) did more than one pickup scan";
            $pickup_scan_count_by_sites = array_count_values(array_column($waybill_pickup_scans, 'site_of_scan'));

            foreach ($pickup_scan_count_by_sites as $site => $number_of_pickup_scans) {
                    if($number_of_pickup_scans > 1) // only show errors for sites having more than one scan pickup scan
                    {
                        $this->scan_result->has_error = true;
                        $this->scan_result->errors[] = "<p> ($site) did  [$number_of_pickup_scans] pickup scans</p>";
                    }
                       // echo "<p><B style='color:red'>[X]</B> ($site) did  [$number_of_pickup_scans] pickup scans</p>";
            }

            //search for if the waybill that created the terminal did the pickup
        }

        //Wrong site did pickup
        //Todo

    }


    function DetectDeliveryScanNotDoneByCollectionSite()
    {
        $collection_scan = $this->find($this->waybills_scans[$this->current_waybill_number], "type_of_scan", "Signature");
        $delivery_scan_by_collection_site = $this->find($this->all_delivery_scans, "site_of_scan", $collection_scan->site_of_scan);
        if ($delivery_scan_by_collection_site == null) {
            $this->scan_result->has_error = true;
            $this->scan_result->errors[] = "<p> ($collection_scan->site_of_scan) did not do delivery scan before done collection scan</p>";
           // echo "<p><B style='color:red'>[X]</B> ($collection_scan->site_of_scan) did not do delivery scan before done collection scan</p>";
        }
    }


    function DetectDeliveryScanDoneByWrongSite()
    {
        $collection_scan = $this->find($this->waybills_scans[$this->current_waybill_number], "type_of_scan", "Signature");
        $delivery_scan_sites = array_filter($this->all_delivery_scans, function ($waybill_scan, $waybills_number) use($collection_scan){
            return ($waybill_scan->site_of_scan !== $collection_scan->site_of_scan);
        }, ARRAY_FILTER_USE_BOTH);

        $a = array_map(function($obj){ return $obj->site_of_scan; }, $delivery_scan_sites);
        if($delivery_scan_sites != null)
        {
            $this->scan_result->has_error = true;
            $this->scan_result->errors[] = "<p>why are sites [ " . implode(",", $a)." ] doing delivery for a shipement collected by [ " . $collection_scan->site_of_scan."]</p>";
            //echo "<p><B style='color:red'>[X]</B>  Error: why are sites [ " . implode(",", $a)." ] doing delivery for a shipement collected by [ " . $collection_scan->site_of_scan."]</p>";
        }
    }

    function DetectIssueParcelScanErrors()
    {
        /*
            for each waybiill
               deliveryCount =  get the delivery scans as a collection
               issuesParcelCount =  get the issue parcel scans as a list

                if(deliveryCount < issueParcelCount - 1)
                    //


        */
    }
}

?>
