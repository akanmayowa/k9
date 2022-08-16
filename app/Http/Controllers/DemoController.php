<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountServices;
use App\User;
use League\Csv\CharsetConverter;


class DemoController extends Controller
{
    public $accountServices;

    public function __construct(AccountServices $accountServices){
        $this->accountServices = $accountServices;
    }


   public function index(){
   // $user = User::all();
    return $this->accountServices->updateTarriffLocationZones();
   }

   public function useCsv() {
    CharsetConverter::register();
    $resource = fopen('/path/to/my/file', 'r');
    $filter = stream_filter_append(
        $resource,
        CharsetConverter::getFiltername('utf-8', 'iso-8859-15'),
        STREAM_FILTER_READ
    );
    while (false !== ($record = fgetcsv($resource))) {
        //$record is correctly encoded
    }

   }


}
