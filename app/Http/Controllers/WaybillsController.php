<?php

namespace App\Http\Controllers;

use App\Site;
use App\User;
use App\Waybill;
use App\Enums\WaybillStatus;
use App\K9DepartureScan;
use Illuminate\Http\Request;
use App\Services\SiteServices;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use App\Services\WaybillServices;
use App\Services\ManifestServices;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WaybillsController extends Controller
{

    public $waybill_services = null;
    public $site_services = null;
    public $manifest_services = null;

    public function __construct(ManifestServices $manifest_services, WaybillServices $waybill_services, SiteServices $site_services)
    {
        $this->middleware('auth');
        $this->manifest_services = $manifest_services;
        $this->waybill_services = $waybill_services;
        $this->site_services = $site_services;
    }

    //Shows the Listing of all Waybills
    public function index()
    {

        $sites = $this->site_services->getAllSites();

        $from_sites = [];
        $to_sites = [];
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        if ($user->hasAnyRole(['Quality Control Personnel'])) {
            $from_sites =  $this->site_services->getAllSitesV2(); // you can still use this list for to
            $to_sites = $this->site_services->getAllSitesV2();
        } else {
            $from_sites =  Site::where('id', Auth::user()->site->id)->where('can_dispatch_or_acknowledge_manifest', '!=', 0)->pluck('name', 'id');
            $to_sites =  $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
        }

        return view('waybills.index', compact('sites', 'from_sites', 'to_sites'));
    }

    public function getwaybills()
    {

        $filters = [
            'status' => (int)request()->input('status'),
            'scan_site_id' => (int)request()->input('scan_site_id'),
            'next_site_id' => (int)request()->input('next_site_id'),
            'start_date' => Carbon::parse(request()->input('start_date')),
            'end_date' => Carbon::parse(request()->input('end_date'))
        ];

        $waybills_query = $this->waybill_services->getWaybills($filters);
        return Datatables::of($waybills_query)
            ->addIndexColumn()
            ->addColumn('route', function (Waybill $waybill) {
                $html = '<span style="color:coral"> From </span><span class="text-muted">' . $waybill->scan_site->name . '<span style="color:coral"> To </span>' . $waybill->next_site->name . '</span>';
                return $html;
            })
            ->addColumn('dispatched', function (waybill $waybill) {
                $html = '<span class="">' . $waybill->created_at->diffForHumans() . '</span>
            <div class="text-green">' . $waybill->created_at->format('Y-m-d , g:i A') . '</div>';
                return $html;
            })
            ->addColumn('status_label', function (Waybill $waybill) {
                $html = "";
                if ($waybill->status === WaybillStatus::IN_TRANSIT) {

                    $html .= '<span class="badge badge-light">' . WaybillStatus::STATUS_TEXT[WaybillStatus::IN_TRANSIT] . '<span>';
                } else if ($waybill->status === WaybillStatus::ACKNOWLEDGED) {
                    $html .= '<span class="badge badge-success">' . WaybillStatus::STATUS_TEXT[WaybillStatus::ACKNOWLEDGED] . '</span>';
                } else if ($waybill->status === WaybillStatus::CANCELLED) {
                    $html .= '<span class="badge badge-primary">' . WaybillStatus::STATUS_TEXT[WaybillStatus::CANCELLED] . '</span>';
                } else if ($waybill->status === WaybillStatus::PENDING) {
                    $html .= '<span class="badge badge-default">' . WaybillStatus::STATUS_TEXT[\App\Enums\WaybillStatus::PENDING] . '</span>';
                } else {
                    $html .= '<span class="badge badge-dark">Unknown</span>';
                }

                return $html;
            })
            ->addColumn('action', function (Waybill $waybill) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item change-group" href="waybills/' . $waybill->id . '/track">
                        <i class="fas fa-info-circle"></i>
                        <span class="nav-link-text">Track</span>
                      </a>
					   <a class="dropdown-item change-group" href="waybills/' . $waybill->id . '/track-k9">
                        <i class="fas fa-info-circle"></i>
                        <span class="nav-link-text">Track on K9</span>
                      </a>';

                $html .= '</a></div></div>';

                return $html;
            })
            ->rawColumns(['route', 'dispatched', 'status_label', 'total_parcels', 'action'])
            ->make(true);
    }



    public function track()
    {

        if(request()->ajax())
        {
           try {
               /*- one or more waybills
                 - trim whitespace ?
                 - invalid waybill ?

               */
                $waybill_numbers_string = request()->input('waybill_number');
                $waybill_numbers = explode(" ", $waybill_numbers_string);
                $records = Waybill::with('scan_site:id,name', 'next_site:id,name', 'created_by_user:id,name', 'acknowledged_by_user:id,name')->whereIn('id', $waybill_numbers)->get();
                return  response()->json(['success' => true, 'message' => 'records retrieved successfully', 'data' => $records, 'waybill_numbers'=> $waybill_numbers]);
           }
           catch(Exception $ex)
           {
                return  response()->json(['success' => false, 'message' => 'could not retrieve records', 'data' => null]);
           }
        }
        else
        {

            return view('track');
        }

    }


    public function trackOnK9()
    {

        if(request()->ajax())
        {
           try {
               /*- one or more waybills
                 - trim whitespace ?
                 - invalid waybill ?

               */
                $waybill_numbers_string = request()->input('waybill_number');
                $waybill_numbers = explode(" ", $waybill_numbers_string);
                $result = $this->waybill_services->trackOnK9($waybill_numbers);
                // return [];
               return  response()->json(['success' => true, 'message' => 'records retrieved successfully', 'data' => $result]);
           }
           catch(Exception $ex)
           {
                return  response()->json(['success' => false, 'message' => $ex->getMessage(),  'data' => null]);
           }
        }
        else
        {

            return view('track-k9');
        }

    }






    public function viewAcknowledgedWaybills()
    {
        //TO DO
        // $waybills = $this->waybill_services->getAcknowledgedWaybill();
        // dd($manifests[0]->manifested);
        return view('waybills.acknowledged');
    }


    public function viewPendingWaybills()
    {
        return view('waybills.pending');
    }

    public function viewDispatchedWaybills()
    {
        $sites = $this->site_services->getAllSites();

        $from_sites = [];
        $to_sites = [];
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        if($user->hasAnyRole(['Quality Control Personnel']))
        {
                $from_sites =  $this->site_services->getAllSitesV2();
                $to_sites = $this->site_services->getAllSitesV2();
        }
        else
        {
          $from_sites =  Site::where('id', Auth::user()->site->id)->where('can_dispatch_or_acknowledge_manifest', '!=', 0)->pluck('name','id');
          $to_sites =  $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
        }

        return view('waybills.dispatched',compact( 'from_sites', 'to_sites'));
    }



    public function viewIncomingWaybills()
    {

        $sites = $this->site_services->getAllSites();

        $from_sites = [];
        $to_sites = [];
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        // if($user->hasAnyRole(['Quality Control Personnel']))
        // {
        //         $from_sites =  $this->site_services->getAllSitesV2();
        //         $to_sites = $this->site_services->getAllSitesV2();
        // }
        // else
        // {
        //   $from_sites =  Site::where('id', Auth::user()->site->id)->where('can_dispatch_or_acknowledge_manifest', '!=', 0)->pluck('name','id');
        //   $to_sites  =  $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
        // }


        // dd($from_sites, $to_sites, $user->getRoleNames());
        $manifests =[]; //$this->manifest_services->getDispatchedManifest();
        $from_sites = $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);

        return view('waybills.incoming',compact( 'from_sites', 'to_sites'));
    }


