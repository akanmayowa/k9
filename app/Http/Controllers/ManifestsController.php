<?php

namespace App\Http\Controllers;

use App\Site;
use App\User;
use Exception;
use App\Waybill;
use App\Manifest;
use Carbon\Carbon;
use App\K9ArrivalScan;
use App\ScanTimestamp;
use App\K9DepartureScan;
use App\Enums\WaybillStatus;
use Illuminate\Http\Request;
use App\Enums\ManifestStatus;
use App\Services\SiteServices;
use Yajra\DataTables\DataTables;
use App\Events\ManifestDispatched;
use App\Services\ManifestServices;
use Illuminate\Support\Facades\DB;
use App\Events\ManifestAcknowledged;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exceptions\ManifestException;
use App\Imports\ManifestWaybillImport;
use App\Services\Helpers;
use App\Services\K9Services;

class ManifestsController extends Controller
{
    public $manifest_services = null;
    public $site_services = null;

    public function __construct(ManifestServices $manifest_services, SiteServices $site_services)
    {
        $this->middleware('auth');
        $this->manifest_services = $manifest_services;
        $this->site_services = $site_services;
        $this->current_user = Auth::user();
        // $this->user_time_zone =  'Africa/Lagos'; // temporal move this to user DB
    }


    public function getManifests()
    {

        // $status =  request()->input('status');
        // if($status == " ")
        // {
        //     $status = null;
        // }
        // else
        // {
        //     $status = (int)$status;
        // }



        $filters = [
            'status' => (int)request()->input('status'),
            'scan_site_id' => (int)request()->input('scan_site_id'),
            'next_site_id' => (int)request()->input('next_site_id'),
            'start_date' => Carbon::parse(request()->input('start_date')),
            'end_date' => Carbon::parse(request()->input('end_date'))
        ];

        $manifests_query = $this->manifest_services->getManifests($filters);
        return Datatables::of($manifests_query)
            ->addIndexColumn()
            ->addColumn('bag_info', function (Manifest $manifest) {
                $shipment_type = "";
                if (!is_null($manifest->shipment_type)) {
                    $shipment_type = "<span class='badge badge-primary'>" . config('custom.shipment_type', ['Forward', 'Reverse'])[$manifest->shipment_type] . "</span>";
                }

                $transport_type = "";
                if (!is_null($manifest->transport_type_id)) {
                    $transport_type = "<span class='badge badge-primary'>" . config('custom.transport_type', ['Air', 'Shuttle', '3rd Party', 'others'])[$manifest->transport_type_id] . "</span>";
                }


                return "$manifest->seal_number <br/>$transport_type " . $shipment_type;
            })
            ->addColumn('route', function (Manifest $manifest) {
                $html = '<span style="color:coral"> From </span><span class="text-muted">' . $manifest->scan_site->name . '<span style="color:coral"> To </span>' . $manifest->next_site->name . '</span>';
                return $html;
            })
            ->addColumn('dispatched', function (Manifest $manifest) {
                $remark = "";
                if (!is_null($manifest->remark)) {
                    $remark = " <i class='fas fa-envelope text-yellow'></i>";
                }

                $html = '<div class="text-darker">By ' . $manifest->created_by_user->name . $remark . '</div><span class="">' . $manifest->created_at->diffForHumans() . '</span>
                <div class="text-green">' . $manifest->created_at->setTimezone(Auth::user()->timezone)->format('Y-m-d , g:i A') . '</div>';
                return $html;

                //     $html = '<span class="">' . $manifest->created_at->diffForHumans() . '</span>
                // <div class="text-green">' . $manifest->created_at->format('Y-m-d , g:i A') . '</div>';
                //     return $html;
            })
            ->addColumn('status_label', function (Manifest $manifest) {
                $html = "";
                if ($manifest->status === ManifestStatus::IN_TRANSIT) {
                    if ($manifest->flagged === 1) {

                        $html .= '<i class="fas fa-flag text-danger" ></i>';
                    }
                    $html .= '<span class="badge badge-light">' . ManifestStatus::STATUS_TEXT[ManifestStatus::IN_TRANSIT] . '<span>';
                } else if ($manifest->status === ManifestStatus::ACKNOWLEDGED) {
                    $html .= '<span class="badge badge-success">' . ManifestStatus::STATUS_TEXT[ManifestStatus::ACKNOWLEDGED] . '</span>';
                } else if ($manifest->status === ManifestStatus::CANCELLED) {
                    $html .= '<span class="badge badge-primary">' . ManifestStatus::STATUS_TEXT[ManifestStatus::CANCELLED] . '</span>';
                } else if ($manifest->status === ManifestStatus::PARTIALLY_RECEIVED) {
                    $html .= '<span class="badge badge-default">' . ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::PARTIALLY_RECEIVED] . '</span>';
                } else {
                    $html .= '<span class="badge badge-dark">Unknown</span>';
                }

                return $html;
            })
            ->addColumn('pending_waybills_count', function (Manifest $manifest) {
                // $manifest->pending_waybills()->count() // implement later
                $html = '<button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills pending" data-container="body" data-animation="true">' . $manifest->waybills->where('status', 0)->count() . '</button>';

                return $html;
            })
            ->addColumn('acknowledged_waybills_count', function (Manifest $manifest) {
                $html = '<button type="button" class="btn btn-sm btn-success btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills acknowledged" data-container="body" data-animation="true">' . $manifest->waybills->where('status', 1)->count() . '</button>';

                return $html;
            })
            ->addColumn('dispatched_waybills_count', function (Manifest $manifest) {
                $html = '<button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills sent" data-container="body" data-animation="true">' . $manifest->waybills->count() . '</button>';

                return $html;
            })
            ->addColumn('pending_waybills_count_classic', function (Manifest $manifest) {
                return  $manifest->waybills->where('status', 0)->count();
            })
            ->addColumn('acknowledged_waybills_count_classic', function (Manifest $manifest) {
                return $manifest->waybills->where('status', 1)->count();
            })
            ->addColumn('dispatched_waybills_count_classic', function (Manifest $manifest) {
                return $manifest->waybills->count();
            })
            ->addColumn('dispatched_date_classic', function (Manifest $manifest) {
                return $manifest->created_at->setTimezone(Auth::user()->timezone)->format('Y-m-d');
            })
            ->addColumn('dispatched_time_classic', function (Manifest $manifest) {
                return $manifest->created_at->setTimezone(Auth::user()->timezone)->format('g:i A');
            })
            ->addColumn('action', function (Manifest $manifest) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                <a class="dropdown-item change-group" href="' . route('getManifestDetails', ['manifest_id' => $manifest->id]) . '">
                <i class="fas fa-info-circle"></i>
                <span class="nav-link-text">View Details</span>';
                $html .= '
                </a>
                </div>
              </div>';

                return $html;
            })
            ->rawColumns(['route', 'dispatched', 'status_label',  'action', 'dispatched_waybills_count', 'acknowledged_waybills_count', 'pending_waybills_count', 'bag_info'])
            ->make(true);
    }


    //Shows the Listing of all Manifests
    public function index()
    {

        // $manifests = $this->manifest_services->getAllManifest();

        // return view('manifest.index', compact('manifests'));

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


        // dd($from_sites, $to_sites, $user->getRoleNames());
        return view('manifest.index', compact('sites', 'from_sites', 'to_sites'));
    }

    /* Shows the form to create manifest
       Constriants:
            -> A user cannot send manifest to his or her site
    */

    public function dispatchManifest()
    {
        //combine cerate and store here
    }

