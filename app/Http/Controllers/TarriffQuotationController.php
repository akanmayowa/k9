<?php

namespace App\Http\Controllers;

use App\Services\TarriffQuotationServices;
use App\TarriffZonning;
use Exception;
use Illuminate\Http\Request;

class TarriffQuotationController extends Controller
{
    public $tarriffQuotationServices = null;

    public function __construct(TarriffQuotationServices $tarriffQuotationServices)
    {
        $this->middleware('auth');
        $this->tarriffQuotationServices = $tarriffQuotationServices;
    }

    public function index(){

        $locations = $this->tarriffQuotationServices->getLocations();
        $forwading_locations =  $this->tarriffQuotationServices->getForwardingLocations();
        return view('tarriff-quotation', compact('locations', 'forwading_locations'));
    }

    public function getTarriffQuote($departure_location_id, $destination_location_id, $forwading_location_id, $percentage_discount, $weight)
    {
        return $this->getTarriffQuotev2($departure_location_id, $destination_location_id, $forwading_location_id, $percentage_discount, $weight);
    }

    public function getTarriffQuotev2($departure_location_id, $destination_location_id, $forwading_location_id, $percentage_discount, $weight)
    {
        $forwading_location_id = ($forwading_location_id == 0) ? null : $forwading_location_id;
        $percentage_discount = ($percentage_discount == 0) ? null : $percentage_discount;
        $result =  $this->tarriffQuotationServices->getTarriffv2([
            'departure_location_id' => $departure_location_id,
            'forwading_location_id' => $forwading_location_id,
            'destination_location_id' => $destination_location_id,
            'percentage_discount' => $percentage_discount,
            'weight' => $weight
        ]);
        return json_encode($result);
    }

    public function getTarriff()
    {
        $weight = (float)request()->input('weight');
        $departure_location_id = (int)request()->input('departure_location_id');
        $destination_location_id = (int)request()->input('destination_location_id');
        $zone = TarriffZonning::where(['departure_location_id'=> $departure_location_id, 'destination_location_id' => $destination_location_id])->get();

        // dd($zone[0]->zone_id);
        $tarriff =  $this->tarriffQuotationServices->getTarriff(['zone_id' => (int)$zone[0]->zone_id, 'weight' => (float)$weight]);

      dd($tarriff);
        // return $tarriff;
    }



    public function getForwardingLocations($location_id)
    {
        try
        {
            $forwading_locations =  $this->tarriffQuotationServices->getForwardingLocationsFor((int)$location_id);
            return response()->json(['success' => true, 'message' => 'Forward locations retrived successfully', 'data' => $forwading_locations]);
        }
        catch(Exception $ex)
        {
             return response()->json(['success' => false, 'message' => 'Oops, Could not retreive forward locations']);
        }
    }


    public function getExpressTarriffs()
    {
        try
        {

            return response()->json(['success' => true, 'message' => 'tarriffs retrived successfully', 'data' =>$this->tarriffQuotationServices->getTarriffs() ]);
        }
        catch(Exception $ex)
        {
             return response()->json(['success' => false, 'message' => 'Oops, Could not tarriffs']);
        }

    }

    public function tarriff()
    {
        return view('tarriff');
    }

    public function zonnings()
    {
        return view('zonning');
    }


    public function getZonnings()
    {

        try
        {

            return response()->json(['success' => true, 'message' => 'zonnings retrived successfully', 'data' =>$this->tarriffQuotationServices->getTarriffZonnings() ]);
        }
        catch(Exception $ex)
        {
             return response()->json(['success' => false, 'message' => 'Oops, Could not zonnings']);
        }
    }


}
