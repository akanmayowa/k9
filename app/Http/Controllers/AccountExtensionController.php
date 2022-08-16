<?php

namespace App\Http\Controllers;

use App\Place;
use App\state;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AccountExtensionController extends Controller
{


   public $stateById = array();
    public $places;


    //  public function dummyDataForDatatables(){
    //     //pickup
    // $pickups = [
    //          ['total_number_of_pickup' => 44, 'total_amount_pickup' =>330,000,'created_at' => "2022-03-01 07:57:15"],
    //          ['total_number_of_pickup' => 33, 'total_amount_pickup' =>3420,000,'created_at' => "2022-03-01 07:57:15"],
    //          ['total_number_of_pickup' => 55, 'total_amount_pickup' =>342200,000,'created_at' => "2022-03-01 07:57:15"],
    //          ['total_number_of_pickup' => 33, 'total_amount_pickup' =>222,000,'created_at' => "2022-03-01 07:57:15"],
    //          ['total_number_of_pickup' => 22, 'total_amount_pickup' =>555,000,'created_at' => "2022-03-02 07:57:15"],
    //          ['total_number_of_pickup' => 222, 'total_amount_pickup' =>444,444,'created_at' => "2022-03-02 07:57:15"],
    //          ['total_number_of_pickup' => 55, 'total_amount_pickup' =>211,222,'created_at' => "2022-03-02 07:57:15"],
    //          ['total_number_of_pickup' => 1234, 'total_amount_pickup' =>34455,00,'created_at' => "2022-03-02 07:57:15"],
    //          ['total_number_of_pickup' => 898, 'total_amount_pickup' =>5555,00,'created_at' => "2022-03-02 07:57:15"],
    //          ['total_number_of_pickup' => 4422, 'total_amount_pickup' =>4422,00,'created_at' => "2022-03-02 07:57:15"],
    //         ];
    //      return view('home', compact('pickups'));
    //  }


//    public function getPlaces() {
//     $places = Place::all()->pluck('id', 'state_id');
//       print_r($places);
//       $stateId = State::select()
// }



    public function index()
    {
//        return $this->getPlaces();
       return $this->getPlacesAsString(25);
        //return $this->getPlacesByStateId([1,25]);
    }


     public function getPlaces(){
        return Place::join('states', 'places.state_id', '=', 'states.id')
        ->select('places.name', 'states.name')
        ->get();
    }


   public function getPlacesAsString($state_id){

        $places =  Place::select('places.name')->where('places.state_id',$state_id)->get();
             return  $places->implode('name', '|');
   }


            public function getPlacesByStateId($stateById){
                 $places = Place::select('places.name')->whereIn('places.state_id',$stateById)->get();
                return  $places->implode('name', '|');
            }




            public function synchronize(){

                $tables = [
                    [ 'name' => 'sites'],
                    [ 'name' => 'employees']
                ];

                return view('synchronize',compact('tables'));
            }



}
