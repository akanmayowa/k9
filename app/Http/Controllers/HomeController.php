<?php

namespace App\Http\Controllers;

use App\Site;
use App\User;
use App\Waybill;
use App\Manifest;
use Carbon\Carbon;
use App\Enums\WaybillStatus;
use Illuminate\Http\Request;
use App\Enums\ManifestStatus;
use App\Services\ManifestServices;
use App\Services\SiteServices;
use Illuminate\Support\Facades\Auth;
use App\Services\AccountServices;


class HomeController extends Controller
{
    public $manifest_services = null;
    public $site_services = null;
    public $accountServices = null;

    public function __construct(AccountServices $accountServices, SiteServices $site_services, ManifestServices $manifest_services)
    {
        $this->site_services = $site_services;
        $this->manifest_services = $manifest_services;
        $this->accountServices = $accountServices;

        $this->middleware('auth');
    }

    public function index()
    {
        // $escalation_interval = config('custom.escalation_interval_in_hours');

        // $date_considered = Carbon::today();

        // $overdue_date = \Carbon\Carbon::now()->subHours($escalation_interval);
        // $overdue_manifest_count = Manifest::where([
        //     ['next_site_id', '=', Auth::user()->site_id],
        //     ['created_at', '<=', $overdue_date],
        //     ['status', '=', 0] //0 In transit
        // ])->count();

        // //Null if user is not logged in
        // $dispatched_manifest_count = Manifest::whereDate('created_at', $date_considered)->where([
        //     ['scan_site_id', '=', Auth::user()->site_id],
        //     ['status', '=', ManifestStatus::IN_TRANSIT]
        //     //0 In transit
        // ])->count();

        // $incoming_manifest_count = Manifest::whereDate('created_at', $date_considered)->where([
        //     ['status', '=', 0], //UnAcknowledged
        //     ['next_site_id', '=', Auth::user()->site_id] //0 In transit
        // ])->count();


        // $acknowleged_manifest_count = Manifest::whereDate('created_at', $date_considered)->where([
        //     ['status', '=', 1], //UnAcknowledged
        //     ['scan_site_id', '=', Auth::user()->site_id] //0 In transit

        // ])->count();

        // $flagged_manifest_count = Manifest::whereDate('created_at', $date_considered)->where([
        //     ['is_flagged', '=', 1], //UnAcknowledged
        //     ['scan_site_id', '=', Auth::user()->site_id] //0 In transit
        // ])->count();

        // //use compact here jor
        // $data = [
        //     'overdue_manifest_count' => $overdue_manifest_count,
        //     'dispatched_manifest_count' => $dispatched_manifest_count,
        //     'incoming_manifest_count' => $incoming_manifest_count,
        //     'acknowleged_manifest_count' => $acknowleged_manifest_count,
        //     'flagged_manifest_count' => $flagged_manifest_count
        // ];



        // $waybills_stat['incoming_waybills_count'] = Waybill::whereDate('created_at', $date_considered)->where([
        //     ['status', '=', WaybillStatus::IN_TRANSIT],
        //     ['next_site_id', '=', Auth::user()->site_id] //0 In transit
        // ])->count();

        // $waybills_stat['dispatched_waybills_count'] = Waybill::whereDate('created_at', $date_considered)->where([
        //     ['scan_site_id', '=', Auth::user()->site_id],
        //     ['status', '!=', WaybillStatus::CANCELLED]
        //     //0 In transit
        // ])->count();


        // $waybills_stat['acknowleged_waybills_count'] =  Waybill::whereDate('created_at', $date_considered)->where([
        //     ['status', '=', WaybillStatus::ACKNOWLEDGED],
        //     ['scan_site_id', '=', Auth::user()->site_id] //0 In transit
        // ])->count();



        // // dd($data);

        // $sites = $this->site_services->getAllSites();

        // $from_sites = [];
        // $to_sites = [];
        // $user_id = Auth::user()->id;
        // $user = User::find($user_id);

        // if ($user->hasAnyRole(['Quality Control Personnel'])) {
        //     $from_sites =  $this->site_services->getAllSitesV2();
        //     $to_sites = $this->site_services->getAllSitesV2();
        // } else {
        //     $from_sites =  Site::where('id', Auth::user()->site->id)->where('can_dispatch_or_acknowledge_manifest', '!=', 0)->pluck('name', 'id');
        //     $to_sites =  $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
        // }

        // return view('home', compact('data', 'waybills_stat', 'date_considered', 'sites', 'from_sites', 'to_sites'));

        //return view('home');
         return $this->dummyDataForDatatables();
    }