public function getDispatchedWaybills()
{

    $filters = [
        'status' => (int)request()->input('status'),
        'scan_site_id' => (int)request()->input('scan_site_id'),
        'next_site_id' => (int)request()->input('next_site_id'),
        'start_date' => Carbon::parse(request()->input('start_date')),
        'end_date' => Carbon::parse(request()->input('end_date')),
        'user' => Auth::user()
    ];

    $waybills_query = $this->waybill_services->getDispatchedWaybills($filters);
    return Datatables::of($waybills_query)
        ->addIndexColumn()
        ->addColumn('route', function (Waybill $waybill) {
            $html = '<span style="color:coral"> From </span><span class="text-muted">' . $waybill->manifest->scan_site->name . '<span style="color:coral"> To </span>' . $waybill->manifest->next_site->name . '</span>';
            return $html;

            // return "";
        })
        ->addColumn('dispatched', function (Waybill $waybill) {
            $html = '<span class="">' . $waybill->created_at->diffForHumans() . '</span>
        <div class="text-green">' . $waybill->created_at->format('Y-m-d , g:i A') . '</div>';
            return $html;
        })
        ->addColumn('status_label', function (Waybill $waybill) {
            $html = "";
            if ($waybill->status === WaybillStatus::IN_TRANSIT) {

                $html .= '<span class="badge badge-light">' . WaybillStatus::STATUS_TEXT[WaybillStatus::IN_TRANSIT] . '<span>';
            } else if ($waybill->status === WaybillStatus::ACKNOWLEDGED) {
                $html .= '<span class="badge badge-success">' . WaybillStatus::STATUS_TEXT[WaybillStatus::ACKNOWLEDGED] . '</span>';
            } else if ($waybill->status === WaybillStatus::CANCELLED) {
                $html .= '<span class="badge badge-primary">' . WaybillStatus::STATUS_TEXT[WaybillStatus::CANCELLED] . '</span>';
            } else if ($waybill->status === WaybillStatus::PENDING) {
                $html .= '<span class="badge badge-default">' . WaybillStatus::STATUS_TEXT[\App\Enums\WaybillStatus::PENDING] . '</span>';
            } else {
                $html .= '<span class="badge badge-dark">Unknown</span>';
            }

            return $html;
        })
        ->addColumn('action', function (Waybill $waybill) {
            $html = '<div class="dropdown">
            <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-h"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item change-group" href="waybills/' . $waybill->id . '/track">
                    <i class="fas fa-info-circle"></i>
                    <span class="nav-link-text">Track</span>
                  </a>
                   <a class="dropdown-item change-group" href="waybills/' . $waybill->id . '/track-k9">
                    <i class="fas fa-info-circle"></i>
                    <span class="nav-link-text">Track on K9</span>
                  </a>';

            $html .= '</a></div></div>';

            return $html;
        })
        ->rawColumns(['route', 'dispatched', 'status_label', 'total_parcels', 'action'])
        ->make(true);
}



