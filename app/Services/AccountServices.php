<?php
namespace App\Services;

use App\Site;
use App\Place;
use App\State;
use Exception;
use App\Tarriff;
use App\K9Waybill;
use App\K9DeliveryScan;
use App\TarriffZonning;
use App\TarriffLocation;
use App\Enums\TarriffZone;
use Illuminate\Support\Facades\DB;

class AccountServices {

    public $tarriff_services = null;
    public $stateId;
    public $places;


    public function __construct(TarriffQuotationServices $tarriff_services)
    {
        $this->tarriff_services = $tarriff_services;
    }
    //used to assign location_id and is franchisee to sites
    //check sychronizers
    public function getDataToInput()
    {
        $csvFileName = "sites.csv";
        $csvFile = public_path('csv/' . $csvFileName);
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
           $line = fgetcsv($file_handle, 0, ',');
           $site_name = $line[0];
           $tarriff_location_id = (int)$line[1];
           $is_a_franchise = (int)$line[3];
           $site = Site::Where('name', $site_name)->get('id')[0];
           $site->update(
               [
                   'tarriff_location_id' => $tarriff_location_id,
                   'is_a_franchise' => $is_a_franchise
               ]
           );
           echo $site->id."|".$site_name."|".$tarriff_location_id."|".$is_a_franchise."\n";
           $line_of_text[] = $line;
        }
        fclose($file_handle);
        return $line_of_text;
    }

    //used to bring in zone_id for locations
    public function updateTarriffLocationZones()
    {
        TarriffLocation::whereIn('name',
        [
            'LAGOS'
        ]
        )->update(['zone_id' => 1]);

        TarriffLocation::whereIn('name',
        [
            'ABEOKUTA',
            'ADO EKITI',
            'AKURE',
            'ASABA',
            'AUCHI',
            'BENIN',
            'EKPOMA',
            'IBADAN',
            'IFE',
            'IJEBU-ODE',
            'ILORIN',
            'LAFIA',
            'ONITSHA',
            'OSHOGBO',
            'WARRI',
            'SAPELE'
        ]
        )->update(['zone_id' => 2]);

        TarriffLocation::whereIn('name',
        [
            'ABA',
            'ABAKALIKI',
            'ABUJA',
            'ENUGU',
            'GUSAU',
            'GOMBE',
            'GWAGWALADA',
            'LOKOJA',
            'NNEWI',
            'NSUKKA',
            'OWERRI',
            'PORT HARCOURT',
            'UMUAHIA'
        ]
        )->update(['zone_id'=> 3]);

        TarriffLocation::whereIn('name',
        [
            'BAUCHI',
            'BONNY',
            'CALABAR',
            'DAMATURU',
            'DUTSE',
            'EKET',
            'JALINGO',
            'KEBBI',
            'JOS',
            'KADUNA',
            'KANO',
            'KATSINA',
            'MAKURDI',
            'MAIDUGURI',
            'MINNA',
            'SOKOTO/GUSAU',
            'UYO',
            'YENAGOA',
            'YOLA/JALINGO',
            'ZARIA'
        ]
        )->update(['zone_id' => 4]);

        echo "successful....";
    }

    public function getDeliveryFee($weight, $lh_value,  $mid_value, $rh_value)
    {
        return $lh_value + (($weight - $mid_value) * $rh_value);
    }

    public function getTransferFee($weight, $lh_value, $rh_value)
    {
        return (double)($lh_value + ($weight - 1) * $rh_value);
    }



    public function transferFee($zone, $weight)
    {
        if($weight > 0 && $weight <= 20)
        {
            if($zone == 1)
            {
                return $this->getTransferFee($weight, 100, 50);
            }
            else if($zone == 2)
            {
                return $this->getTransferFee($weight, 100, 100);
            }
            else if($zone == 3)
            {
                return  $this->getTransferFee($weight, 100, 150);
            }
            else if($zone == 4)
            {
                return  $this->getTransferFee($weight, 100, 200);
            }
            else {
                throw new Exception("invalid zone supplied for transfer fee calculation");
            }
        }
        else if($weight > 20 && $weight <= 500)
        {
            if($zone == 1)
            {

                return $this->getTransferFee($weight, 1100, 25);
            }
            else if($zone == 2)
            {
                return $this->getTransferFee($weight, 2100, 35);
            }
            else if($zone == 3)
            {
                return  $this->getTransferFee($weight, 2600, 45);
            }
            else if($zone == 4)
            {
                return   $this->getTransferFee($weight, 2800, 90);
            }
            else {
                throw new Exception("invalid zone supplied for transfer fee calculation");
            }
        }

        else if($weight > 500 && $weight <= 2000)
        {
            if($zone == 1)
            {

                return $this->getTransferFee($weight, 1100, 15);
            }
            else if($zone == 2)
            {
                return $this->getTransferFee($weight, 2100, 30);
            }
            else if($zone == 3)
            {
                return  $this->getTransferFee($weight, 2600, 38);
            }
            else if($zone == 4)
            {
                return  $this->getTransferFee($weight, 2800, 75);
            }
            else {
                throw new Exception("invalid zone supplied for transfer fee calculation");
            }
        }

        else if($weight > 2000 && $weight < 5000)
        {
            if($zone == 1)
            {

                return $this->getTransferFee($weight, 1100, 8);
            }
            else if($zone == 2)
            {
                return $this->getTransferFee($weight, 2100, 20);
            }
            else if($zone == 3)
            {
                return  $this->getTransferFee($weight, 2600, 35);
            }
            else if($zone == 4)
            {
                return  $this->getTransferFee($weight, 2800, 70);
            }
            else {
                throw new Exception("invalid zone supplied for transfer fee calculation");
            }
        }
        else
        {
            throw new Exception("Invalid weight supplied for transfer fee");
        }


        // return compact('zone_1_transfer_fee', 'zone_2_transfer_fee', 'zone_3_transfer_fee', 'zone_4_transfer_fee', 'delivery_fee_without_COD', 'delivery_fee_with_COD');

    }

    public function deliveryFee($weight, $is_COD)
    {
            if($weight > 0 && $weight <= 5)
            {
                if($is_COD)
                return $this->getDeliveryFee($weight, 500, 0, 0);
                else
                return $this->getDeliveryFee($weight, 250, 0, 0);
            }
            else if($weight > 5 && $weight <= 20)
            {
                if($is_COD)
                return $this->getDeliveryFee($weight, 500, 5, 30);
                else
                return $this->getDeliveryFee($weight, 250, 5, 30);
            }
            else if($weight > 20 && $weight <= 9999)
            {
                if(!$is_COD)
                return  $this->getDeliveryFee($weight, 700, 5, 15);
                else
                return $this->getDeliveryFee($weight, 700, 5, 15);
            }
            else
            {
                throw new Exception("Invalid weight supplied for delivery fee");
            }

    }

    public function pickupFee($waybill_freight, $transfer_fee, $delivery_fee)
    {
       return  $waybill_freight - ($transfer_fee + $delivery_fee);
    }

    public function roundWeight($data)
    {
        $weight = $data['weight'];
        $max_weight = 70;
        $column_name = null;
        $tarriff = 0;

        if ($data['zone_id'] === TarriffZone::ZONE_ONE) {
            $column_name = 'zone_1_cost_in_cents';
            $extra_cost_for_zone = 100;
        }


        else if($data['zone_id'] === TarriffZone::ZONE_TWO)
        {
            $column_name = 'zone_2_cost_in_cents';
            $extra_cost_for_zone = 150;
        }


        else if($data['zone_id'] === TarriffZone::ZONE_THREE)
        {
            $column_name = 'zone_3_cost_in_cents';
            $extra_cost_for_zone = 200;
        }


       else  if($data['zone_id'] === TarriffZone::ZONE_FOUR)
        {
            $column_name = 'zone_4_cost_in_cents';
            $extra_cost_for_zone = 350;
        }
        else {
            throw new Exception("Unknow Zone number {$data['zone_id']} Supplied ");
        }

        //round to the nearest 0.5

        $extra_tarriff = 0;

        if($weight > 0 && $weight < 20)
        {
          return   $rounded_weight = ceil($weight*2)/2;

        }
        else if($weight >= 20)
        {
            $rounded_weight = ceil($weight);
            if($rounded_weight > $max_weight)
            {
                $extra_weight = ( $rounded_weight - $max_weight);
                $extra_tarriff = ($extra_weight * $extra_cost_for_zone);
                $rounded_weight = $max_weight;
            }

            return $rounded_weight;
        }
        else {
            throw new Exception("Invalid Weight supplied");
        }

    }

    /*
    data : [
        'start_date' => '2022-01-01',
         'end_date' = '2022-01-05',
         'site_to_consider_id' => 234421
    ]

    */
    public function getFeeData($filters)
    {
        $start_date = $filters['start_date'];
        $end_date = $filters['end_date'];
        $site_to_consider_id = $filters['site_id']; //ABA-ABA 234421;

        //COD ?
        // $cod_filter = null;
        // if($filters['cod'] != null)
        // {
        //     $cod_filter = ' AND WAYBILL.GOODS_PAYMENT > 0 ';
        // }

        //Neutral site ukor ?

        $waybills =
        DB::connection('K9_server')->select("SELECT
        COLLECTION_SCAN.BILL_CODE,
        CAST(COLLECTION_SCAN.SIGN_DATE AS DATE) AS SIGN_DATE,
        COLLECTION_SCAN.SIGN_SITE_CODE as collection_site,
        round((COALESCE(WAYBILL.FREIGHT, 0) / cast(PIECE_NUMBER AS unsigned)), 2)  AS freight,
		round((WAYBILL.BILL_WEIGHT / cast(PIECE_NUMBER AS unsigned)), 2)  AS pickup_weight,
        round((COALESCE(WAYBILL.GOODS_PAYMENT, 0) / cast(PIECE_NUMBER AS unsigned)), 2)  as COD,
        cast(PIECE_NUMBER AS unsigned) AS PEICE_NUMBER,
        WAYBILL.REC_SITE_CODE as pickup_site,
        WAYBILL.SIGN_SITE_CODE,
        COALESCE(WAYBILL.BL_RETURN, 0) AS has_return_scan
        FROM NRLY.TAB_SIGN COLLECTION_SCAN
        LEFT JOIN TAB_BILL WAYBILL
        ON SUBSTRING_INDEX(COLLECTION_SCAN.BILL_CODE, '-', 1) = WAYBILL.BILL_CODE
        WHERE
        COLLECTION_SCAN.RECORD_SITE_CODE = $site_to_consider_id AND
        CAST(COLLECTION_SCAN.SIGN_DATE AS DATE) >= '$start_date'
        and CAST(COLLECTION_SCAN.SIGN_DATE AS DATE) <='$end_date' ORDER BY SIGN_DATE");
        $sites = Site::get(['id', 'name', 'tarriff_location_id']);// perhaps get some columns
        $site_to_consider =  $sites->where('id',  $site_to_consider_id)->first();
        $zonnings = TarriffZonning::where(['destination_location_id'=> $site_to_consider->tarriff_location_id])->get();

        $result = [];
        $total_delivered_waybills  = 0;
        $total_delivery_commission = 0;
        $cod_to_remit = 0;
        foreach($waybills as $waybill)
        {
            $pickup_site =     $waybill->pickup_site;
            $collection_site = $waybill->collection_site; // this one is supposed to be collection site

            $departure_site =   $sites->where('id', $pickup_site)->first();
            $destination_site = $sites->where('id', $collection_site)->first();

            $zone =  $zonnings->where('departure_location_id',$departure_site->tarriff_location_id)->where('destination_location_id', $destination_site->tarriff_location_id)->first()->zone_id;
            // $weight = null;

            $weight = $this->normalizeWeight($waybill, $zone);

            $transfer_fee = 0;
            $delivery_fee = 0;
            $pickup_fee = 0;

            if(!$waybill->has_return_scan)
            {
                $transfer_fee = $this->transferFee($zone, $weight);
                $delivery_fee = $this->deliveryFee($weight, ((int)$waybill->COD) != 0);

                if($this->waybillIsEligbleForPickUpFee($waybill)) // >= I guess
                {

                    //freight for a key account
                    $pickup_fee = $this->pickupFee($waybill->freight, $transfer_fee, $delivery_fee);
                }
            }


            // $pickup_fee = 0;
            // if($waybill->freight > 0) // >= I guess
            // {
            //     $pickup_fee = $this->pickupFee($waybill->freight, $transfer_fee, $delivery_fee);
            // }



            $total_delivered_waybills += 1;
            $total_delivery_commission += $delivery_fee;
            $cod_to_remit += $waybill->COD;

           // echo "$waybill->BILL_CODE | $departure_site->name -  $destination_site->name  |Zone= ".$zone." | Freight= ".$waybill->freight." | COD= ".$waybill->COD."| Weight= ".$weight." | Transfer Fee= $transfer_fee | Delivery Fee= $delivery_fee | Pickup Fee= $pickup_fee\n";

           $result[] = [
                'waybill_number' => $waybill->BILL_CODE,
                'collection_date' => $waybill->SIGN_DATE,
                'departure_site' => $departure_site,
                'destination_site' => $destination_site,
                'zone' => $zone,
                'freight' => $waybill->freight,
                'COD' => $waybill->COD,
                'weight' => $waybill->pickup_weight,
                'transfer_fee' => $transfer_fee,
                'delivery_fee' => $delivery_fee,
                'pickup_fee' => $pickup_fee,
                'has_return_scan' => $waybill->has_return_scan,
                'total_delivered_waybills' => $total_delivered_waybills,
                'total_delivery_commission' => $total_delivery_commission,
                'cod_to_remit' => $cod_to_remit
            ];
        }

        return $result;
    }

    public function normalizeWeight($waybill, $zone)
    {
        if($waybill->pickup_weight < 1)
        {
            $weight = 1;
        }
        else
        {
            $weight = $this->roundWeight(['weight' =>  $waybill->pickup_weight, 'zone_id' => $zone]);
        }

        return $weight;
    }

    public function getPickUpCommission($filters)
    {


        // return $this->getFeeData($filters);
        $start_date = 	$filters['start_date'];
        $end_date = 	$filters['end_date'];
        $site_to_consider_id = $filters['site_id']; //ABA-ABA 234421;




        //COD ?
        // $cod_filter = null;
        // if($filters['cod'] != null)
        // {
        //     $cod_filter = ' AND WAYBILL.GOODS_PAYMENT > 0 ';
        // }

        //Neutral site ukor ?

        $waybills =
        DB::connection('K9_server')->select("SELECT
        PICKUP.BILL_CODE,
        PICKUP.SCAN_DATE,
        PICKUP.SCAN_SITE_CODE,
        PICKUP.REMARK,
        CAST(COLLECTION_SCAN.SIGN_DATE AS DATE) AS SIGN_DATE,
        COLLECTION_SCAN.SIGN_SITE_CODE as collection_site,
        round((COALESCE(WAYBILL.FREIGHT, 0) / cast(PIECE_NUMBER AS unsigned)), 2)  AS freight,
		round((WAYBILL.BILL_WEIGHT / cast(PIECE_NUMBER AS unsigned)), 2)  AS pickup_weight,
        round((COALESCE(WAYBILL.GOODS_PAYMENT, 0) / cast(PIECE_NUMBER AS unsigned)), 2)  as COD,
        cast(PIECE_NUMBER AS unsigned) AS PEICE_NUMBER,
        WAYBILL.REC_SITE_CODE as pickup_site,
        WAYBILL.SIGN_SITE_CODE,
        WAYBILL.DESTINATION_CODE,
        WAYBILL.ACCEPT_MAN_ADDRESS as receiver_address,
		COALESCE(WAYBILL.BL_RETURN, 0) AS has_return_scan
        FROM NRLY.TAB_SCAN_REC as PICKUP
        LEFT JOIN TAB_BILL WAYBILL
        ON SUBSTRING_INDEX(PICKUP.BILL_CODE, '-', 1) = WAYBILL.BILL_CODE
        LEFT JOIN NRLY.TAB_SIGN AS COLLECTION_SCAN  -- Collection scan is optional
        ON PICKUP.BILL_CODE = COLLECTION_SCAN.BILL_CODE
        WHERE
        PICKUP.SCAN_SITE_CODE =$site_to_consider_id AND
        CAST(PICKUP.SCAN_DATE AS DATE) >= '$start_date'
        and CAST(PICKUP.SCAN_DATE AS DATE) <='$end_date'
        and (length(PICKUP.BILL_CODE) = 14 OR length(PICKUP.BILL_CODE) = 18)
        and LEFT( PICKUP.BILL_CODE, 5) = '86234'
        ORDER BY PICKUP.SCAN_DATE");


            $sites = Site::get(['id', 'name', 'tarriff_location_id']); //you might get it onces
            $site_to_consider =  $sites->where('id',  $site_to_consider_id)->first();

            //all zonnings assigned to a site location, sites are assigned a location, location are assigned zones
            //if $site_to_consider does not exists ?
            $zonnings = TarriffZonning::where(['departure_location_id'=> $site_to_consider->tarriff_location_id])->get();

            //tarriff Locations
            $tarriff_locations = TarriffLocation::get();

        // dd($waybills);
        $result = [];
        $total_pickup_fee = 0;
        $total_pickup_waybills = 0;
        $delivery_fee_plus_transfer_fee = 0;
        // try {
            foreach($waybills as $waybill)
            {
                $pickup_site =     $waybill->pickup_site; // site under consideration
                $collection_site = $waybill->collection_site;

                //only wabills that have been collected have destination site / collection site
                $departure_site =   $sites->where('id', $pickup_site)->first();
                $destination_site = $sites->where('id', $collection_site)->first();


				//-- Start Zone Discovery
                $zone = null;
                $zone_strategy = null;
                //Get zone
                if(!is_null($destination_site))
                {
                    $zone_strategy = "departure_location_to_destination_location";
                    $zone =  $zonnings->where('departure_location_id',$departure_site->tarriff_location_id)->where('destination_location_id', $destination_site->tarriff_location_id)->first()->zone_id;
                    //check for nulls
                }
                else if(!is_null($waybill->DESTINATION_CODE))
                {
                    $tarriff_location_id = $sites->where('id',  $waybill->DESTINATION_CODE)->first()->tarriff_location_id;
                    $zone = $tarriff_locations->where('id',  $tarriff_location_id)->first()->zone_id;
                    $zone_strategy = "k9_destination_zone";
                }

                //try using state strategy?
                else
                {
                    throw new Exception("no zone assigned to waybill number $waybill->BILL_CODE");
                }

				//--End Zone Discovery


                $freight = $waybill->freight;
                if($waybill->freight <= 0)
                {
                    $freight = $this->tarriff_services->getTarriff(['zone_id' => $zone, 'weight' => $waybill->pickup_weight ]);
                }

                $weight = null;
                if($waybill->pickup_weight < 1)
                {
                    $weight = 1;
                }
                else
                {
                    $weight = $this->roundWeight(['weight' =>  $waybill->pickup_weight, 'zone_id' => $zone]);
                }



                $transfer_fee = $this->transferFee($zone, $weight);
                $delivery_fee = $this->deliveryFee($weight, ((int)$waybill->COD) != 0);

                // $pickup_fee = 0;
                // if($waybill->freight > 0) // >= I guess
                // {
                //     $pickup_fee = $this->pickupFee($waybill->freight, $transfer_fee, $delivery_fee);
                // }

                $pickup_fee = 0;
                if($this->waybillIsEligbleForPickUpFee($waybill)) // >= I guess
                {
                    $pickup_fee = $this->pickupFee($freight, $transfer_fee, $delivery_fee);
                }

                $total_pickup_fee += $pickup_fee; // call it commission
                $total_pickup_waybills += 1;
                $delivery_fee_plus_transfer_fee += $transfer_fee + $delivery_fee;


               // echo "$waybill->BILL_CODE | $departure_site->name -  $destination_site->name  |Zone= ".$zone." | Freight= ".$waybill->freight." | COD= ".$waybill->COD."| Weight= ".$weight." | Transfer Fee= $transfer_fee | Delivery Fee= $delivery_fee | Pickup Fee= $pickup_fee\n";

               $result[] = [
					'waybill' => $waybill,
                    'waybill_number' => $waybill->BILL_CODE,
                    'collection_date' => $waybill->SIGN_DATE,
                    'departure_site' => $departure_site,
                    'destination_site' => $destination_site,
                    'zone' => $zone,
                    'freight' => $freight,
                    'COD' => $waybill->COD,
                    'weight' => $waybill->pickup_weight,
                    'transfer_fee' => $transfer_fee,
                    'delivery_fee' => $delivery_fee,
                    'delivery_fee_plus_transfer_fee' => $delivery_fee_plus_transfer_fee,
                    'pickup_fee' => $pickup_fee,
                    'zone_strategy' => $zone_strategy,
                    'receiver_address' => $waybill->receiver_address
                    ,
                    'total_pickup_fee' => $total_pickup_fee,
                    'total_pickup_waybills' => $total_pickup_waybills,
                    ''
                ];


            }
        return $result;
    }


    public function getPickupFee()
    {
        return "Pickup FEE";
    }

    public function waybillIsEligbleForPickUpFee($waybill)
    {
        //Database Level operation ?
       return str_starts_with($waybill->BILL_CODE, '86234');
    }


    public function getCommissionStats($filters)
    {

        // return $this->getFeeData($filters);
        $start_date = 	$filters['start_date'];
        $end_date = 	$filters['end_date'];
        $site_to_consider_id = $filters['site_id']; //ABA-ABA 234421;

        $pickup_info =      $this->getPickUpCommission($filters);
        $delivery_and_cod = $this->getFeeData($filters);

       return compact('pickup_info', 'delivery_and_cod');
    }




    public function getDateRangeSearchForDashboard($filters)
    {
        $start_date = $filters['start_date'];
        $end_date = $filters['end_date'];
        $site_to_consider_id = $filters['site_id']; //ABA-ABA 234421;
        $waybills =
        DB::connection('K9_server')->select("SELECT
        COLLECTION_SCAN.BILL_CODE,
        CAST(COLLECTION_SCAN.SIGN_DATE AS DATE) AS SIGN_DATE,
        COLLECTION_SCAN.SIGN_SITE_CODE as collection_site,
        round((COALESCE(WAYBILL.FREIGHT, 0) / cast(PIECE_NUMBER AS unsigned)), 2)  AS freight,
		round((WAYBILL.BILL_WEIGHT / cast(PIECE_NUMBER AS unsigned)), 2)  AS pickup_weight,
        round((COALESCE(WAYBILL.GOODS_PAYMENT, 0) / cast(PIECE_NUMBER AS unsigned)), 2)  as COD,
        cast(PIECE_NUMBER AS unsigned) AS PEICE_NUMBER,
        WAYBILL.REC_SITE_CODE as pickup_site,
        WAYBILL.SIGN_SITE_CODE,
        COALESCE(WAYBILL.BL_RETURN, 0) AS has_return_scan
        FROM NRLY.TAB_SIGN COLLECTION_SCAN
        LEFT JOIN TAB_BILL WAYBILL
        ON SUBSTRING_INDEX(COLLECTION_SCAN.BILL_CODE, '-', 1) = WAYBILL.BILL_CODE
        WHERE
        COLLECTION_SCAN.RECORD_SITE_CODE = $site_to_consider_id AND
        CAST(COLLECTION_SCAN.SIGN_DATE AS DATE) >= '$start_date'
        and CAST(COLLECTION_SCAN.SIGN_DATE AS DATE) <='$end_date' ORDER BY SIGN_DATE");
        $sites = Site::get(['id', 'name', 'tarriff_location_id']);// perhaps get some columns
        $site_to_consider =  $sites->where('id',  $site_to_consider_id)->first();
        $zonnings = TarriffZonning::where(['destination_location_id'=> $site_to_consider->tarriff_location_id])->get();

        $result = [];
        $total_delivered_waybills  = 0;
        $total_delivery_commission = 0;
        $cod_to_remit = 0;
        foreach($waybills as $waybill)
        {
            $pickup_site =     $waybill->pickup_site;
            $collection_site = $waybill->collection_site; // this one is supposed to be collection site
            $departure_site =   $sites->where('id', $pickup_site)->first();
            $destination_site = $sites->where('id', $collection_site)->first();
            $zone =  $zonnings->where('departure_location_id',$departure_site->tarriff_location_id)->where('destination_location_id', $destination_site->tarriff_location_id)->first()->zone_id;
            $weight = $this->normalizeWeight($waybill, $zone);
            $transfer_fee = 0;
            $delivery_fee = 0;
            $pickup_fee = 0;
            if(!$waybill->has_return_scan)
            {
                $transfer_fee = $this->transferFee($zone, $weight);
                $delivery_fee = $this->deliveryFee($weight, ((int)$waybill->COD) != 0);

                if($this->waybillIsEligbleForPickUpFee($waybill)) // >= I guess
                {

                    $pickup_fee = $this->pickupFee($waybill->freight, $transfer_fee, $delivery_fee);
                }
            }
            $total_delivered_waybills += 1;
            $total_delivery_commission += $delivery_fee;
            $cod_to_remit += $waybill->COD;
           $result[] = [
                'waybill_number' => $waybill->BILL_CODE,
                'collection_date' => $waybill->SIGN_DATE,
                'departure_site' => $departure_site,
                'destination_site' => $destination_site,
                'zone' => $zone,
                'freight' => $waybill->freight,
                'COD' => $waybill->COD,
                'weight' => $waybill->pickup_weight,
                'transfer_fee' => $transfer_fee,
                'delivery_fee' => $delivery_fee,
                'pickup_fee' => $pickup_fee,
                'has_return_scan' => $waybill->has_return_scan,
                'total_delivered_waybills' => $total_delivered_waybills,
                'total_delivery_commission' => $total_delivery_commission,
                'cod_to_remit' => $cod_to_remit
            ];
        }

        return $result;
    }



    public function getPlaces(){
        return  Place::join('states', 'places.state_id', '=', 'states.id')
        ->select('places.name', 'states.name')
        ->get();
    }




   public function getPlacesAsString($state_id){

    $state_id = State::pluck('id');

    return  $this->places = Place::join('states', 'places.state_id', '=', 'states.id')
        ->select('places.name', 'states.name')
        ->where('places.state_id', $state_id)
        ->get();
   }


   



   public function getPlacesByStateId($state_id){
    foreach($this->getPlacesAsString($state_id) as $place)
    {
        $listOfPlaces = $place['place.name'] ." ". $place['state.name'];
        //--------noted use ---- instead of "  "
    }

         echo implode(', ', $listOfPlaces);

   }



}