//not used anymore
    public function create()
    {
        try {

            $site_list = $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
            $site_users = $this->site_services->getSiteUsers(Auth::user()->site->id)->pluck('name', 'id');

            return view('manifest.create', compact('site_list', 'site_users'));
        } catch (Exception $ex) {

            //TODO: Remember to remove the display of exception message on Production
            return redirect()->back()->withError('Could not retrieve Next sites!, ' . $ex->getMessage());
        }
    }

    public function store()
    {
        // dd(request()->all());
        $data['waybills'] = json_decode(request()->input('waybills')); //ajax way dont need this
        // dd($waybills);
        $data['next_site_id'] = (int)request()->input('waybills_next_site_id'); //(int)request()->input('next_site_id');
        $data['transport_type_id'] = request()->input('transport_type');
        $data['driver_name'] = request()->input('driver_name');
        $data['driver_phonenumber'] = request()->input('driver_phonenumber');
        $data['truck_platenumber'] = request()->input('truck_platenumber');
        $data['seal_number'] = request()->input('seal_number'); // Validate Seat Number
        $data['bag_number'] = request()->input('bag_number'); // Validate Seat Number
        $data['truck_seal_number'] = request()->input('truck_seal_number');
        $data['shipment_type'] =  request()->input('shipment_type');
        $data['groups'] = json_decode(request()->input('groups_to_send'));


        // dd($data['groups']);
        $data['manifest_remark'] =  request()->input('manifest_remark');


        // dd($groups);

        // $filters = [
        //     'status' => (int)request()->input('status'),
        //     'scan_site_id' => (int)request()->input('scan_site_id'),
        //     'next_site_id' => (int)request()->input('next_site_id'),
        //     'start_date' => Carbon::parse(request()->input('start_date')),
        //     'end_date' => Carbon::parse(request()->input('end_date'))
        // ];

        // dd(compact('waybills', 'next_site_id', 'transport_type_id', 'driver_name', 'driver_phonenumber', 'truck_platenumber', 'number_of_bags', 'truck_seal_number'));
        try {

            $result = $this->manifest_services->createManifest($data);
            if ($result['success'] == true)
                return redirect()->back()->withSuccess("Manifest ID {$result['manifest']->id} Dispatch SuccessFully");
            else
                return redirect()->back()->withError($result['message']);
        } catch (ManifestException $ex) {

            return redirect()->back()->withError('Dispatched Failed!, ' . $ex->getMessage());
        } catch (Exception $ex) {

            return redirect()->back()->withError($ex->getMessage());
        }
        // return json_encode(['success' => true, 'message' => 'request()->all()']);

        // return response()->json(['success' => false, 'message' => 'Dispatched failed, Could not create Manifest']);
    }

    public function waybills()
    {
        $waybills = $this->manifest_services->getAllWaybills();
        return view('manifest.waybills', compact('waybills'));
    }

    public function waybillsInsight()
    {
        $site = Site::find('DC-LOS');
        $waybills = [];
        return view('tools.waybills-insight', compact('waybills'));
    }

    public function flagOverdue($manifest_id)
    {

        try {

            $this->manifest_services->flagOverdue(compact('manifest_id'));
            return  ['success' => true, 'message' => "Manifest ID $manifest_id has been Flagged successfully"];
        } catch (ManifestException $ex) {
            return  ['success' => false, 'message' => $ex->getMessage()];
        } catch (Exception $ex) {
            return  ['success' => false, 'message' => "An error occurred, Could not Flag manifest"];
        }
    }


    public function cancelManifest($manifest_id)
    {
        //return response()->json($manifest_id);
        try {
            $user = Auth::user(); // assumming user is logged in wrong
            $this->manifest_services->cancelManifest(compact('manifest_id', 'user'));
            return  ['success' => true, 'message' => "Manifest ID $manifest_id has been cancelled successfully"];
        } catch (ManifestException $ex) {
            return  ['success' => false, 'message' => $ex->getMessage()];
        } catch (Exception $ex) {
            //Log error
            return  ['success' => false, 'message' => "An error occurred, Could not cancel manifest"];
        }
    }

    // public function importDispatchedWaybills()
    // {
    //     //Validation they o
    //     //Import so you can covert to string
    //     $formData = request()->file();
    //     $waybills = ["86234200207115", "86234200202417", "86234200232547", "86234200197017", "86234200342757", "86234200259081"];
    //     return json_encode(['message' => 'cool', 'waybills' => $waybills, 'formData' => $formData]);
    //     dd(request()->all());
    //     $result = Excel::toArray(new ManifestWaybillImport, request()->file('file'));
    //     dd($result);
    // }

    // public function previewImport()
    // {
    //     $contents = Excel::toArray(new ManifestWaybillImport, request()->file('file'))[0];
    //     $waybills_to_import = [];
    //     $first_content = array_shift($contents); //remove the first row
    //     dd($contents);
    //     // foreach($contents as $data)
    //     // {
    //     //     //remove blank lines
    //     //     // if($data['site_no'] == "")
    //     //     // {
    //     //     //     continue;
    //     //     // }

    //     //     $waybills_to_import[] = ['id' => $data['site_no'], 'name' => $data['site']];
    //     // }

    //     // dd($waybills_to_import);

    //     //    $result = Site::insert(
    //     //         $waybills_to_import
    //     //     );

    //     // dd($result);
    // }

    public function getNewVirtualSealNumber()
    {
        return response()->json("VS-" . strtoupper(Helpers::generateCode(5)));
    }

    public function viewIncomingManifests()
    {
        // $manifests = $this->manifest_services->getIncomingManifest();
        // return view('manifest.incoming', compact('manifests'));



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
        $manifests = []; //$this->manifest_services->getDispatchedManifest();
        $from_sites = $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
        return view('manifest.incoming', compact('manifests', 'sites', 'from_sites', 'to_sites'));
    }

    public function viewDispatchedManifests()
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


        // dd($from_sites, $to_sites, $user->getRoleNames());
        $manifests = []; //$this->manifest_services->getDispatchedManifest();
        $site_users = $this->site_services->getSiteUsers(Auth::user()->site->id)->pluck('name', 'id');

        return view('manifest.dispatched', compact('manifests', 'sites', 'from_sites', 'to_sites', 'site_users'));
    }

    //You can do both with one function by detecting ajax
    public function getIncomingManifests()
    {

        $filters = [
            'status' => (int)request()->input('status'),
            'scan_site_id' => (int)request()->input('scan_site_id'),
            'next_site_id' => (int)request()->input('next_site_id'),
            'start_date' => Carbon::parse(request()->input('start_date')),
            'end_date' => Carbon::parse(request()->input('end_date')),
            'user' => Auth::user()
        ];

        $manifests_query = $this->manifest_services->getIncomingManifests($filters);
        return Datatables::of($manifests_query)
            ->addIndexColumn()
            ->addColumn('bag_info', function (Manifest $manifest) {
                $shipment_type = "";
                if (!is_null($manifest->shipment_type)) {
                    $shipment_type = "<span class='badge badge-primary'>" . config('custom.shipment_type', ['Forward', 'Reverse'])[$manifest->shipment_type] . "</span>";
                }

                $transport_type = "";
                if (!is_null($manifest->transport_type_id)) {
                    $transport_type = "<span class='badge badge-primary'>" . config('custom.transport_type', ['Air', 'Shuttle', '3rd Party', 'others'])[$manifest->transport_type_id] . "</span>";
                }


                return "$manifest->bag_id // $manifest->seal_number <br/>$transport_type " . $shipment_type;
            })
            // ->addColumn('route', function (Manifest $manifest) {
            //     $html = '<span style="color:coral"> From </span><span class="text-muted">' . $manifest->scan_site->name . '<span style="color:coral"> To </span>' . $manifest->next_site->name . '</span>';
            //     return $html;
            // })
            ->addColumn('dispatched', function (Manifest $manifest) {
                $remark = "";
                if (!is_null($manifest->remark)) {
                    $remark = "<i class='fas fa-envelope text-yellow btn btn-sm btn-success btn-tooltip' data-toggle='tooltip' data-placement='left' title='$manifest->remark' data-container='body' data-animation='true'></i>";
                }

                $html = '<div class="text-darker">By ' . $manifest->created_by_user->name . $remark . '</div><span class="">' . $manifest->created_at->diffForHumans() . '</span>
                <div class="text-green">' . $manifest->created_at->format('Y-m-d , g:i A') . '</div>';
                return $html;

                //     $html = '<span class="">' . $manifest->created_at->diffForHumans() . '</span>
                // <div class="text-green">' . $manifest->created_at->format('Y-m-d , g:i A') . '</div>';
                //     return $html;
            })
            ->addColumn('status_label', function (Manifest $manifest) {
                $html = "";
                if ($manifest->status === ManifestStatus::IN_TRANSIT) {
                    if ($manifest->flagged === 1) {

                        $html .= '<i class="fas fa-flag text-danger" ></i>';
                    }
                    $html .= '<span class="badge badge-light">' . ManifestStatus::STATUS_TEXT[ManifestStatus::IN_TRANSIT] . '<span>';
                } else if ($manifest->status === ManifestStatus::ACKNOWLEDGED) {
                    $html .= '<span class="badge badge-success">' . ManifestStatus::STATUS_TEXT[ManifestStatus::ACKNOWLEDGED] . '</span>';
                } else if ($manifest->status === ManifestStatus::CANCELLED) {
                    $html .= '<span class="badge badge-primary">' . ManifestStatus::STATUS_TEXT[ManifestStatus::CANCELLED] . '</span>';
                } else if ($manifest->status === ManifestStatus::PARTIALLY_RECEIVED) {
                    $html .= '<span class="badge badge-default">' . ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::PARTIALLY_RECEIVED] . '</span>';
                } else {
                    $html .= '<span class="badge badge-dark">Unknown</span>';
                }

                return $html;
            })
            // ->addColumn('total_parcels', function (Manifest $manifest) {
            //     $html = '<button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of parcels acknowledged" data-container="body" data-animation="true">' . $manifest->acknowledged_waybills->count() . '</button>
            //     / <button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="right" title="Numbers of parcels dispatched" data-container="body" data-animation="true">' . count($manifest->waybills) . '</button>';

            //     return $html;
            // })
            // ->addColumn('pending_waybills_count', function (Manifest $manifest) {
            //     //$manifest->pending_waybills()->count() // implement later
            //     $html = '<button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills pending" data-container="body" data-animation="true">'.$manifest->pending_waybills->count().'</button>';

            //     return $html;
            // })
            // ->addColumn('acknowledged_waybills_count', function (Manifest $manifest) {
            //     $html = '<button type="button" class="btn btn-sm btn-success btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills acknowledged" data-container="body" data-animation="true">'.$manifest->acknowledged_waybills->count().'</button>';

            //     return $html;
            // })
            ->addColumn('dispatched_waybills_count', function (Manifest $manifest) {
                $html = '<button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills sent" data-container="body" data-animation="true">' . $manifest->waybills_count . '</button>';
                // $html = '<button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills sent" data-container="body" data-animation="true">' . 0 . '</button>';

                return $html;
            })
            ->addColumn('action_buttons', function (Manifest $manifest) {
                $html = '';
                // $html = '<button type="button" data-manifest="'.$manifest->id.'" class="btn btn-sm btn-success btn-tooltip acknowledge_manifest" data-toggle="tooltip" data-placement="left" title="Acknowledge this manifest" data-container="body" data-animation="true">Acknowledge</button>';
                return $html;
            })
            ->addColumn('action', function (Manifest $manifest) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item change-group" href="' . route('getManifestDetails', ['manifest_id' => $manifest->id]) . '">
                        <i class="fas fa-info-circle"></i>
                        <span class="nav-link-text">View Details</span>
                      </a>
                      <a class="dropdown-item change-group" href="' . route('getNipostManifest', ['manifest_id' => $manifest->id]) . '">
                      <span class="nav-link-text">Nipost Manifest</span>
                    </a>
                    <a class="dropdown-item change-group" href="' . route('getMPSManifest', ['manifest_id' => $manifest->id]) . '">
                    <span class="nav-link-text">MPS Manifest</span>
                  </a>
                      ';
                $html .= '<a class="dropdown-item change-group" href="' . route('confirmParcels', $manifest->id) . '">
                <i class="fas fa-check-double text-success"></i>
                <span class="nav-link-text">Acknowledge</span>
            </a>
                </div>
              </div>';

                return $html;
            })
            ->rawColumns(['dispatched', 'status_label',  'action', 'dispatched_waybills_count', 'bag_info', 'action_buttons'])
            // ->rawColumns(['route', 'dispatched', 'status_label', 'total_parcels', 'action', 'dispatched_waybills_count', 'acknowledged_waybills_count', 'pending_waybills_count', 'bag_info', 'action_buttons'])
            ->make(true);
    }


    public function getAcknowledgedManifests()
    {

        $filters = [
            'status' => (int)request()->input('status'),
            'scan_site_id' => (int)request()->input('scan_site_id'),
            'next_site_id' => (int)request()->input('next_site_id'),
            'start_date' => Carbon::parse(request()->input('start_date')),
            'end_date' => Carbon::parse(request()->input('end_date')),
            'user' => Auth::user()
        ];

        // $manifests_query = $this->manifest_services->getIncomingManifests($filters);
        $manifests_query = $this->manifest_services->getAcknowledgedManifests($filters);
        return Datatables::of($manifests_query)
            ->addIndexColumn()
            ->addColumn('route', function (Manifest $manifest) {
                $html = '<span style="color:coral"> From </span><span class="text-muted">' . $manifest->scan_site->name . '<span style="color:coral"> To </span>' . $manifest->next_site->name . '</span>';
                return $html;
            })
            ->addColumn('dispatched', function (Manifest $manifest) {
                $html = '<span class="">' . $manifest->created_at->diffForHumans() . '</span>
            <div class="text-green">' . $manifest->created_at->format('Y-m-d , g:i A') . '</div>';
                return $html;
            })
            // ->addColumn('acknowledged', function (Manifest $manifest) {
            //     $html = '<span class="">' . optional($manifest->acknowledged_at)->diffForHumans() . '</span>
            // <div class="text-green">' . optional($manifest->acknowledged_at)->format('Y-m-d , g:i A') . '</div>';
            //     return $html;
            // })
            ->addColumn('status_label', function (Manifest $manifest) {
                $html = "";
                if ($manifest->status === ManifestStatus::IN_TRANSIT) {
                    if ($manifest->flagged === 1) {

                        $html .= '<i class="fas fa-flag text-danger" ></i>';
                    }
                    $html .= '<span class="badge badge-light">' . ManifestStatus::STATUS_TEXT[ManifestStatus::IN_TRANSIT] . '<span>';
                } else if ($manifest->status === ManifestStatus::ACKNOWLEDGED) {
                    $html .= '<span class="badge badge-success">' . ManifestStatus::STATUS_TEXT[ManifestStatus::ACKNOWLEDGED] . '</span>';
                } else if ($manifest->status === ManifestStatus::CANCELLED) {
                    $html .= '<span class="badge badge-primary">' . ManifestStatus::STATUS_TEXT[ManifestStatus::CANCELLED] . '</span>';
                } else if ($manifest->status === ManifestStatus::PARTIALLY_RECEIVED) {
                    $html .= '<span class="badge badge-default">' . ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::PARTIALLY_RECEIVED] . '</span>';
                } else {
                    $html .= '<span class="badge badge-dark">Unknown</span>';
                }

                return $html;
            })
            ->addColumn('total_parcels', function (Manifest $manifest) {
                // $html = '<button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of parcels acknowledged" data-container="body" data-animation="true">' . $manifest->acknowledged_waybills()->count() . '</button>
                // / <button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="right" title="Numbers of parcels dispatched" data-container="body" data-animation="true">' . count($manifest->waybills) . '</button>';

                return "";
            })
            ->addColumn('pending_waybills_count', function (Manifest $manifest) {
                // $manifest->pending_waybills()->count() // implement later
                $html = '<button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills pending" data-container="body" data-animation="true">' . $manifest->waybills->where('status', 0)->count() . '</button>';

                return $html;
            })
            ->addColumn('acknowledged_waybills_count', function (Manifest $manifest) {
                $html = '<button type="button" class="btn btn-sm btn-success btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills acknowledged" data-container="body" data-animation="true">' . $manifest->waybills->where('status', 1)->count() . '</button>';

                return $html;
            })
            ->addColumn('dispatched_waybills_count', function (Manifest $manifest) {
                $html = '<button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills sent" data-container="body" data-animation="true">' . $manifest->waybills->count() . '</button>';

                return $html;
            })
            ->addColumn('action', function (Manifest $manifest) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item change-group" href="' . route('getManifestDetails', ['manifest_id' => $manifest->id]) . '">
                <i class="fas fa-info-circle"></i>
                <span class="nav-link-text">View Details</span>';

                if ($manifest->status === \App\Enums\ManifestStatus::PARTIALLY_RECEIVED) {
                    $html .= '<a class="dropdown-item change-group" href="' . route('confirmParcels', $manifest->id) . '">
                    <i class="fas fa-check-double text-success"></i>
                    <span class="nav-link-text">Re Acknowledge</span>
                     </a>';
                }
                $html .= '</div>
              </div>';

                return $html;
            })
            ->rawColumns(['route', 'dispatched', 'status_label', 'total_parcels', 'action', 'dispatched_waybills_count', 'acknowledged_waybills_count', 'pending_waybills_count'])
            ->make(true);
    }