public function getIncomingWaybills()
{

    $filters = [
        'status' => (int)request()->input('status'),
        'scan_site_id' => (int)request()->input('scan_site_id'),
        'next_site_id' => (int)request()->input('next_site_id'),
        'start_date' => Carbon::parse(request()->input('start_date')),
        'end_date' => Carbon::parse(request()->input('end_date')),
        'user' => Auth::user()
    ];

    $waybills_query = $this->waybill_services->getIncomingWaybills($filters);
    return Datatables::of($waybills_query)
        ->addIndexColumn()
        ->addColumn('route', function (Waybill $waybill) {
            $html = '<span style="color:coral"> From </span><span class="text-muted">' . $waybill->scan_site->name . '<span style="color:coral"> To </span>' . $waybill->next_site->name . '</span>';
            return $html;

            // return "";
        })
        ->addColumn('dispatched', function (Waybill $waybill) {
            $html = '<span class="">' . $waybill->created_at->diffForHumans() . '</span>
        <div class="text-green">' . $waybill->created_at->format('Y-m-d , g:i A') . '</div>';
            return $html;
        })
        ->addColumn('status_label', function (Waybill $waybill) {
            $html = "";
            if ($waybill->status === WaybillStatus::IN_TRANSIT) {

                $html .= '<span class="badge badge-light">' . WaybillStatus::STATUS_TEXT[WaybillStatus::IN_TRANSIT] . '<span>';
            } else if ($waybill->status === WaybillStatus::ACKNOWLEDGED) {
                $html .= '<span class="badge badge-success">' . WaybillStatus::STATUS_TEXT[WaybillStatus::ACKNOWLEDGED] . '</span>';
            } else if ($waybill->status === WaybillStatus::CANCELLED) {
                $html .= '<span class="badge badge-primary">' . WaybillStatus::STATUS_TEXT[WaybillStatus::CANCELLED] . '</span>';
            } else if ($waybill->status === WaybillStatus::PENDING) {
                $html .= '<span class="badge badge-default">' . WaybillStatus::STATUS_TEXT[\App\Enums\WaybillStatus::PENDING] . '</span>';
            } else {
                $html .= '<span class="badge badge-dark">Unknown</span>';
            }

            return $html;
        })
        ->addColumn('action', function (Waybill $waybill) {
            $html = '<div class="dropdown">
            <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-h"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item change-group" href="waybills/' . $waybill->id . '/track">
                    <i class="fas fa-info-circle"></i>
                    <span class="nav-link-text">Track</span>
                  </a>
                   <a class="dropdown-item change-group" href="waybills/' . $waybill->id . '/track-k9">
                    <i class="fas fa-info-circle"></i>
                    <span class="nav-link-text">Track on K9</span>
                  </a>';

            $html .= '</a></div></div>';

            return $html;
        })
        ->rawColumns(['route', 'dispatched', 'status_label', 'total_parcels', 'action'])
        ->make(true);
}


