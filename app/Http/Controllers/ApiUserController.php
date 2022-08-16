<?php

namespace App\Http\Controllers;

use Exception;
use App\Api_user;
use Facade\FlareClient\Api;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\State;



class ApiUserController extends Controller
{
    public $length_of_string = 15;
    public function index()
    {
        return view('api.users.index');
    }

    public function getApiUsersData()
      {
           return Api_user::orderBy('created_at','desc')->get();

      }



    public function getApiUser(Request $request)
    {
        if($request->ajax()){
        $api_user = $this->getApiUsersData();
        return DataTables::of($api_user)
            ->addIndexColumn()
            ->addColumn('Actions', function($api_user) {
                return
        '<div class="dropdown">
        <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-h"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        <a  class="dropdown-item user-group" id="getEditedApiUser" data-id="'.$api_user->id.'">Edit</a>
        <a  class="dropdown-item user-group" id="getEditedResetKeyApiUser" data-id="'.$api_user->id.'">Reset Key</a>
        </div>
      </div>';
                })
            ->rawColumns(['Actions'])
            ->make(true);
    }
}



    public function store(Api_user $apiUser, Request $request)
    {

       $validator = Validator::make($request->all(), [
            'id' => 'required|unique:api_users',  //id represents the app code
            'name' => 'required|string',
            'access_type' => 'required|string',
            'is_active' => 'required|string'
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()]);
        }

        $apiUser->id = $request->id;       //id represents the app code
        $apiUser->name = $request->name;
        $apiUser->access_type = $request->access_type;
        $apiUser->is_active = $request->is_active;
        $apiUser->api_token = $this->createToken($this->length_of_string);
        $apiUser->save();
        return response()->json(['success'=>'Api user has been successfully registered !']);
    }


            public function createToken($length_of_string)
            {
                return Str::random($this->length_of_string);
            }


            public function edit($id)
            {
                $api_user = Api_user::find($id);
                $html =
        '<div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">App Code</label>
                <input type="text" class="form-control" required readonly  name="id" id="editid" value="'.$api_user->id.'">
                <span class="text-danger">
                <strong id="id-error"></strong>
                </span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label" >Company Name</label>
                <input type="text" class="form-control" required name="name" id="editname" value="'.$api_user->name.'">
                <span class="text-danger">
                <strong id="name-error"></strong>
                </span>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Access Type</label>
                <select type="text" required class="form-control" name="access_type"  id="editaccess_type">
                <option disabled class="form-control">Please Select Access Type</option>
                <option selected value="'.$api_user->access_type.'">'.$api_user->access_type.'</option>
                <option value="test"  class="form-control">Test</option>
                <option value="production"  class="form-control">Production</option>
                </select>
                <span class="text-danger">
                <strong id="access_type-error"></strong>
                </span>
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Status</label>
                <select type="text" required class="form-control" name="status" id="editis_active">
                <option selected value="'.$api_user->is_active.'">'.$api_user->is_active.'</option>
                   <option disabled  class="form-control">Please Account Status</option>
                    <option value="deactivated"  class="form-control">Deactivated</option>
                    <option value="activated"  class="form-control">Activated</option>
                </select>
                <span class="text-danger">
                <strong id="is_active-error"></strong>
                </div>
            </div>
          </div>';
            return response()->json(['html'=>$html]);
            }


            public function update(Request $request, $id)
            {
                $validator = Validator::make($request->all(), [
                    'id' => 'required|string',  //id represents the app code
                    'name' => 'required|string',
                    'access_type' => 'required|string',
                    'is_active' => 'required|string'
                ]);
                if ($validator->fails())
                {
                    return Response::json(['errors' => $validator->errors()]);
                }
                $apiUser = Api_User::find($id);
                $apiUser->id = $request->id;       //id represents the app code
                $apiUser->name = $request->name;
                $apiUser->access_type = $request->access_type;
                $apiUser->is_active = $request->is_active;
                $apiUser->update();
                return response()->json(['success'=>'Successfully updated Api User Details']);
            }


            public function editApiKey($id)
            {
                $api_user = Api_user::find($id);
                return response()->json($api_user);
            }




            public function updateApiKey(Request $request, $id)
                {
                    $validator = Validator::make($request->all(), ['password' => 'required|string',]);
                    if ($validator->fails())
                    {
                        return Response::json(['errors'=>'The Password field is empty']);
                    }
                        if(!Hash::check($request->password, Auth::user()->password))
                            {
                                return Response::json(['errors'=>'Incorrect User Password Entered' ]);
                            }
                            else
                            {
                                $apiUser = Api_User::find($id);
                                $apiUser->api_token = $this->createToken($this->length_of_string);
                                $apiUser->update();
                                return response()->json(['success'=>'Successfully updated Api User Key Details']);
                            }
                }


                public function getStateList()
                {
                  $state = State::select('id','name')->get();
                  return view('api.docs.index')->with('state',$state);
                }


                public function apiDocumentationIndex()
                {
                  return $this->getStateList();
                }
}
