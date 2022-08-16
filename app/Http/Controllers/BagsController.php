<?php

namespace App\Http\Controllers;

use App\Bag;
use Exception;
use App\Transfer;
use Carbon\Carbon;
use App\Enums\BagType;
use App\Enums\BagStatus;
use Illuminate\Http\Request;
use App\Enums\TransferStatus;
use App\Services\BagServices;
use App\Services\SiteServices;
use App\Exceptions\BagException;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class BagsController extends Controller
{
    public $bag_services = null;
    public $site_services = null;
    public function __construct(BagServices $bag_services,  SiteServices $site_services)
    {
        $this->middleware('auth');
        $this->bag_services = $bag_services;
        $this->site_services = $site_services;
        $this->current_user = Auth::user();
    }



    public function index()
    {
        if (request()->ajax()) {
            $filters = [
                'status' => (int)request()->input('status'),
                'next_or_current_site_id' => (int)request()->input('next_or_current_site_id'),
                'type' => (int)request()->input('type'),
                'user' => Auth::user()
            ];

        	$query = $this->bag_services->getBagsQuery($filters);
            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('display_code', function (Bag $bag) {
                $color = 'btn-default';
                 $html = $bag->displayId;

                 return $html;
             })
             ->addColumn('display_status', function (Bag $bag) {
                $color = '';
                $html = "";
                if ($bag->status === BagStatus::ON_TRANSFER) {
                    $color = 'badge-default';
                } else if ($bag->status === BagStatus::AVAILABLE_FOR_USE) {
                    $color = 'badge-success';
                } else if ($bag->status === BagStatus::LOST) {
                    $color = 'badge-danger';
                } else if ($bag->status === BagStatus::DAMAGED) {
                    $color = 'badge-warning';
                } else {
                    $color = 'badge-dark';
                }

                 $html = "<span class='badge $color'>" . BagStatus::STATUS_TEXT[$bag->status]."</span>";

                 return $html;
             })
             ->addColumn('current_manifest_or_transfer_display', function (Bag $bag) {
                $color = 'badge-dark';
                $html = "<span class='badge $color'>--</span>";
                if($bag->current_manifest_or_transfer_id != null)
                {
                    $html =$bag->current_manifest_or_transfer_id;
                }
                 return $html;
             })
            ->addColumn('action', function (Bag $bag) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
              </div>';

                return $html;
            })
            ->rawColumns(['display_code', 'display_status', 'current_manifest_or_transfer_display', 'action'])
            ->make(true);
        }
        else
        {
            $sites = $this->site_services->getAllSites();
            return view('bags.index', compact('sites', 'sites'));
        }
    }


    public function getAvailableBagsInSite()
    {
        $site_id = Auth::user()->site_id;
        try {

           $bags = $this->bag_services->getBags(['next_or_current_site_id' => $site_id, 'status' => BagStatus::AVAILABLE_FOR_USE]);

            return response()->json(['success' => true, 'message' => 'Available bags Retrieved Successfully', 'data' => $bags]);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'message' => 'Could not retrieve avaiable bags \n' . $ex->getMessage()]);
        }
    }


    public function register()
    {
        $sites = $this->site_services->getAllSites();
        return view('bags.register', compact('sites'));
    }


    public function registerBags(Request $request)
    {
        // dd(request()->input());

        $validated = $request->validate([
            'site_id' => 'required|min:1',
            'type' => 'required|min:0',
            'bag_numbers' => 'required'
        ]);

          $data['bag_numbers'] = request()->input('bag_numbers');
          $data['site_id'] = request()->input('site_id');
          $data['bag_type'] = request()->input('type');
          $data['created_by'] = Auth::id();
            // $data = [];


            try {

                    // $result = $this->bag_services->registerAutoNumbering($data);
                    return redirect()->back()->withSuccess("Bags Registered Successfully");
            } catch (BagException $ex) {

                return redirect()->back()->withError($ex->getMessage());
            } catch (Exception $ex) {

                return redirect()->back()->withError($ex->getMessage());
            }

            }



    public function transfer_view()
    {
        $sites = $this->site_services->getAllSites();
        return view('bags.transfer-bags', compact('sites'));
    }

    public function transfer()
    {

    }

    public function transferBags(Request $request)
    {
        // dd(request()->input());

        $validated = $request->validate([
            'site_id' => 'required|min:1',
            'bag_numbers' => 'required'
        ]);

          $data['bag_numbers'] = request()->input('bag_numbers');
          $data['destination_site_id'] = request()->input('site_id');
          $data['user'] = Auth::user();
          $result = $this->bag_services->transfer($data);
          if ($result['success'] == true)
          {
              return redirect()->back()->withSuccess("Bags transferred successfully");

          }
         else
         {
             return redirect()->back()->withError($result['message']);

         }

}

    public function  acknowledgeTransfer()
    {

        try {

            $data['transfer_id'] = request()->input('transfer_id');
            $data['user'] = Auth::user();
            $result = $this->bag_services->acknowledgeTransfer($data);
            if ($result['success']) {
                return redirect()->route('transfers.incoming')->withSuccess("{$result['message']}");
            } else {
                return redirect()->back()->with('error', "{$result['message']}");
            }
        } catch (Exception $ex) {
            //Log error
            return redirect()->back()->with('error', "Transfer acknowledgement failed!, Please try again");
        }
    }

    public function getIncomingTransfers()
    {  if (request()->ajax()) {


        $filters = [
            'departure_site' => (int)request()->input('departure_site'),
            'user' => Auth::user()
        ];

        $transfers_query = $this->bag_services->getIncomingTransfers($filters);
        return Datatables::of($transfers_query)
            ->addIndexColumn()
            ->addColumn('dispatched', function (Transfer $transfer) {
                $html = '<div class="text-darker">By ' . $transfer->created_by_user->name.'</div><span class="">' . $transfer->created_at->diffForHumans() . '</span>
                <div class="text-green">' . $transfer->created_at->format('Y-m-d , g:i A') . '</div>';
                return $html;
            })
            ->addColumn('status_label', function (Transfer $transfer) {
                $html = "";
                if ($transfer->status === TransferStatus::IN_TRANSIT) {
                    if ($transfer->flagged === 1) {

                        $html .= '<i class="fas fa-flag text-danger" ></i>';
                    }
                    $html .= '<span class="badge badge-light">' . TransferStatus::STATUS_TEXT[TransferStatus::IN_TRANSIT] . '<span>';
                } else if ($transfer->status === TransferStatus::ACKNOWLEDGED) {
                    $html .= '<span class="badge badge-success">' . TransferStatus::STATUS_TEXT[TransferStatus::ACKNOWLEDGED] . '</span>';
                } else if ($transfer->status === TransferStatus::CANCELLED) {
                    $html .= '<span class="badge badge-primary">' . TransferStatus::STATUS_TEXT[TransferStatus::CANCELLED] . '</span>';
                } else if ($transfer->status === TransferStatus::PARTIALLY_RECEIVED) {
                    $html .= '<span class="badge badge-default">' . TransferStatus::STATUS_TEXT[\App\Enums\TransferStatus::PARTIALLY_RECEIVED] . '</span>';
                } else {
                    $html .= '<span class="badge badge-dark">Unknown</span>';
                }

                return $html;
            })
            ->addColumn('dispatched_bags_count', function (Transfer $transfer) {
                $html = '<button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills sent" data-container="body" data-animation="true">' . $transfer->transfer_bags_count . '</button>';
                return $html;
            })
            ->addColumn('action_buttons', function (Transfer $transfer) {
                $html = '';
                return $html;
            })
            ->addColumn('action', function (Transfer $transfer) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item change-group" href="">
                        <i class="fas fa-info-circle"></i>
                        <span class="nav-link-text">View Details</span>
                      </a>
                      ';
                $html .= '<a class="dropdown-item change-group" href="'.route('transfers.acknowledgeView', $transfer->id).'">
                <i class="fas fa-check-double text-success"></i>
                <span class="nav-link-text">Acknowledge</span>
            </a>
                </div>
              </div>';

                return $html;
            })
            ->rawColumns(['dispatched', 'status_label',  'action', 'dispatched_bags_count','action_buttons'])
            ->make(true);
        }
        else
        {
            $from_sites = $this->site_services->getAllSites();
            return view('bags.incoming-transfers', compact('from_sites'));
        }
    }
    public function getOutgoingTransfers()
    {
        if (request()->ajax()) {


            $filters = [
                'departure_site' => (int)request()->input('departure_site'),
                'user' => Auth::user()
            ];

            $transfers_query = $this->bag_services->getOutgoingTransfers($filters);
            return Datatables::of($transfers_query)
                ->addIndexColumn()
                ->addColumn('dispatched', function (Transfer $transfer) {
                    $html = '<div class="text-darker">By ' . $transfer->created_by_user->name.'</div><span class="">' . $transfer->created_at->diffForHumans() . '</span>
                    <div class="text-green">' . $transfer->created_at->format('Y-m-d , g:i A') . '</div>';
                    return $html;
                })
                ->addColumn('status_label', function (Transfer $transfer) {
                    $html = "";
                    if ($transfer->status === TransferStatus::IN_TRANSIT) {
                        if ($transfer->flagged === 1) {

                            $html .= '<i class="fas fa-flag text-danger" ></i>';
                        }
                        $html .= '<span class="badge badge-light">' . TransferStatus::STATUS_TEXT[TransferStatus::IN_TRANSIT] . '<span>';
                    } else if ($transfer->status === TransferStatus::ACKNOWLEDGED) {
                        $html .= '<span class="badge badge-success">' . TransferStatus::STATUS_TEXT[TransferStatus::ACKNOWLEDGED] . '</span>';
                    } else if ($transfer->status === TransferStatus::CANCELLED) {
                        $html .= '<span class="badge badge-primary">' . TransferStatus::STATUS_TEXT[TransferStatus::CANCELLED] . '</span>';
                    } else if ($transfer->status === TransferStatus::PARTIALLY_RECEIVED) {
                        $html .= '<span class="badge badge-default">' . TransferStatus::STATUS_TEXT[\App\Enums\TransferStatus::PARTIALLY_RECEIVED] . '</span>';
                    } else {
                        $html .= '<span class="badge badge-dark">Unknown</span>';
                    }

                    return $html;
                })
                ->addColumn('dispatched_bags_count', function (Transfer $transfer) {
                    $html = '<button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of waybills sent" data-container="body" data-animation="true">' . $transfer->transfer_bags_count . '</button>';
                    return $html;
                })
                ->addColumn('action_buttons', function (Transfer $transfer) {
                    $html = '';
                    return $html;
                })
                ->addColumn('action', function (Transfer $transfer) {
                    $html = '<div class="dropdown">
                    <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-h"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                </a>
                    <a class="dropdown-item change-group" href="' . route('transfer.details', ['transfer_id' => $transfer->id]) . '">
                    <span class="nav-link-text">View Details</span>';
                $html .= '
                    </a>
                    </div>
                  </div>';

                    return $html;
                })
                ->rawColumns(['dispatched', 'status_label',  'action', 'dispatched_bags_count','action_buttons'])
                ->make(true);
            }
            else
            {
                $to_sites = $this->site_services->getAllSites();
                return view('bags.outgoing-transfers', compact('to_sites'));
            }
    }


    public function acknowledgeView($transfer_id)
    {
        try {
            $transfer = Transfer::with('transfer_bags')->where('id', (int)$transfer_id)->first();
            return view('bags.acknowledge-transfer', compact('transfer'));
        } catch (Exception $ex) {
            return redirect()->back()->with('error', "Failed to retreive transfer");
        }
    }

    public function getOnSiteBags()
    {
        if (request()->ajax()) {
            $filters = [
                'status' => (int)request()->input('status'),
                'next_or_current_site_id' => Auth::user()->site_id,
                'type' => (int)request()->input('type'),
                'user' => Auth::user()
            ];

        	$query = $this->bag_services->getBagsQuery($filters);
            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('display_code', function (Bag $bag) {
                $color = 'btn-default';
                 $html = $bag->displayId;

                 return $html;
             })
             ->addColumn('display_status', function (Bag $bag) {
                $color = '';
                $html = "";
                if ($bag->status === BagStatus::ON_TRANSFER) {
                    $color = 'badge-default';
                } else if ($bag->status === BagStatus::AVAILABLE_FOR_USE) {
                    $color = 'badge-success';
                } else if ($bag->status === BagStatus::LOST) {
                    $color = 'badge-danger';
                } else if ($bag->status === BagStatus::DAMAGED) {
                    $color = 'badge-warning';
                } else {
                    $color = 'badge-dark';
                }

                 $html = "<span class='badge $color'>" . BagStatus::STATUS_TEXT[$bag->status]."</span>";

                 return $html;
             })
             ->addColumn('current_manifest_or_transfer_display', function (Bag $bag) {
                $color = 'badge-dark';
                $html = "<span class='badge $color'>--</span>";
                if($bag->current_manifest_or_transfer_id != null)
                {
                    $html =$bag->current_manifest_or_transfer_id;
                }
                 return $html;
             })
            ->addColumn('action', function (Bag $bag) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
              </div>';

                return $html;
            })
            ->rawColumns(['display_code', 'display_status', 'current_manifest_or_transfer_display', 'action'])
            ->make(true);
        }
        else
        {
            $sites = $this->site_services->getAllSites();
            return view('bags.onsite', compact('sites', 'sites'));
        }
    }

    public function track()
    {
        abort(404, 'This is the track controller');
    }

    public function getTransferDetails($transfer_id)
    {

        try {

            $transfer = $this->bag_services->getTransfer((int)$transfer_id);

            //TODO
            if($transfer === null)
            {
                abort(404);// what does this even do ?
            }


            return view('bags.transfer-details', compact('transfer'));
        } catch (Exception $ex) {
            return redirect()->back()->withError('Transfer Not found!');
        }
    }


}