    public function dummyDataForDatatables(){
        //pickup
    $pickups = [
             ['pickup' => 'WYB444444', 'total_amount_pickup' =>330,000,'created_at' => "2022-03-01 07:57:15", 'delivery' =>'WY2344', 'total_amount_delivery' =>555,222,        'cod' =>'WY5744',       'return_waybill' => 33, 'duplicate_waybill'=>44, 'invalid_waybill' =>3],
             ['pickup' => 'WYB33444444', 'total_amount_pickup' =>3420,000,'created_at' => "2022-03-01 07:57:15", 'delivery' =>'WY854455', 'total_amount_delivery' =>555,222,   'cod' =>'WY5744',         'return_waybill' => 33, 'duplicate_waybill'=>44, 'invalid_waybill' =>3],
             ['pickup' => 'WYB5544444', 'total_amount_pickup' =>342200,000,'created_at' => "2022-03-01 07:57:15", 'delivery' =>'WY84477', 'total_amount_delivery' =>555,222,   'cod' =>'WY5744',          'return_waybill' => 33, 'duplicate_waybill'=>44, 'invalid_waybill' =>3],
             ['pickup' => 'WYB3344444', 'total_amount_pickup' =>222,000,'created_at' => "2022-03-01 07:57:15", 'delivery' =>'WYB33444444435', 'total_amount_delivery' =>555,222, 'cod' =>'WY5744',           'return_waybill' => 33, 'duplicate_waybill'=>44, 'invalid_waybill' =>3],
             ['pickup' => 'WYB224444', 'total_amount_pickup' =>555,000,'created_at' => "2022-03-02 07:57:15", 'delivery' =>'WYB554444', 'total_amount_delivery' =>555,222,       'cod' =>'WY5744',            'return_waybill' => 33, 'duplicate_waybill'=>44, 'invalid_waybill' =>3],
             ['pickup' => 'WYB2224444', 'total_amount_pickup' =>444,444,'created_at' => "2022-03-02 07:57:15", 'delivery' =>'WYB2224444', 'total_amount_delivery' =>555,222,    'cod' =>'WY5744',                'return_waybill' => 33, 'duplicate_waybill'=>44, 'invalid_waybill' =>3],
             ['pickup' => 'WYB554444', 'total_amount_pickup' =>211,222,'created_at' => "2022-03-02 07:57:15", 'delivery' =>'WYB4244', 'total_amount_delivery' =>555,222,        'cod' =>'WY5744',              'return_waybill' => 33, 'duplicate_waybill'=>44, 'invalid_waybill' =>3],
             ['pickup' => 'WYB1234444', 'total_amount_pickup' =>34455,00,'created_at' => "2022-03-02 07:57:15", 'delivery' =>'WYB898444', 'total_amount_delivery' =>555,222,    'cod' =>'WY5744',                'return_waybill' => 33, 'duplicate_waybill'=>44, 'invalid_waybill' =>3],
             ['pickup' => 'WYB898444', 'total_amount_pickup' =>5555,00,'created_at' => "2022-03-02 07:57:15", 'delivery' =>'WY644', 'total_amount_delivery' =>555,222,        'cod' =>'WY5744',                'return_waybill' => 33, 'duplicate_waybill'=>44, 'invalid_waybill' =>3],
             ['pickup' => 'WYB442222', 'total_amount_pickup' =>4422,00,'created_at' => "2022-03-02 07:57:15", 'delivery' =>'WY5744', 'total_amount_delivery' =>555,222,     'cod' =>'WY5744',                    'return_waybill' => 33, 'duplicate_waybill'=>44, 'invalid_waybill' =>3],
            ];

            $delivery = [];
            $cods = [];

            
         return view('home')
                ->with('pickups', $pickups)
                ->with('deliveries', $deliveries)
                ->with('cods', $cods);
     }





