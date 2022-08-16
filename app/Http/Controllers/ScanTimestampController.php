<?php

namespace App\Http\Controllers;

use Exception;
use App\Waybill;
use Carbon\Carbon;
use App\ScanTimestamp;
use App\K9DepartureScan;
use Illuminate\Http\Request;
use App\Services\SiteServices;
use Yajra\DataTables\DataTables;
use App\Services\ManifestServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\ScanTimestampServices;
use App\Exceptions\ScanTimeGroupingException;

class ScanTimestampController extends Controller
{
    public $manifest_services = null;
    public $site_services = null;
    public $scan_timestamp_services = null;
    public function __construct(ManifestServices $manifest_services, SiteServices $site_services, ScanTimestampServices $scan_timestamp_services)
    {
        $this->middleware('auth');
        $this->manifest_services = $manifest_services;
        $this->site_services = $site_services;
        $this->scan_timestamp_services = $scan_timestamp_services;
    }

    public function index()
    {
        // $scan_timestamps =  $this->scan_timestamp_services->getScanTimestamps();

        $site_list = $this->manifest_services->getPossibleNextSitesFor(Auth::user()->site);
        $site_users = $this->site_services->getSiteUsers(Auth::user()->site->id)->pluck('name', 'id');
        // dd($site_list);
        return view('manifest.scan-timestamp', compact('site_list', 'site_users'));
    }


