<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\SiteServices;
use Yajra\DataTables\DataTables;
use App\Services\AccountServices;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public $accountServices = null;
    public $siteServices = null;
    public function __construct(AccountServices $accountServices, SiteServices $siteServices)
    {
        $this->middleware('auth');
        $this->accountServices = $accountServices;
        $this->siteServices = $siteServices;
        $this->current_user = Auth::user();
    }

    public function index()
    {
        return   $this->getFeeData();
    }

    public function getFeeData()
    {


        if(request()->ajax())
        {
            $filters = [
                'start_date'   =>  	Carbon::parse(request()->input('start_date')),
                'end_date'      =>  Carbon::parse(request()->input('end_date')),
                'site_id' => (int)request()->input('site_id'),
                'cod' => (int)request()->input('cod'),
                // 'next_site_id' => (int)request()->input('next_site_id'),
                // 'user' => Auth::user()
            ];

            $waybills_query = $this->accountServices->getFeeData($filters);

            return Datatables::of($waybills_query)
                ->addIndexColumn()
                ->addColumn('waybill_number', function ($waybill_info) {
                    $html = $waybill_info['waybill_number'];
                    return $html;
                })
                ->addColumn('collection_date', function ($waybill_info) {


                    return Carbon::parse($waybill_info['collection_date'])->format('Y-M-d');

                })
                ->addColumn('site', function ($waybill_info) {
                    $html = $waybill_info['destination_site']->name;
                    return $html;
                })
                ->addColumn('zone', function ($waybill_info) {
                    $html = $waybill_info['zone'];
                    return $html;
                })
                ->addColumn('freight', function ($waybill_info) {
                    $html = $waybill_info['freight'];
                    return $html;
                })
                ->addColumn('cod', function ($waybill_info) {
                    $html = $waybill_info['COD'];
                    return $html;
                })
                ->addColumn('weight', function ($waybill_info) {
                    $html = $waybill_info['weight'].' kg';
                    return $html;
                })
                ->addColumn('transfer_fee', function ($waybill_info) {
                    $html = $waybill_info['transfer_fee'];
                    return $html;
                })
                ->addColumn('delivery_fee', function ($waybill_info) {
                    $html = $waybill_info['delivery_fee'];
                    return $html;
                })
                ->addColumn('pickup_fee', function ($waybill_info) {
                    $html = $waybill_info['pickup_fee'];
                    return $html;
                })


                ->rawColumns(['waybill_number', 'route', 'zone', 'freight', 'cod', 'weight', 'transfer_fee', 'delivery_fee', 'pickup_fee'])
                ->make(true);
        }
        else
        {
            $sites =  $this->siteServices->getFranchisees();
            return view('account', compact('sites'));
        }
    }


    public function pickUpParcelsCommission()
    {
        if(request()->ajax())
        {
            $filters = [
                'start_date'   =>  	Carbon::parse(request()->input('start_date')),
                'end_date'      =>  Carbon::parse(request()->input('end_date')),
                'site_id' => (int)request()->input('site_id'),
                'cod' => (int)request()->input('cod'),
                // 'next_site_id' => (int)request()->input('next_site_id'),
                // 'user' => Auth::user()
            ];

            $waybills_query = $this->accountServices->getPickUpCommission($filters);
            return Datatables::of($waybills_query)
                ->addIndexColumn()
                ->addColumn('waybill_number', function ($waybill_info) {
                    $html = $waybill_info['waybill_number'];
                    return $html;
                })
                ->addColumn('pickup_date', function ($waybill_info) {


                    return Carbon::parse($waybill_info['waybill']->SCAN_DATE)->format('Y-M-d');

                })
                ->addColumn('pickup_site', function ($waybill_info) {
                    $html = $waybill_info['departure_site']->name;
                    return $html;
                })
                ->addColumn('zone', function ($waybill_info) {
                    $html = $waybill_info['zone'];
                    return $html;
                })
                ->addColumn('freight', function ($waybill_info) {
                    $html = $waybill_info['freight'];
                    return $html;
                })
                ->addColumn('cod', function ($waybill_info) {
                    $html = $waybill_info['COD'];
                    return $html;
                })
                ->addColumn('weight', function ($waybill_info) {
                    $html = $waybill_info['weight'].' kg';
                    return $html;
                })
                ->addColumn('transfer_fee', function ($waybill_info) {
                    $html = $waybill_info['transfer_fee'];
                    return $html;
                })
                ->addColumn('delivery_fee', function ($waybill_info) {
                    $html = $waybill_info['delivery_fee'];
                    return $html;
                })
                ->addColumn('pickup_fee', function ($waybill_info) {
                    $html = $waybill_info['pickup_fee'];
                    return $html;
                })
                ->addColumn('delivery_fee_plus_transfer_fee', function ($waybill_info) {
                    $html = $waybill_info['delivery_fee_plus_transfer_fee'];
                    return $html;
                })
                ->with('count', function(){
                    return 222222222222222222;
                })
                // ->addColumn('commission', function ($waybill_info) {
                //     $html = $waybill_info['commission'];
                //     return $html;
                // })


                ->rawColumns(['waybill_number', 'route', 'zone', 'freight', 'cod', 'weight', 'transfer_fee', 'delivery_fee', 'pickup_fee'])
                ->make(true);
        }
        else
        {
            $sites =  $this->siteServices->getFranchisees();
            return view('pick-up-parcels-commission', compact('sites'));
        }
    }

    public function commissionStats()
    {

        if(request()->ajax())
        {
           $filters2 = ['start_date'=> '2022-02-05' , 'end_date' => '2022-02-11', 'site_id' => 234132];
            // return response()->json("successsssss");
            $filters = [
                'start_date'   =>  	Carbon::parse(request()->input('start_date')),
                'end_date'      =>  Carbon::parse(request()->input('end_date')),
                'site_id' => (int)request()->input('site_id'),
                'cod' => (int)request()->input('cod'),
                // 'next_site_id' => (int)request()->input('next_site_id'),
                // 'user' => Auth::user()
            ];
            $stats = $this->accountServices->getCommissionStats($filters2);
            return response()->json(['success'=> true, 'filters' => $filters2, 'data' => $stats]);
            //Exception unkor ?

        }
        else
        {
            return response()->json("nothing for you");
            abort(404);
        }
    }

}