    // public function getDateRangeSearchForDashboard(){
    //     $start_date = Carbon::parse(request()->input('start_date'));
    //     $end_date = Carbon::parse(request()->input('end_date'));

    //     if($start_date && $end_date){
    //         $filters = [
    //             'start_date'   =>  	Carbon::parse(request()->input('start_date')),
    //             'end_date'      =>  Carbon::parse(request()->input('end_date')),

    //         ];
    //         $query_for_dashboard_card_period_filter = $this->accountServices->getDateRangeSearchForDashboard($filters);
    //         return view('home', compact('query_for_dashboard_card_period_filter'));
    //     }
    //     else{
    //         $query_for_dashboard_card_period_filter = $this->accountServices->getDateRangeSearchForDashboard();
    //         return view('home')-with('query_for_dashboard_card_period_filters', $query_for_dashboard_card_period_filter);
    //     }
    //   }



    public function about()
    {
        return view('about');
    }


    public function updateDashBoard()
    {
        $start_date = Carbon::parse(request()->input('start_date'));
        $end_date = Carbon::parse(request()->input('end_date'));
        $site_id = (int)request()->input('scan_site_id');
        $to_site = (int)request()->input('next_site_id');


        $escalation_interval = config('custom.escalation_interval_in_hours');

        $date_considered = Carbon::today();

        $overdue_date = \Carbon\Carbon::now()->subHours($escalation_interval);
        $overdue_manifest_count = Manifest::where([
            ['next_site_id', '=', $site_id],
            ['created_at', '<=', $overdue_date],
            ['status', '=', 0] //0 In transit
        ])->count();

        //Null if user is not logged in
        $dispatched_manifest_count = Manifest::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->where([
                ['scan_site_id', '=', $site_id]
            ])->count();

        $incoming_manifest_count = Manifest::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->where([
                ['status', '=', 0], //UnAcknowledged
                ['next_site_id', '=', $site_id] //0 In transit
            ])->count();


        $acknowleged_manifest_count = Manifest::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->where([
                ['status', '=', 1],
                ['next_site_id', '=', $site_id] //0 In transit
            ])->count();


            $partially_acknowleged_manifest_count = Manifest::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->where([
                ['status', '=', ManifestStatus::PARTIALLY_RECEIVED],
                ['next_site_id', '=', $site_id] //0 In transit
            ])->count();

        $flagged_manifest_count = Manifest::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->where([
                ['is_flagged', '=', 1], //UnAcknowledged
                ['next_site_id', '=', $site_id] //0 In transit
            ])->count();

        //use compact here jor
        $data = [
            'overdue_manifest_count' => $overdue_manifest_count,
            'dispatched_manifest_count' => $dispatched_manifest_count,
            'incoming_manifest_count' => $incoming_manifest_count,
            'acknowleged_manifest_count' => $acknowleged_manifest_count,
            'flagged_manifest_count' => $flagged_manifest_count,
            'partially_acknowleged_manifest_count' => $partially_acknowleged_manifest_count
        ];



        $data['incoming_waybills_count'] = Waybill::whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->where([
            ['status', '=', WaybillStatus::IN_TRANSIT],
            ['next_site_id', '=',$site_id] //0 In transit
        ])->count();

        $data['dispatched_waybills_count'] = Waybill::whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->where([
            ['scan_site_id', '=', $site_id],
            ['status', '!=', WaybillStatus::CANCELLED]
            //0 In transit
        ])->count();


        $data['acknowleged_waybills_count'] =  Waybill::whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->where([
            ['status', '=', WaybillStatus::ACKNOWLEDGED],
            ['next_site_id', '=',$site_id] //0 In transit
        ])->count();


        $data['partially_acknowleged_waybills_count'] = Waybill::where('status', WaybillStatus::IN_TRANSIT)->whereHas('manifest', function ($query) use($site_id) {
            return $query->where('status', '=', ManifestStatus::PARTIALLY_RECEIVED)->where('next_site_id', $site_id);
        })->count();


        return response()->json(['success'=> true, 'start_date' => $start_date, 'end_date' => $end_date, 'from_site' => $site_id, 'to_site' => $to_site, 'data' => $data]);

    }

                }