    public function endScan($groups_id)
    {

        try {

            $data['group_id'] = (int) $groups_id; //request()->input('group_id');
            $data['user_id'] = Auth::id();
            $data['end_date'] = Carbon::parse(request()->input('end_date'));
            $this->scan_timestamp_services->endScan($data);
            return response()->json(['success' => true, 'data' => $data, 'message' => 'Scan Ended  successfully']);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'data' => $data, 'message' => $ex->getMessage()]);
        }
    }

    public function cancelScan($groups_id)
    {

        try {

            $data['group_id'] = (int) $groups_id; //request()->input('group_id');
            $data['user_id'] = Auth::id();
            // $data['end_date'] = Carbon::parse(request()->input('end_date'));
            $this->scan_timestamp_services->deleteGroup($data);
            return response()->json(['success' => true, 'data' => $data, 'message' => 'Group Cancelled successfully']);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'data' => $data, 'message' => $ex->getMessage()]);
        }
    }

    public function getTimestamps()
    {
        $filters = [
            'scan_site_id' => Auth::user()->site->id,
            'scanner_id' => (int)request()->input('scanner_id'),
            'user_id' => Auth::id(),
            'next_site_id' => (int)request()->input('next_site_id'),
            'start_date' => Carbon::parse(request()->input('start_date')),
            'end_date' => Carbon::parse(request()->input('end_date'))
        ];
        //
        $scan_timestamps_query =  $this->scan_timestamp_services->getScanTimestampsQuery($filters);
        return Datatables::of($scan_timestamps_query)
            ->addColumn('next_site_name', function (ScanTimestamp $scan_timestamp) {


                return $scan_timestamp->next_site->name;
            })
            // ->addIndexColumn()
            ->addColumn('start_date', function (ScanTimestamp $scan_timestamp) {

                $start_date = "";
                $end_date  = ""; //Carbon::today()->endOfDay()->subMinute(10)->format('h:i:s A');

                $html2 = "<b class='btn btn-sm badge badge-default'> ";
                $query =  K9DepartureScan::query(); //->distinct();
                $query->where('SCAN_SITE_CODE', $scan_timestamp['scan_site_id'])
                    //     //Only the person who createds the group can do the k9 scan
                    ->where('PRE_OR_NEXT_STATION_CODE', $scan_timestamp['next_site_id'])
                    //     // ->whereNotIn('BILL_CODE', $already_departed)
                    ->where('SCAN_DATE', '>=', $scan_timestamp['start_date']);
                if (!is_null($scan_timestamp->end_date)) {
                    //time zone too
                    $query->where('SCAN_DATE', '<=', $scan_timestamp['end_date']); // null given erro
                    $end_date = $scan_timestamp->end_date->format('h:i:s A');
                }
                if ($scan_timestamp['scanner_id'] != null) // Not yet tested
                {
                    $query->where('SCAN_MAN_CODE', $scan_timestamp['scanner_id']);
                }

                $html2 .= $query->count('BILL_CODE') . "</b>";
                if (is_null($scan_timestamp->end_date)) {
                    $html2 = "";
                }
                $start_date =  $scan_timestamp->start_date->format('h:i:s A');
                //   return $html2;
                return "<span class='text-green'>" . $start_date . "</span><span class='text-red'> =======> </span><span class='text-green'>" . $end_date . "</span><br/>" . $html2;
            })
            ->addColumn('scanner_name', function (ScanTimestamp $scan_timestamp) {
                $html = "";
                if (is_null($scan_timestamp->scanner_id)) {
                    $html .= 'Every Scanner';
                } else {
                    $html .= " " . $scan_timestamp->scanner->name . "";
                }
                return $html;
            })
            // ->addColumn('waybills_count', function (ScanTimestamp $scan_timestamp) {
            //     $html = "<b class='text-warning'> ";
            //    $query =  K9DepartureScan::query();
            //    $query->where('SCAN_SITE_CODE', $scan_timestamp['scan_site_id'])
            // //     //Only the person who createds the group can do the k9 scan
            //     ->where('PRE_OR_NEXT_STATION_CODE',$scan_timestamp['next_site_id'])
            // //     // ->whereNotIn('BILL_CODE', $already_departed)
            //     ->where('SCAN_DATE', '>=',$scan_timestamp['start_date']);
            //     if(!is_null($scan_timestamp->end_date))
            //     {
            //         $query->where('SCAN_DATE', '<=',$scan_timestamp['end_date']); // null given erro

            //     }
            //     if($scan_timestamp['scanner_id'] != null) // Not yet tested
            //     {
            //         $query->where('SCAN_MAN_CODE',$scan_timestamp['scanner_id']);

            //     }

            //     $html .= $query->count()."</b>";
            //      return $html;
            //  })
            ->addColumn('end_scan', function (ScanTimestamp $scan_timestamp) {
                $html = '';
                if (is_null($scan_timestamp->end_date)) {
                    $html = '<button type="button" data-group="' . $scan_timestamp->id . '" class="btn btn-sm btn-warning btn-tooltip end_scan" data-toggle="tooltip" data-placement="left" title="End the running scan" data-container="body" data-animation="true">End</button>';
                } else {
                    $html = '<button type="button" data-group="' . $scan_timestamp->id . '"class="btn btn-sm btn-primary btn-tooltip dispatch" data-toggle="tooltip" data-placement="left" title="Dispatch the waybills in this  group" data-container="body" data-animation="true">create manifest</button>';
                }

                return $html;
            })
            ->addColumn('action', function (ScanTimestamp $scan_timestamp) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                <a class="dropdown-item cancel_scan" data-group="' . $scan_timestamp->id . '"href="#">Delete Group</a>';
                $html .= '
                </div>
              </div>';
                return $html;
            })
            ->rawColumns(['end_scan', 'action', 'waybills_count', 'start_date', 'scanner_name'])
            ->make(true);
    }

    // public function getTimestampsAsList()
    // {
    //     $filter['next_site_id'] = request()->input('next_site_id');
    //     $filter['scan_site_id'] = Auth::user()->site->id;
    //     $filter['user_id'] = Auth::id();
    //    $scan_timestamps =   $this->scan_timestamp_services->getScanTimestampsAsList($filter);
    //      return response()->json(['success' => true, 'data' => $scan_timestamps, 'message' => 'Groups retrieved successfully']);
    // }


    // ----------------------------------STORE------------------

    public function store()
    {
        //Get the Scan timestamps
        // $data = []; // your request variables
        // $this->scan_timestamp_services->createScanTimestamp($data);
        $data = [];
        try {

            //Validation
            $data['created_by'] = Auth::user()->id;
            $data['updated_by'] =  Auth::user()->id;
            $data['scan_site_id'] = (int)Auth::user()->site->id;
            $data['next_site_id'] = (int)request()->input('next_site_id');
            $data['tag'] = request()->input('tag');
            // $data['seal_number'] = request()->input('seal_number');
            $data['start_date'] = Carbon::parse(request()->input('start_date'));
            if (request()->input('period_type') == 'auto_period') {
                $data['end_date'] = null;
            } else {
                $data['end_date'] = Carbon::parse(request()->input('end_date'));
            }

            if (is_null(request()->input('scanner_id'))) {
                $data['scanner_id'] = null;
            } else {
                $data['scanner_id'] = (int)request()->input('scanner_id');
            }

            $result = $this->scan_timestamp_services->createScanTimestamp($data);

            return response()->json(['success' => true, 'data' => $data, 'message' => 'Group Saved successfully']);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'data' => $data, 'message' => $ex->getMessage()]);
        }
    }


    //-------------------END OF CRATE GROUP
    //getPeriodScans -> better name
    public function getDepartureScansToDispatch()
    {
        try {

            //what if you just send the group info?
            $groups_id = request()->input('scan_groups'); //make this just one group
            $group = ScanTimestamp::where('id', $groups_id[0])->first();

            $already_departed = Waybill::query()
                ->where('scan_site_id', $group->scan_site_id)
                ->where('next_site_id', $group->next_site_id)
                //use this time zone here
                ->whereDate('created_at', Carbon::today()) // you might want to be specific with the time, you might want to be storing k9_scan_date too, and k9 arrived_date
                ->pluck('id'); //cancelled manifest / deleted too

            $waybills = null;
            $conditions = [];
            $conditions[] = ['TAB_SCAN_SEND.SCAN_DATE', '>=', $group->start_date];
            $conditions[] = ['TAB_SCAN_SEND.SCAN_DATE', '<=', $group->end_date];
            $conditions[] = ['TAB_SCAN_SEND.PRE_OR_NEXT_STATION_CODE', '=', $group->next_site_id];
            $conditions[] = ['TAB_SCAN_SEND.SCAN_SITE_CODE', '=' , $group->scan_site_id];
            if ($group->scanner_id != null) // Not yet tested
            {
                $conditions[] = ['TAB_SCAN_SEND.SCAN_MAN_CODE', '=', $group->scanner_id];
            }
            $waybills = DB::connection('K9_server')->
                        table('TAB_SCAN_SEND')->
                        leftJoin('TAB_BILL', 'TAB_SCAN_SEND.BILL_CODE', '=', 'TAB_BILL.BILL_CODE')->
                        where($conditions)->
                        whereNotIn('TAB_SCAN_SEND.BILL_CODE', $already_departed)->
                        select( 'TAB_SCAN_SEND.BILL_CODE', 'TAB_SCAN_SEND.SCAN_SITE_CODE', 'TAB_SCAN_SEND.PRE_OR_NEXT_STATION_CODE', 'TAB_SCAN_SEND.SCAN_DATE', 'TAB_SCAN_SEND.SCAN_MAN_CODE','TAB_SCAN_SEND.WEIGHT', 'TAB_SCAN_SEND.REGISTER_DATE', 'TAB_BILL.BILL_WEIGHT','TAB_BILL.MAIN_CODE','TAB_BILL.PIECE_NUMBER','TAB_BILL.GOODS_TYPE_CODE','TAB_BILL.REGISTER_SITE_CODE','TAB_BILL.SEND_MAN_COMPANY')->
                        get();


            return response()->json(['success' => true, 'message' => 'Retrieved successfully', 'data' => $waybills/*$waybills*/, 'request' => request()->all(), 'group' => $group]);
        } catch (Exception $ex) {

            return response()->json(
                [
                    'success' => false, 'message' => 'could not retrieve departure scans', 'data' => $group,
                    'error' => $ex->getMessage()

                ]
            );
        }
    }


    public function timeTeller()
    {
    }
}