public function getPendingWaybills()
{

    $filters = [
        'status' => (int)request()->input('status'),
        'scan_site_id' => (int)request()->input('scan_site_id'),
        'next_site_id' => (int)request()->input('next_site_id'),
        'start_date' => Carbon::parse(request()->input('start_date')),
        'end_date' => Carbon::parse(request()->input('end_date')),
        'user' => Auth::user()
    ];

    $waybills_query = $this->waybill_services->getPendingWaybills($filters);
    return Datatables::of($waybills_query)
        ->addIndexColumn()
        ->addColumn('route', function (Waybill $waybill) {
            $html = '<span style="color:coral"> From </span><span class="text-muted">' . $waybill->manifest->scan_site->name . '<span style="color:coral"> To </span>' . $waybill->manifest->next_site->name . '</span>';
            return $html;
        })
        ->addColumn('dispatched', function (Waybill $waybill) {
            $html = '<span class="">' . $waybill->created_at->diffForHumans() . '</span>
        <div class="text-green">' . $waybill->created_at->format('Y-m-d , g:i A') . '</div>';
            return $html;
        })
        ->addColumn('status_label', function (Waybill $waybill) {
            $html = "";
            if ($waybill->status === WaybillStatus::IN_TRANSIT) {

                $html .= '<span class="badge badge-light">' . WaybillStatus::STATUS_TEXT[WaybillStatus::IN_TRANSIT] . '<span>';
            } else if ($waybill->status === WaybillStatus::ACKNOWLEDGED) {
                $html .= '<span class="badge badge-success">' . WaybillStatus::STATUS_TEXT[WaybillStatus::ACKNOWLEDGED] . '</span>';
            } else if ($waybill->status === WaybillStatus::CANCELLED) {
                $html .= '<span class="badge badge-primary">' . WaybillStatus::STATUS_TEXT[WaybillStatus::CANCELLED] . '</span>';
            } else if ($waybill->status === WaybillStatus::PENDING) {
                $html .= '<span class="badge badge-default">' . WaybillStatus::STATUS_TEXT[\App\Enums\WaybillStatus::PENDING] . '</span>';
            } else {
                $html .= '<span class="badge badge-dark">Unknown</span>';
            }

            return $html;
        })
        ->addColumn('action', function (Waybill $waybill) {
            $html = '<div class="dropdown">
            <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-h"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item change-group" href="waybills/' . $waybill->id . '/track">
                    <i class="fas fa-info-circle"></i>
                    <span class="nav-link-text">Track</span>
                  </a>
                   <a class="dropdown-item change-group" href="waybills/' . $waybill->id . '/track-k9">
                    <i class="fas fa-info-circle"></i>
                    <span class="nav-link-text">Track on K9</span>
                  </a>';

            $html .= '</a></div></div>';

            return $html;
        })
        ->rawColumns(['route', 'dispatched', 'status_label', 'total_parcels', 'action'])
        ->make(true);
}

    public function getAcknowledgedWaybills()
    {

        $filters = [
            'status' => (int)request()->input('status'),
            'scan_site_id' => (int)request()->input('scan_site_id'),
            'next_site_id' => (int)request()->input('next_site_id'),
            'start_date' => Carbon::parse(request()->input('start_date')),
            'end_date' => Carbon::parse(request()->input('end_date')),
            'user' => Auth::user()
        ];

        $waybills_query = $this->waybill_services->getAcknowledgedWaybills($filters);
        return Datatables::of($waybills_query)
            ->addIndexColumn()
            ->addColumn('route', function (Waybill $waybill) {
                $html = '<span style="color:coral"> From </span><span class="text-muted">' . $waybill->manifest->scan_site->name . '<span style="color:coral"> To </span>' . $waybill->manifest->next_site->name . '</span>';
                return $html;
            })
            ->addColumn('dispatched', function (Waybill $waybill) {
                $html = '<span class="">' . $waybill->created_at->diffForHumans() . '</span>
            <div class="text-green">' . $waybill->created_at->format('Y-m-d , g:i A') . '</div>';
                return $html;
            })
            ->addColumn('status_label', function (Waybill $waybill) {
                $html = "";
                if ($waybill->status === WaybillStatus::IN_TRANSIT) {

                    $html .= '<span class="badge badge-light">' . WaybillStatus::STATUS_TEXT[WaybillStatus::IN_TRANSIT] . '<span>';
                } else if ($waybill->status === WaybillStatus::ACKNOWLEDGED) {
                    $html .= '<span class="badge badge-success">' . WaybillStatus::STATUS_TEXT[WaybillStatus::ACKNOWLEDGED] . '</span>';
                } else if ($waybill->status === WaybillStatus::CANCELLED) {
                    $html .= '<span class="badge badge-primary">' . WaybillStatus::STATUS_TEXT[WaybillStatus::CANCELLED] . '</span>';
                } else if ($waybill->status === WaybillStatus::PENDING) {
                    $html .= '<span class="badge badge-default">' . WaybillStatus::STATUS_TEXT[\App\Enums\WaybillStatus::PENDING] . '</span>';
                } else {
                    $html .= '<span class="badge badge-dark">Unknown</span>';
                }

                return $html;
            })
            ->addColumn('action', function (Waybill $waybill) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item change-group" href="waybills/' . $waybill->id . '/track">
                        <i class="fas fa-info-circle"></i>
                        <span class="nav-link-text">Track</span>
                      </a>
					   <a class="dropdown-item change-group" href="waybills/' . $waybill->id . '/track-k9">
                        <i class="fas fa-info-circle"></i>
                        <span class="nav-link-text">Track on K9</span>
                      </a>';

                $html .= '</a></div></div>';

                return $html;
            })
            ->rawColumns(['route', 'dispatched', 'status_label', 'total_parcels', 'action'])
            ->make(true);
    }



    public function getDispatchedWaybillsSummary()
    {
        return response()->json(
            $this->waybill_services->getDispatchedWaybillsSummary(['scan_site_id' => Auth::user()->site_id, 'date'=> Carbon::today()->format('Y-m-d')])
        );
    }

    public function getIncomingWaybillsSummary()
    {
        return response()->json(
            $this->waybill_services->getIncomingWaybillsSummary(['scan_site_id' => Auth::user()->site_id, 'date'=> Carbon::today()->format('Y-m-d')])
        );
    }

    // public function track()
    // {
    //     $waybill = request()->input('waybill');
    //     return Waybill::where('id', $waybill)->get();
    // }


    public function getArrivedWaybillsScans()
    {

        if (request()->ajax()) {

            $filters = [
                'status' => (int)request()->input('status'),
                'scan_site_id' => (int)request()->input('scan_site_id'),
                'next_site_id' => (int)request()->input('next_site_id'),
                'start_date' => Carbon::parse(request()->input('start_date')),
                'end_date' => Carbon::parse(request()->input('end_date')),
                'user' => Auth::user()
            ];

        	$query = $this->waybill_services->scanRecords($filters);
            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('display_delivery_count', function ($record) {
                $color = 'btn-warning';
                if($record->delivery_count > 0)
                {
                    $color = 'btn-success';
                }
                 $html = '<button type="button" class="btn btn-sm '.$color.'  btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of return scans" data-container="body" data-animation="true">' . $record->delivery_count . '</button>';

                 return $html;
             })
             ->addColumn('display_collection_count', function ($record) {
                $color = 'btn-warning';
                if($record->collection_count > 0)
                {
                    $color = 'btn-success';
                }
                 $html = '<button type="button" class="btn btn-sm '.$color.'  btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of return scans" data-container="body" data-animation="true">' . $record->collection_count . '</button>';

                 return $html;
             })
             ->addColumn('display_issue_parcel_count', function ($record) {
                $color = 'btn-warning';
                if($record->issue_parcel_count > 0)
                {
                    $color = 'btn-success';
                }
                 $html = '<button type="button" class="btn btn-sm '.$color.'  btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of return scans" data-container="body" data-animation="true">' . $record->issue_parcel_count . '</button>';

                 return $html;
             })
             ->addColumn('display_delivery_count', function ($record) {
                $color = 'btn-warning';
                if($record->delivery_count > 0)
                {
                    $color = 'btn-success';
                }
                 $html = '<button type="button" class="btn btn-sm '.$color.'  btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of return scans" data-container="body" data-animation="true">' . $record->delivery_count . '</button>';

                 return $html;
             })
            ->addColumn('display_return_count', function ($record) {
               $color = 'btn-warning';
               if($record->return_count > 0)
               {
                   $color = 'btn-success';
               }
                $html = '<button type="button" class="btn btn-sm '.$color.'  btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of return scans" data-container="body" data-animation="true">' . $record->return_count . '</button>';

                return $html;
            })
            ->addColumn('display_departure_count', function ($record) {
                $color = 'btn-warning';
                if($record->departure_count > 0)
                {
                    $color = 'btn-success';
                }
                 $html = '<button type="button" class="btn btn-sm '.$color.'  btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of return scans" data-container="body" data-animation="true">' . $record->departure_count . '</button>';

                 return $html;
             })

            ->rawColumns(['display_return_count', 'display_delivery_count', 'display_collection_count', 'display_issue_parcel_count', 'display_departure_count'])
            ->make(true);
        }
        else {

            $sites = $this->site_services->getAllSites();

            $from_sites = [];
            $to_sites = [];
            $user_id = Auth::user()->id;
            $user = User::find($user_id);

            if ($user->hasAnyRole(['Quality Control Personnel'])) {
                $from_sites =  $this->site_services->getAllSitesV2();
                $to_sites = $this->site_services->getAllSitesV2();
            } else {
                $from_sites =  Site::where('id', Auth::user()->site->id)->where('can_dispatch_or_acknowledge_manifest', '!=', 0)->pluck('name', 'id');
                $to_sites =  $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
            }

            return view('arrived-waybills-scans', compact('from_sites', 'to_sites'));

        }


    }