public function viewPartiallyAcknowledgedManifests()
{
        $manifests = null;
        return view('manifest.partially-acknowledged', compact('manifests'));
}

    public function getPartiallyAcknowledgedManifests()
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

            $manifests_query = $this->manifest_services->getPartiallyAcknowledgedManifests($filters);
            return Datatables::of($manifests_query)
            ->addIndexColumn()
            ->addColumn('route', function (Manifest $manifest) {
                $html = '<span style="color:coral"> From </span><span class="text-muted">' . $manifest->scan_site->name . '<span style="color:coral"> To </span>' . $manifest->next_site->name . '</span>';
                return $html;
            })
            ->addColumn('dispatched', function (Manifest $manifest) {
                $html = '<span class="">' . $manifest->created_at->diffForHumans() . '</span>
            <div class="text-green">' . $manifest->created_at->format('Y-m-d , g:i A') . '</div>';
                return $html;
            })
            ->addColumn('status_label', function (Manifest $manifest) {
                $html = "";
                if ($manifest->status === ManifestStatus::IN_TRANSIT) {
                    if ($manifest->flagged === 1) {
                        $html .= '<i class="fas fa-flag text-danger" ></i>';
                    }
                    $html .= '<span class="badge badge-light">' . ManifestStatus::STATUS_TEXT[ManifestStatus::IN_TRANSIT] . '<span>';
                } elseif ($manifest->status === ManifestStatus::ACKNOWLEDGED) {
                    $html .= '<span class="badge badge-success">' . ManifestStatus::STATUS_TEXT[ManifestStatus::ACKNOWLEDGED] . '</span>';
                } elseif ($manifest->status === ManifestStatus::CANCELLED) {
                    $html .= '<span class="badge badge-primary">' . ManifestStatus::STATUS_TEXT[ManifestStatus::CANCELLED] . '</span>';
                } elseif ($manifest->status === ManifestStatus::PARTIALLY_RECEIVED) {
                    $html .= '<span class="badge badge-default">' . ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::PARTIALLY_RECEIVED] . '</span>';
                } else {
                    $html .= '<span class="badge badge-dark">Unknown</span>';
                }

                return $html;
            })
            ->addColumn('total_parcels', function (Manifest $manifest) {
                // $html = '<button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of parcels acknowledged" data-container="body" data-animation="true">' . $manifest->acknowledged_waybills()->count() . '</button>
                // / <button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="right" title="Numbers of parcels dispatched" data-container="body" data-animation="true">' . count($manifest->waybills) . '</button>';

                return "";
            })
            ->addColumn('pending_waybills_count', function (Manifest $manifest) {
                // $manifest->pending_waybills()->count() // implement later
                $html = '<button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills pending" data-container="body" data-animation="true">' . $manifest->waybills->where('status', 0)->count() . '</button>';
                // $html = '<button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills pending" data-container="body" data-animation="true">' . 0 . '</button>';

                return $html;
            })
            ->addColumn('acknowledged_waybills_count', function (Manifest $manifest) {
                // $html = '<button type="button" class="btn btn-sm btn-success btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills acknowledged" data-container="body" data-animation="true">' . $manifest->waybills->where('status', 1)->count() . '</button>';
                $html = '<button type="button" class="btn btn-sm btn-success btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills acknowledged" data-container="body" data-animation="true">' . 0 . '</button>';

                return $html;
            })
            ->addColumn('dispatched_waybills_count', function (Manifest $manifest) {
                // $html = '<button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills sent" data-container="body" data-animation="true">' . $manifest->waybills->count() . '</button>';
                $html = '<button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills sent" data-container="body" data-animation="true">' . 0 . '</button>';
                return $html;
            })
            ->addColumn('action', function (Manifest $manifest) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item change-group" href="' . route('getManifestDetails', ['manifest_id' => $manifest->id]) . '">
                <i class="fas fa-info-circle"></i>
                <span class="nav-link-text">View Details</span>';

                if ($manifest->status === \App\Enums\ManifestStatus::PARTIALLY_RECEIVED) {
                    $html .= '<a class="dropdown-item change-group" href="' . route('confirmParcels', $manifest->id) . '">
                    <i class="fas fa-check-double text-success"></i>
                    <span class="nav-link-text">Re Acknowledge</span>
                     </a>';
                }
                $html .= '</div>
              </div>';

                return $html;
            })
            ->rawColumns(['route', 'dispatched', 'status_label', 'total_parcels', 'action', 'dispatched_waybills_count', 'acknowledged_waybills_count', 'pending_waybills_count'])
            ->make(true);
        }
        // else
        // {
        //     $manifests = null;
        //     return view('manifest.partially-acknowledged', compact('manifests'));
        // }


    }


    //You can do both with one function by detecting ajax
    public function getDispatchedManifests()
    {

        $filters = [
            'status' => (int)request()->input('status'),
            'scan_site_id' => (int)request()->input('scan_site_id'),
            'next_site_id' => (int)request()->input('next_site_id'),
            'created_by' => (int)request()->input('created_by'),
            'start_date' => Carbon::parse(request()->input('start_date'), Auth::user()->timezone),
            'end_date' => Carbon::parse(request()->input('end_date'),  Auth::user()->timezone),
            'user' => Auth::user()
        ];

        $manifests_query = $this->manifest_services->getDispatchedManifests($filters);
        return Datatables::of($manifests_query)
            ->setRowAttr([
                'data-manifest_id' => function (Manifest $manifest) {
                    return $manifest->id;
                },
            ])->addIndexColumn()
            ->addColumn('route', function (Manifest $manifest) {
                $html = '<span style="color:coral"> From </span><span class="text-muted">' . $manifest->scan_site->name . '<span style="color:coral"> To </span>' . $manifest->next_site->name . '</span>';
                return $html;
            })
            ->addColumn('dispatched', function (Manifest $manifest) {
                $remark = "";
                if (!is_null($manifest->remark)) {
                    $remark = " <i class='fas fa-envelope text-yellow'></i>";
                }

                $html = '<div class="text-darker">By ' . $manifest->created_by_user->name . $remark . '</div><span class="">' . $manifest->created_at->diffForHumans() . '</span>
                <div class="text-green">' . $manifest->created_at->setTimezone(Auth::user()->timezone)->format('Y-m-d , g:i A') . '</div>';
                return $html;
            })
            ->addColumn('status_label', function (Manifest $manifest) {
                $html = "";
                if ($manifest->status === ManifestStatus::IN_TRANSIT) {
                    if ($manifest->flagged === 1) {

                        $html .= '<i class="fas fa-flag text-danger" ></i>';
                    }
                    $html .= '<span class="badge badge-light">' . ManifestStatus::STATUS_TEXT[ManifestStatus::IN_TRANSIT] . '<span>';
                } else if ($manifest->status === ManifestStatus::ACKNOWLEDGED) {
                    $html .= '<span class="badge badge-success">' . ManifestStatus::STATUS_TEXT[ManifestStatus::ACKNOWLEDGED] . '</span>';
                } else if ($manifest->status === ManifestStatus::CANCELLED) {
                    $html .= '<span class="badge badge-primary">' . ManifestStatus::STATUS_TEXT[ManifestStatus::CANCELLED] . '</span>';
                } else if ($manifest->status === ManifestStatus::PARTIALLY_RECEIVED) {
                    $html .= '<span class="badge badge-default">' . ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::PARTIALLY_RECEIVED] . '</span>';
                } else {
                    $html .= '<span class="badge badge-dark">Unknown</span>';
                }

                return $html;
            })
            // ->addColumn('total_parcels', function (Manifest $manifest) {
            //     $html = '<button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of parcels acknowledged" data-container="body" data-animation="true">' . $manifest->acknowledged_waybills()->count() . '</button>
            //         / <button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="right" title="Numbers of parcels dispatched" data-container="body" data-animation="true">' . count($manifest->waybills) . '</button>';

            //     return $html;
            // })
            ->addColumn('pending_waybills_count', function (Manifest $manifest) {
                // $manifest->pending_waybills()->count() // implement later
                // $html = '<button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills pending" data-container="body" data-animation="true">' . $manifest->waybills->where('status', 0)->count() . '</button>';
                $html = '<button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills pending" data-container="body" data-animation="true">' . 0 . '</button>';

                return $html;
            })
            ->addColumn('acknowledged_waybills_count', function (Manifest $manifest) {
                // $html = '<button type="button" class="btn btn-sm btn-success btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills acknowledged" data-container="body" data-animation="true">' . $manifest->waybills->where('status', 1)->count() . '</button>';
                $html = '<button type="button" class="btn btn-sm btn-success btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills acknowledged" data-container="body" data-animation="true">' . 0 . '</button>';

                return $html;
            })
            ->addColumn('dispatched_waybills_count', function (Manifest $manifest) {
                $html = '<button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills sent" data-container="body" data-animation="true">' .  $manifest->waybills_count . '</button>';
                return $html;
            })
            ->addColumn('action', function (Manifest $manifest) {
                $html = '<div class="dropdown">
                    <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-h"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                </a>
                    <a class="dropdown-item change-group" href="' . route('getManifestDetails', ['manifest_id' => $manifest->id]) . '">
                    <span class="nav-link-text">View Details</span>';
                $html .= '
                    </a>
                    <a class="dropdown-item change-group" href="' . route('getNipostManifest', ['manifest_id' => $manifest->id]) . '">
                    <span class="nav-link-text">Manifest with Weight</span>
                  </a>
                    </div>
                  </div>';

                return $html;
            })
            ->rawColumns(['route', 'dispatched', 'status_label', 'total_parcels', 'action', 'dispatched_waybills_count', 'acknowledged_waybills_count', 'pending_waybills_count'])
            ->make(true);
    }

