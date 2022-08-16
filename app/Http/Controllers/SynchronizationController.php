<?php

namespace App\Http\Controllers;


use App\Services\K9Services;
use Illuminate\Http\Request;
use App\Services\SiteServices;
use Yajra\DataTables\DataTables;
use App\Services\AccountServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class SynchronizationController extends Controller
{
    public $synchronizer = null;
    public $user;

    public function __construct(K9Services $k9DatabaseServices)
    {
        $this->synchronizer = $k9DatabaseServices;
    }

    // public function index(K9DatabaseServices $k9DatabaseServices)
    // {
    //     $this->synchronizer = $k9DatabaseServices;
    //     return "You want to synchronize";
    // }



    public function synchronizeDB($table_name)
    {
        // $table_name = request()->input("table_name");

        if($table_name == "employees")
        {
            $result  = $this->synchronizer->synchronizeEmployeeTable(auth()->user());
            return response()->json($result);

        }

        if($table_name == "sites")
        {
            $result  = $this->synchronizer->synchronizeSiteTable(auth()->user());
            return response()->json($result);
        }

        return  response()->json("Please provide a valid table name");

    }



    // public function synchronizeDBemployees()
    // {
    //      $result = $this->synchronizer->synchronizeEmployeeTableV2();
    //      if($result){
    //         Session::flash('error');
    //         return view('synchronize');
    //      }

    //          Session::flash('success');
    //          return  view('synchronize.index');
    // }


    // public function synchronizeDBsites()
    // {
    //     $result = $this->synchronizer->synchronizeSiteTable();
    //       if(!$result){
    //         Session::flash('error');
    //         return redirect('synchronize/index');
    //     }
    //     Session::flash('success');
    //     return redirect('synchronize/index');
    // }



    public function index(){
        return view('synchronize');
    }

}