public function getIncomingWaybillsArrivalStatus()
{
    if(request()->ajax())
    {
        $filters = ['next_site_id' => Auth::user()->site_id, 'start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date'), 'scanner_id' => null];

        $filters = [
            'scan_site_id' => (int)request()->input('scan_site_id'),
            'next_site_id' => (int)request()->input('next_site_id'),
            'start_date' => Carbon::parse(request()->input('start_date')),
            'end_date' => Carbon::parse(request()->input('end_date')),
                'scanner_id' => null];
        $result = $this->waybill_services->getWaybillsArrivalStatus($filters);
        return Datatables::of($result)
        ->addIndexColumn()
        ->addColumn('arrival_status', function ($row) {

            $arrival_status = '<span class="text-warning">Not Arrived</span>';
            if($row->arrival_date != NULL)
            {
                $arrival_status = '<span class="text-success">Acknowledged</span>';
            }

             $html = $arrival_status;

             return $html;
         })
        ->addColumn('action', function ($scan) {
            $html = '<div class="dropdown">
            <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-h"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                  ';
            $html .= '
            </div>
          </div>';

            return $html;
        })
        ->rawColumns(['action', 'arrival_status'])
        ->make(true);
    }
    else
    {

    $sites = $this->site_services->getAllSites();

    $from_sites = [];
    $to_sites = [];
    $user_id = Auth::user()->id;
    $user = User::find($user_id);

    if ($user->hasAnyRole(['Quality Control Personnel'])) {
        $from_sites =  $this->site_services->getAllSitesV2();
        $to_sites = $this->site_services->getAllSitesV2();
    } else {
        $from_sites =  Site::where('id', Auth::user()->site->id)->where('can_dispatch_or_acknowledge_manifest', '!=', 0)->pluck('name', 'id');
        $to_sites =  $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
    }

    return view('waybills.arrival-status', compact('from_sites', 'to_sites'));

}
}


    public function getWaybillsArrivalStatus(){
        if(request()->ajax())
        {
            // $filters = ['next_site_id' => Auth::user()->site_id, 'start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date'), 'scanner_id' => null];

            $filters = [
                'scan_site_id' => (int)request()->input('scan_site_id'),
                'next_site_id' => (int)request()->input('next_site_id'),
                'start_date' => Carbon::parse(request()->input('start_date')),
                'end_date' => Carbon::parse(request()->input('end_date')),
                    'scanner_id' => null];
            $result = $this->waybill_services->getWaybillsArrivalStatus($filters);
            return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('arrival_status', function ($row) {

                $arrival_status = '<span class="text-warning">Not Arrived</span>';
                if($row->arrival_date != NULL)
                {
                    $arrival_status = '<span class="text-success">Acknowledged</span>';
                }

                 $html = $arrival_status;

                 return $html;
             })
            ->addColumn('action', function ($scan) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      ';
                $html .= '
                </div>
              </div>';

                return $html;
            })
            ->rawColumns(['action', 'arrival_status'])
            ->make(true);
        }
        else
        {

        $sites = $this->site_services->getAllSites();

        $from_sites = [];
        $to_sites = [];
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        // if ($user->hasAnyRole(['Quality Control Personnel'])) {
        //     $from_sites =  $this->site_services->getAllSitesV2();
        //     $to_sites = $this->site_services->getAllSitesV2();
        // } else {
        //     $from_sites =  Site::where('id', Auth::user()->site->id)->where('can_dispatch_or_acknowledge_manifest', '!=', 0)->pluck('name', 'id');
        //     $to_sites =  $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
        // }

        $from_sites =  $this->site_services->getAllSitesV2();
        $to_sites = $this->site_services->getAllSitesV2();

        return view('waybills.arrival-status', compact('from_sites', 'to_sites'));

    }
    }



    public function getK9DepartureScanSummary()
    {



        if(request()->ajax())
        {
            $filters = ['scan_site_id' => Auth::user()->site_id, 'start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date'), 'scanner_id' => null];
            $result = $this->waybill_services->getK9DepartureScanSummary($filters);
            return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($scan) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      ';
                $html .= '
                </div>
              </div>';

                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        else
        {
            $to_sites =  $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
            return view('k9.departure-scans-summary', compact('to_sites'));
        }
    }


    public function getK9IncomingScanSummary()
    {



        if(request()->ajax())
        {
            $filters = ['next_site_id' => Auth::user()->site_id, 'start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date'), 'scanner_id' => null];
            $result = $this->waybill_services->getK9IncomingScanSummary($filters);
            return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('action', function ($scan) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      ';
                $html .= '
                </div>
              </div>';

                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        else
        {
            $to_sites =  $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
            return view('k9.incoming-scans-summary');
        }
    }
}