//     <a class="dropdown-item change-group" href="' . route('getNipostManifest', ['manifest_id' => $manifest->id]) . '">
//     <span class="nav-link-text">Nipost Manifest</span>
//   </a>
//   <a class="dropdown-item change-group" href="' . route('getNipostManifestSummary', ['manifest_id' => $manifest->id]) . '">
//     <span class="nav-link-text">Nipost Manifest Summary</span>
//   </a>
//   <a class="dropdown-item change-group" href="' . route('getMPSManifest', ['manifest_id' => $manifest->id]) . '">
//   <span class="nav-link-text">MPS Manifest</span>

    public function viewAcknowledgedManifests()
    {
        // $manifests = $this->manifest_services->getAcknowledgedManifest();
        // dd($manifests[0]->manifested);
        $manifests = null;
        return view('manifest.acknowledged', compact('manifests'));
    }

    public function confirmParcels($manifest_id)
    {
        //This method requires access to interent
        try {
            $date = Carbon::today()->toDateString(); //Date range might change later


            $manifest = Manifest::with('waybills')->where('id', (int)$manifest_id)->first();


            //Check the already acknowledged here too

            //Not date big bug
            // $manifest_waybills_on_k9x = Waybill::where('manifest_id', $manifest->id)
            // ->get(['id', 'status']); // I think this should be get(['id', status])

            //this is not a an sql query rather it is on a collection
            // $unacknowledged_manifest_waybills  = $manifest->waybills->where('status', '!=', WaybillStatus::ACKNOWLEDGED)->pluck('id');


            // $unarrived_manifest_waybills_on_k9x =
            //     collect($manifest_waybills_on_k9x)
            //     ->filter(function ($waybill, $key) {
            //         return $waybill->status !== WaybillStatus::ACKNOWLEDGED;
            //     });

            // $arrived_manifest_waybills_on_k9x =
            //     collect($manifest_waybills_on_k9x)
            //     ->filter(function ($waybill, $key) {
            //         return $waybill->status === WaybillStatus::ACKNOWLEDGED;
            //     });


            // dd($already_arrived);

            // dd($manifest);

            /*
                Retrieve all the waybills of this manifest that have been arrived today
                by the user's site

                PreCondition: $manifest->next_site_id == Auth::user()->site_id

            */
            //Pick only distinct to avoid over
            $arrived_manifest_waybills_on_k9 = K9ArrivalScan::where('SCAN_SITE_CODE', $manifest->next_site_id)
                ->where('PRE_OR_NEXT_STATION_CODE', $manifest->scan_site_id)
                ->whereDate('SCAN_DATE', '>=', $manifest->created_at)
                ->whereIn('BILL_CODE', $manifest->waybills->pluck('id'))
                ->pluck('BILL_CODE'); // get the date is was arrived too, for dispatch get the dat it was scanned too this can be used to know when these scans where done on k9
            // ->whereDate('SCAN_DATE', $date) //Original one
            // ->OrderBy('SCAN_DATE', 'Asc')


            //We can just acknwoledge it here self

            //2021 - 28 - 6 , 5:39 AM , show expected List , arrived list, Missing List
            //Only arrived will be sent for acknowledgment

            // dd($manifest, $manifest_waybills_on_k9x->pluck('id'), $arrived_manifest_waybills_on_k9->pluck('BILL_CODE'));
            //Get the waybills for this manifest that has not been scanned
            return view('manifest.receive', compact('manifest', 'arrived_manifest_waybills_on_k9'));
        } catch (Exception $ex) {
            return redirect()->back()->with('error', "Failed to retrieve waybill manifest !, please check that you have a working internet connection");
        }
    }

    public function acknowledgeManifest()
    {
        try {

            $manifest_id = request()->input('manifest_id');
            $waybills = json_decode(request()->input('waybills'));
            // dd($waybills);
            $user = Auth::user();
            $result = $this->manifest_services->acknowledgeManifest(compact('manifest_id', 'waybills', 'user'));

            if ($result['success']) {
                return redirect()->route('viewIncomingManifests')->withSuccess("{$result['message']}");
            } else {
                return redirect()->back()->with('error', "{$result['message']}");
            }
        } catch (Exception $ex) {
            //Log error
            return redirect()->back()->with('error', "Manifest acknowledgement failed!, Please try again");
        }
    }



    // public function acknowledgeWaybills()
    // {
    //     $waybills = json_decode(request()->input('waybills')); //ajax way dont need this
    //     $scan_site_id = (int)request()->input('scan_site_id');

    //     try {

    //         $result = $this->manifest_services->acknowledgeWaybills(compact('waybills', 'scan_site_id'));
    //         if ($result['success'] == true)
    //             return redirect()->back()->withSuccess("Manifest ID {$result['manifest']->id} Dispatch SuccessFully");
    //         else
    //             return redirect()->back()->withError($result['message']);
    //     } catch (ManifestException $ex) {

    //         return redirect()->back()->withError('Dispatched Failed!, ' . $ex->getMessage());
    //     } catch (Exception $ex) {

    //         return redirect()->back()->withError('Dispatched Failed!, An error occurred at the server level\n' . $ex->getMessage());
    //     }
    // }

    // public function unlock()
    // {
    //     return "Unlock this manifest";
    // }


    //stores a manifest


    // $create_manifest_data = [
    //     'seal_number'=>'5433', // int
    //     'waybills' => ['8748758784755', '8585795785987584'], //Another table referenced
    //     'departure_site_id' => 234202, //Bigint, FK
    //     'next_site_id' => 234, //Bigint, FK
    //     'courier' => 'Mudi Grace', // string, NULLABLE
    //     'means_of_movement' => 1, //INT ENUM
    //     'manifested_by' => 230570, //user id Big FK
    //     'dispatched_at' => now(), // DateTime NULLABLE
    //     'ackwnoledged_by' => 230570, //user id Big FK NULLABLE
    //     'created_at' => now(), //DateTime
    //     'updated_at'=> now() //DateTime
    // ];
    // public function store2()
    // {

    //     $current_user = Auth::user();


    //     $data = request()->all();
    //     $waybills = json_decode(request()->input('waybills'));
    //     $seal_number = (int)request()->input('seal_number');
    //     $next_site_id = (int)request()->input('next_site_id');

    //     $scan_site = Site::find($current_user->site_id);
    //     if ($scan_site == null) {
    //         dd("Error, Invalid Departure Site number supplied");
    //     }


    //     $destination_site = Site::find($next_site_id);
    //     if ($destination_site == null) {
    //         dd("Error, Invalid destination Site supplied ");
    //     }


    //     // dd($data);


    //     // $data = [
    //     //     'seal_number' => $seal_number,
    //     //     'scan_site' => $scan_site['name'],
    //     //     'destination_site' => $destination_site['name'],
    //     //     'means_of_movement' => 0, //means_of_movement enumeration
    //     //     'manifested_by' => $current_user,
    //     //     'dispatched_at' => null,
    //     //     'acknowledged_by' => 244039,
    //     //     'status' => 0, //PENDING
    //     //     'courier' => 'Emmanuel Olamide',
    //     //     'created_at' => now(),
    //     //     'updated_at' => now()
    //     // ];
    //     // dd($data);

    //     $create_manifest_data = [
    //         'seal_number' => $seal_number, // int
    //         'waybills' => $waybills, //Another table referenced
    //         'departure_site_id' => $scan_site->id, //Bigint, FK
    //         'next_site_id' => $destination_site->id, //Bigint, FK
    //         'courier' => null, // string, NULLABLE
    //         'means_of_movement' => 1, //INT ENUM
    //         'manifested_by' => $current_user->id, //user id Big FK
    //         'dispatched_at' => now(), // DateTime NULLABLE
    //         'ackwnoledged_by' => null, //user id Big FK NULLABLE
    //         'created_at' => now(), //DateTime
    //         'updated_at' => now() //DateTime
    //     ];

    //     $manifest = $this->manifest_services->createManifest($create_manifest_data);
    //     if ($manifest !== null)
    //         return redirect()->back()->withSuccess('Manifest Dispatch SuccessFully');
    //     else
    //         return redirect()->back()->withError('Dispatched Failed!, \n Could not create Manifest');
    // }

    //Saves a manifest
    // public function edit()
    // {
    //     return "Edit Manifest";
    // }




    public function getIncoming()
    {
        $manifest = $this->manifest_services->getIncomingManifest();
        return view('manifest.incoming', compact('manifest'));
    }

    // getManifestWithWaybills($manifset_id)
    // {
    //     $manifest = $this->manifest_services->getManifest($manifest_id);
    //     return $manifest;
    // }

    public function getManifest($manifest_id)
    {
        try {

            $manifest = $this->manifest_services->getManifest((int)$manifest_id);
            // dd($manifest);
            return response()->json(['success' => true, 'message' => 'Manifest Retrieved Successfully', 'data' => $manifest]);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'message' => 'Manifest record not found']);
        }
    }

    public function k9_getDepartureScans()
    {

        try {
            // return json_encode(request()->input('next_site_id'));
            $date = Carbon::yesterday()->toDateString();
            //'2341'
            //234124
            // $departure_site_id = Auth::user()->site_id;
            $departure_site_id = 2341;
            $waybills = DB::connection('mysql2')->select("SELECT
            BILL_CODE, SCAN_DATE,
            SCAN_TYPE_CODE,
            T3.EMPLOYEE_NAME,
            T2.SITE_NAME AS NEXT_SITE_NAME, T1.SITE_NAME AS SCAN_SITE_NAME, EMPLOYEE_NAME
            FROM NRLY.TAB_SCAN_SEND
            JOIN TAB_SITE T1
            ON SCAN_SITE_CODE = T1.SITE_CODE
            INNER JOIN TAB_SITE T2
            ON PRE_OR_NEXT_STATION_CODE = T2.SITE_CODE
            INNER JOIN TAB_EMPLOYEE_VIEW T3
            ON SCAN_MAN_CODE = T3.EMPLOYEE_CODE
            where SCAN_SITE_CODE = $departure_site_id  and DATE(SCAN_DATE) = '$date' ORDER BY BILL_CODE");
            // dd($waybills);

            // return response()->json(['success' => true, 'message' => 'Data Retrieved successfully', 'data' => collect($waybills)->pluck('BILL_CODE')]);
            return view('manifest.k9.departure-list', compact('waybills'));
            // return $waybills;

        } catch (Exception $ex) {
            $waybills = [];
            return  view('manifest.k9.departure-list', compact('waybills'));
        }
    }

    public function k9_createWaybillGroup()
    {
        return view('waybills.k9_createWaybillGroup', compact('siteList'));
    }

    /*
        Retrieves all the departure scans for a site for a partical date from k9 server

    */

    public function k9_getDepartedWaybills()
    {

        try {

            $date = Carbon::today()->toDateString(); //Date range might change later
            $scan_site_id =  Auth::user()->site_id; //Assummed only logged in user can access this controller

            //Validate SCAN_SITE
            if (!$scan_site_id) {
                throw new Exception("Scan site is not specified");
            }

            if (!request()->has('next_site_id')) {
                throw new Exception("next site not specified!");
            }

            $scanner_id = null;
            if (request()->has('scanner_id')) {
                $scanner_id = request()->get('scanner_id');
            }

            //Validate NEXT_SITE
            $next_site_id = (int)request()->get('next_site_id');
            $next_site = Site::find($next_site_id);

            //Null unkor
            $start_date = Carbon::parse(request()->input('start_date'));
            $end_date =   Carbon::parse(request()->input('end_date'));

            // if($filters['start_date'] != null)
            // {
            //     $build_query->whereDate('created_at', '>=', $filters['start_date']);

            // }

            // if($filters['end_date'] != null)
            // {
            //     $build_query->whereDate('created_at', '<=', $filters['end_date']);
            // }
            //wheereBetween & protect $dtes column could not help ? No time to check why


            // $query = k9DepartureScan::with('employee');



            /*
               //Should I use SCAN_DATE or REGISTER_DATE -- Please document this
                    Model::where(function ($query) use ($a,$b) {
                        $query->where('a', '=', $a)
                            ->orWhere('b', '=', $b);
                    })
                    ->where(function ($query) use ($c,$d) {
                        $query->where('c', '=', $c)
                            ->orWhere('d', '=', $d);
                    });

            */
            //     //[1, 2, 4]
            //     $waybills = k9DepartureScan::where('SCAN_SITE_CODE', $scan_site_id)->where('PRE_OR_NEXT_STATION_CODE', $next_site_id)->whereDate('SCAN_DATE', $date)->OrderBy('SCAN_DATE', 'DESC')->get();

            //     //[2]
            //     $already_departed = Waybill::where('scan_site_id', $scan_site_id)->where('next_site_id', $next_site_id)->whereDate('created_at', Carbon::today())->get('id');


            //    //[1, 4]
            //     $filtered_waybills = collect($waybills)->filter(function ($waybill, $key) use($already_departed) {
            //         return !($already_departed->contains($waybill->BILL_CODE));
            //     });


            //[2]
            $already_departed = Waybill::where('scan_site_id', $scan_site_id)
                ->where('next_site_id', $next_site_id)
                ->whereDate('created_at', Carbon::today())
                ->pluck('id'); //cancelled manifest / deleted too
            //[1, 2, 4]

            //is laravel case sensitive ?
            $query = K9DepartureScan::query();
            if ($scanner_id != null) {
                $query->where('SCAN_MAN_CODE', $scanner_id);
            }

            $waybills = $query->where('SCAN_SITE_CODE', $scan_site_id)
                ->where('PRE_OR_NEXT_STATION_CODE', $next_site_id)
                ->whereNotIn('BILL_CODE', $already_departed)
                ->where('SCAN_DATE', '>=', $start_date)
                ->where('SCAN_DATE', '<=', $end_date)
                ->get();

            //DUPLICATES HANDLED
            $filtered_waybills = $waybills->unique('BILL_CODE');

            //->OrderBy('SCAN_DATE', 'DESC')
            // ->whereDate('SCAN_DATE', $date)
            // unique('BILL_CODE');
            //  //[1, 4]
            //   $filtered_waybills = collect($waybills)->filter(function ($waybill, $key) use($already_departed) {
            //       return !($already_departed->contains($waybill->BILL_CODE));
            //   });


            return response()->json(['success' => true, 'message' => 'Retrieved successfully', 'data' => $filtered_waybills/*$waybills*/, 'request' => request()->all()]);
        } catch (Exception $ex) {

            return response()->json(['success' => false, 'message' => 'could not retrieve departure scans', 'data' => null, 'scan_site_id' => $scan_site_id, 'next_site_id' => $next_site_id, 'next_site' => $next_site->name]);
        }
    }



    public function getManifestDetails($manifest_id)
    {

        try {

            $manifest = $this->manifest_services->getManifest((int)$manifest_id);

            //TODO
            if($manifest === null)
            {
                abort(404);// what does this even do ?
            }


            return view('manifest.details', compact('manifest'));
        } catch (Exception $ex) {
            return redirect()->back()->withError('Manifest Not found!');
        }
    }

    public function getNipostManifestSummary($manifest_id)
    {

        try {

            $manifest = $this->manifest_services->getManifest((int)$manifest_id);
            return view('manifest.nipost-summary', compact('manifest'));
            // return view('manifest.maniifest-nipost', compact('manifest'));
        } catch (Exception $ex) {
            return redirect()->back()->withError("Manifest Not found!". $ex->getMessage());
        }
    }

    public function getNipostManifest($manifest_id)
    {

        try {

            $manifest = $this->manifest_services->getManifest((int)$manifest_id);
            // return view('manifest.nipost-summary', compact('manifest'));
            return view('manifest.maniifest-nipost', compact('manifest'));
        } catch (Exception $ex) {
            return redirect()->back()->withError("Manifest Not found!". $ex->getMessage());
        }
    }

    public function getMPSManifest($manifest_id)
    {
        try {

            $manifest = $this->manifest_services->getManifest((int)$manifest_id);
            return view('manifest.mps', compact('manifest'));
        } catch (Exception $ex) {
            return redirect()->back()->withError('Manifest Not found!'+ $ex->getMessage());
        }
    }



    //this should be dispatched Manifest
    public function getDepartureScansToDispatch()
    {
        try {

            $groups_id = request()->input('scan_groups');
            $groups = ScanTimestamp::whereIn('id', $groups_id)->get();



            //Assuming all groups are for the same next site
            //maybe you should use next_site from
            //[2]
            $already_departed = Waybill::where('scan_site_id', $groups[0]['scan_site_id'])
                ->where('next_site_id', $groups[0]['next_site_id'])
                ->whereDate('created_at', Carbon::today())
                ->pluck('id'); //cancelled manifest / deleted too

            //[1, 2, 4]

            //is laravel case sensitive ?
            $query = K9DepartureScan::query();
            // foreach($groups as $group) {

            $waybills = $query->where(function ($query) use ($groups, $already_departed) {
                $query->where('SCAN_MAN_CODE', $groups[0]['scanner_id'])
                    ->where('SCAN_SITE_CODE', $groups[0]['scan_site_id'])
                    ->where('PRE_OR_NEXT_STATION_CODE', $groups[0]['next_site_id'])
                    ->whereNotIn('BILL_CODE', $already_departed)
                    ->where('SCAN_DATE', '>=', $groups[0]['start_date'])
                    ->where('SCAN_DATE', '<=', $groups[0]['end_date']);
            });

            //Duplicate waybills unkor
            // if ($groups > 1) {
            //     $query->orWhere(
            //         function ($query) use ($groups, $already_departed) {
            //             $query->where('SCAN_MAN_CODE', $groups[1]['scanner_id'])
            //                 ->where('SCAN_SITE_CODE', $groups[1]['scan_site_id'])
            //                 ->where('PRE_OR_NEXT_STATION_CODE', $groups[1]['next_site_id'])
            //                 ->whereNotIn('BILL_CODE', $already_departed)
            //                 ->where('SCAN_DATE', '>=', $groups[1]['start_date'])
            //                 ->where('SCAN_DATE', '<=', $groups[1]['end_date']);
            //         }
            //     );
            // }

            $query->OrderBy('SCAN_DATE', 'DESC')->get(['BILL_CODE', 'SCAN_SITE_CODE', 'PRE_OR_NEXT_STATION_CODE', 'SCAN_DATE', 'SCAN_MAN_CODE', 'REGISTER_DATE', 'REGISTER_DATE', 'WEIGHT']); //get();
            //  $filtered_waybills = $waybills;

            // }
            /*
$query->where('SCAN_MAN_CODE', $group['scanner_id'])
				->where('SCAN_SITE_CODE', $group['scan_site_id'])
                ->where('PRE_OR_NEXT_STATION_CODE', $group['next_site_id'])
                ->whereNotIn('BILL_CODE', $already_departed)
                ->where('SCAN_DATE', '>=', $group['start_date'])
                ->where('SCAN_DATE', '<=', $group['end_date'])
                ->OrderBy('SCAN_DATE', 'DESC')->get();


                */
            $filtered_waybills = $waybills->unique('BILL_CODE');

            return response()->json(['success' => true, 'message' => 'Retrieved successfully', 'data' => $filtered_waybills/*$waybills*/, 'request' => request()->all()]);
        } catch (Exception $ex) {

            return response()->json(
                [
                    'success' => false, 'message' => 'could not retrieve departure scans', 'data' => $groups

                ]
            );
        }
    }


    public function getWaybillsInForDispatch()
    {
        try {

            $waybills_in = request()->input('waybills_in');
            $next_site_id =  request()->input('next_site_id');
            $scan_site_id =  Auth::user()->site_id;


            //maybe you should use next_site from
            //[2]
            $already_departed = Waybill::where('scan_site_id', $scan_site_id)
                ->where('next_site_id', $next_site_id)
                ->whereIn('id', $waybills_in)
                ->whereDate('created_at', Carbon::today())
                ->pluck('id'); //cancelled manifest / deleted too

            //[1, 2, 4]

            //is laravel case sensitive ?
            $query = K9DepartureScan::query();
            // foreach($groups as $group) {


            $waybills =  $query->where('SCAN_SITE_CODE', $scan_site_id)
                ->where('PRE_OR_NEXT_STATION_CODE', $next_site_id)
                ->whereIn('BILL_CODE', $waybills_in)
                ->where('SCAN_DATE', '>=', Carbon::today())
                ->where('SCAN_DATE', '<=', Carbon::today());

            $query->OrderBy('SCAN_DATE', 'DESC')->get(['BILL_CODE', 'SCAN_SITE_CODE', 'PRE_OR_NEXT_STATION_CODE', 'SCAN_DATE', 'SCAN_MAN_CODE', 'REGISTER_DATE', 'REGISTER_DATE']); //get();
            $filtered_waybills = $waybills;

            // }
            /*
$query->where('SCAN_MAN_CODE', $group['scanner_id'])
				->where('SCAN_SITE_CODE', $group['scan_site_id'])
                ->where('PRE_OR_NEXT_STATION_CODE', $group['next_site_id'])
                ->whereNotIn('BILL_CODE', $already_departed)
                ->where('SCAN_DATE', '>=', $group['start_date'])
                ->where('SCAN_DATE', '<=', $group['end_date'])
                ->OrderBy('SCAN_DATE', 'DESC')->get();


                */
            $filtered_waybills = $waybills;

            return response()->json(['success' => true, 'message' => 'Retrieved successfully', 'data' => $filtered_waybills/*$waybills*/, 'request' => request()->all()]);
        } catch (Exception $ex) {

            return response()->json(
                [
                    'success' => false, 'message' => 'could not retrieve departure scans', 'data' => []
                ]
            );
        }
    }

    public function manifestCompliance()
    {
        return view('manifest.compliance');
    }

    //Talks to Both K9 & K9X
    public function getSiteManifestCompliance()
    {
        try {

            $data['sites'] = Site::get(['id', 'name']);
            $data['dispatch_record'] = Waybill::whereDate('created_at', Carbon::today())->groupBy('scan_site_id')->select('scan_site_id', DB::raw('count(id) as total'))->get();
            $data['k9_departure_record'] = K9DepartureScan::whereDate('SCAN_DATE', Carbon::today())->groupBy('SCAN_SITE_CODE')->select('SCAN_SITE_CODE', DB::raw('count(BILL_CODE) as total'))->get();

            //To get the acknwoledged count with the way the DB is structured now is hard
            $data['acknowledged_record'] = Waybill::whereDate('created_at', Carbon::today())->where('status', 1)->groupBy('next_site_id')->select('next_site_id', DB::raw('count(id) as total'))->get();
            //Check this query well

            //    :where('SCAN_SITE_CODE', $manifest->next_site_id)
            //         ->where('PRE_OR_NEXT_STATION_CODE', $manifest->scan_site_id)
            //         ->whereDate('SCAN_DATE', $date)
            // ->whereIn('BILL_CODE', $manifest_waybills_on_k9x->pluck('id'))
            // ->OrderBy('SCAN_DATE', 'DESC')->get();
            $data['k9_arrival_record'] = K9ArrivalScan::whereDate('SCAN_DATE', Carbon::today())->groupBy('SCAN_SITE_CODE')->select('SCAN_SITE_CODE', DB::raw('count(BILL_CODE) as total'))->get();


            return response()->json(['success' => true, 'message' => 'Manifest compliance data Retrieved successfully', 'data' => $data, 'request' => request()->all()]);
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false, 'message' => 'could not retrieve manifest compliance', 'data' => []
                ]
            );
        }
    }



    public function getCurrentDayDepartureListForSite(K9Services $k9Services)
    {
        // $filters = request()->input('filters');


        // $filters = [
        //     'status' => (int)request()->input('status'),
        //     'scan_site_id' => (int)request()->input('scan_site_id'),
        //     'next_site_id' => (int)request()->input('next_site_id'),
        //     'start_date' => Carbon::parse(request()->input('start_date')),
        //     'end_date' => Carbon::parse(request()->input('end_date')),
        //     'user' => Auth::user()
        // scanner_id
        // ];


        $filters = [
            'scan_site_id' => Auth::user()->site->id,
            'next_site_id' => (int)request()->input('next_site_id'),
            'waybills' => null,
            'scanner_id' => (int)request()->input('scanner_id'),
            'start_date' => Carbon::parse(request()->input('start_date')),
            'end_date' => Carbon::parse(request()->input('end_date'))

        ];
        $departure_list_query = $k9Services->getCurrentDayDepartureListForSiteQuery($filters);
        return Datatables::of($departure_list_query)
            ->addIndexColumn()
            ->make(true);
    }



    public function getManifestWaybills()
    {
        try {

            $data['manifest_id'] = (int) request()->input('manifest_id');
            $data['status'] =  request()->input('status');
            $waybills = $this->manifest_services->getManifestWaybills($data);

            return response()->json(['success' => true, 'message' => 'Manifest Waybills compliance data Retrieved successfully', 'data' => $waybills, 'request' => request()->all()]);
        } catch (Exception $ex) {
            return response()->json(
                [
                    'success' => false, 'message' => 'could not departure List\n' . $ex->getMessage(), 'data' => []
                ]
            );
        }
    }


    public function getIncomingManifestGroup($data)
    {
        $data['sites'] = Site::get(['id', 'name']);
        $current_site = Auth::user()->site->id;
    }

}
