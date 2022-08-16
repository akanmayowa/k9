<?php

namespace App\Services;

use App\EcommerceTariff;
use App\Enums\TarriffZone;
use App\TarriffZonning;
use App\TarriffLocation;
use App\ForwardingCharge;
use App\Tarriff;
use Exception;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class TarriffQuotationServices {

        public function __construct()
        {
            //  echo "tarriff service created";
        }

        public function getLocations()
        {
            return TarriffLocation::pluck('name', 'id');
        }

        public function getForwardingLocations()
        {
                 $forwardingLocations = ForwardingCharge::all();
                 return $forwardingLocations;
        }
        //Maybe change the name to forwardingMap not forwap charges
        public function getForwardingLocationsFor($location_id)
        {
            return ForwardingCharge::where('location_id', $location_id)->get();
        }

        public function getTarriffv2($data)
        {
            //Can we not just use the ID's directly and throw exception on null ?
            $departure_location = TarriffLocation::find($data['departure_location_id']);
            $destination_location =  TarriffLocation::find($data['destination_location_id']);
            $forwading_location = $data['forwading_location_id'];

            $forwarding_charge = 0;
            if(!is_null($forwading_location))
            {
              $forwading_data =  ForwardingCharge::where('id', $forwading_location)->first();
              $forwarding_charge = $forwading_data->cost_in_cents * 100; //make sure you do the over 100 division
            }


            $zone = TarriffZonning::where(['departure_location_id'=> $departure_location->id, 'destination_location_id' => $destination_location->id])->get();
            $tarriff =  $this->getTarriff(['zone_id' => (int)$zone[0]->zone_id, 'weight' => (float)$data['weight']]);

            //percentage_discount
            $percentage_discount = 0;
            if(!is_null($percentage_discount))
            {
                $percentage_discount = ($tarriff * (float) $data['percentage_discount'] / 100);
            }

            $new_charge = ($tarriff - $percentage_discount) + $forwarding_charge;

            return ['tarriff' => $new_charge,
             'departure_location' => $departure_location->name,
            'destination_location' => $destination_location->name,
            'zone' => (int)$zone[0]->zone_id,
            'weight' => (float)$data['weight'],
            'percentage_discount' => $percentage_discount,
            'forwarding_charge' => $forwarding_charge
        ];
        }

        public function getTarriff($data)
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
                throw new Exception("Unknown Zone Supplied");
            }

            //round to the nearest 0.5

            $extra_tarriff = 0;

            if($weight > 0 && $weight < 20)
            {
                $rounded_weight = ceil($weight*2)/2;

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


            }
            else {
                throw new Exception("Invalid Weight supplied");
            }

            $tarriff_in_cents =  Tarriff::where('weight_start', '=', $rounded_weight)->first()->$column_name;
            $tarriff_in_naira = $tarriff_in_cents * 100; //covert from kobo to naira, cents is suppossed to be cents


            return $tarriff_in_naira + $extra_tarriff;
        }


        public function getRoundedWeight($weight)
        {
            if($weight < 0)
            {
                throw new InvalidArgumentException("Shipmemnt weight cannot be negative.\n kinldy provide a valid shipment weight");
            }

            echo "weight: " . $weight . "\n";
            echo "PW: " . (float)$weight . "\n";
            $rounded_weight = "";

            if( strpos($weight, ".") !== false ) {
                $whole = "";
                $decimal = "";

                list($whole, $decimal) = explode('.', $weight);

                if($decimal >= 5)
                {
                    $rounded_weight  = "$whole.5";
                }
                else
                {
                    $rounded_weight = "$whole.0";
                }

                echo "d: " . $decimal ."\n";
                echo "w: " . $whole ."\n";
                echo "wght: " . $rounded_weight;

           }
           else
           {
                $rounded_weight = "$weight.0";
                echo "wght: " . $rounded_weight;

           }

            // return $weight;
        }



        public function getRoundedWeightV2($weight)
        {
            // highest number smaller than your value rounded to 0.5
            $rounded_weight = floor($weight*2)/2;
            $max_weight = 20;
            if($weight > $max_weight)
            {
                $extra_weight = $weight - $max_weight;
                $zero_point_five_in_extra_weight = $extra_weight / 0.5;
            }
            echo $rounded_weight;
        }



        public function getTarriffs()
        {
           return  Tarriff::all();
        }


        public function getTarriffZonnings()
        {
            return TarriffZonning::join('tarriff_locations as tl', 'tl.id', '=', 'departure_location_id')->join('tarriff_locations as tl2', 'tl2.id', '=', 'destination_location_id')->orderBy('tl.id')->get(['tl.name as from','tl2.name as to', 'zone_id']);
        }



        public function getEcommerceTarriffs()
        {
            return EcommerceTariff::all();
        }


}
