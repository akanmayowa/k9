<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TarriffZonning;
use App\Services\TarriffQuotationServices;
use App\Tarriff;


class TarrrifController extends Controller
{

    public $tarriffQuotationServices = null;
     public function __construct(TarriffQuotationServices $tarriffQuotationServices)
     {
    //    // $this->middleware('auth');
         $this->tarriffQuotationServices = $tarriffQuotationServices;
     }


    public function getTarriff(Request $request){
    
     //   return response()->json(['foo'=>'bar']);
      $request->validate([
        "weight" => 'required|numeric',
        "departure_state" => "required|integer",
        "destination_state" => "required|integer",
        ]);

        $weight = $request->weight;
        $departure_location_id = $request->departure_state;
        $destination_location_id = $request->destination_state;

        $zone = TarriffZonning::where(['departure_location_id'=> $departure_location_id, 'destination_location_id' => $destination_location_id])->get();
        $tarriff =  $this->tarriffQuotationServices->getTarriff(['zone_id' => (int)$zone[0]->zone_id, 'weight' => $weight]);


        return response()->json([
            'status' => true,
            'amount in cash' => $tarriff,
            'the weight you entered is' => $weight
        ], 201);

        
    }
}
