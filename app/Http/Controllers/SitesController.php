<?php
namespace App\Http\Controllers;
use App\Site;
use App\State;
use Exception;
use App\SiteType;
use App\Imports\SiteImport;
use Illuminate\Http\Request;
use App\Services\SiteServices;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;



class SitesController extends Controller
{
    public $site_services = null;

    public function __construct(SiteServices $site_services)
    {
        $this->middleware('auth');
        $this->site_services = $site_services;
    }

        //Shows Listing of all Sites
        public function index() {
            $state = $this->site_services->getStates();
            $site_type = $this->site_services->getSiteTypes();
            $sites =  $this->site_services->getSites();
            return view('sites.index', compact('sites'))
            ->with('state', $state)
            ->with('site_type', $site_type);
        }



        public function import(){
            return view('sites.import');
        }


        public function getSiteUsers($site_id)
        {
            try {

                $users = $this->site_services->getSiteUsers((int)$site_id);
                // dd($manifest);
                return response()->json(['success' => true, 'message' => 'Site Users Retrieved Successfully', 'data' => $users]);
            }
            catch(Exception $ex)
            {
                return response()->json(['success' => false, 'message' => 'Site Users record not found\n'.$ex->getMessage()]);
            }
        }

        public function getSites()
        {
            $sites_query =  $this->site_services->getSitesQuery();
            return Datatables::of($sites_query)
                ->addIndexColumn()
                ->addColumn('is_a_test_site', function (Site $site) {
                    $html ='';
                    if($site->is_a_test_site)
                    {
                        $html.='YES';
                    }
                    else {
                        $html.='NO';
                    }

                    return $html;
                })
                ->addColumn('is_a_franchise', function (Site $site) {
                    $html ='';
                    if($site->is_a_franchise)
                    {
                        $html.='YES';
                    }
                    else {
                        $html.='NO';
                    }

                    return $html;
                })
                ->addColumn('action', function (Site $site) {
                    $html = '<div class="dropdown">
                    <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-h"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">';
                    $html .= '
                    <a  href="javascript:void(0)" class="dropdown-item site-details" data-site="'.json_encode($site->id).'"   data-username="'.json_encode($site->name).'"data-roles="'.json_encode($site->name).'">Details</a>
                    <a href="javascript:void(0)" data-toggle="modal" data-id="'.json_encode($site->id).'"  data-target="#editSiteModal" class="dropdown-item site-details editSite" >Edit</a>
                    </div>
                  </div>';
                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
               }

                public function edit($id)
                {
                    $site = Site::with('state:id,name')->with('siteType:id,name')->find($id);
                     return response()->json($site);
                }

                public function store(Request $request)
                {
                        $validator = Validator::make($request->all(), [
                            'id' => 'required',
                            'name' => 'required',
                            'address' => 'required',
                            'state_id' => 'required',
                            'is_a_franchise' => 'required',
                        ]);

                        if ($validator->fails())
                        {
                            return Response::json(['errors' => $validator->errors()]);
                        }

                        Site::updateOrCreate(
                        ['id' => $request->id],
                        ['name' => $request->name,
                        'is_a_franchise' => $request->is_a_franchise,
                        'state_id' => $request->state_id,
                        'address' => $request->address,
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                        ]
                     );
                    return response()->json(['success' => true, 'message' => 'Site Updated Successfully']);
                }

}
