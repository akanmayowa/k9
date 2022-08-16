<?php

namespace App\Http\Controllers;

use App\Role;
use App\Site;
use App\User;
use Exception;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Services\SiteServices;
use App\Services\UserServices;
use App\Events\PasswordChanged;
use Yajra\DataTables\DataTables;
use App\Services\ManifestServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exceptions\UserServicesException;

class UsersController extends Controller
{

    public $user_services = null;
    public $site_services = null;
    public function __construct(UserServices $user_services, SiteServices $site_services)
    {
        $this->middleware('auth');

        $this->user_services = $user_services;
        $this->site_services = $site_services;
    }

    // public $imports = [];


    //Shows Listing of all Users
    public function index()
    {
        $sites = $this->site_services->getAllSites();
        return view('users.index', ['sites' => $sites]);
    }


    public function getUsers()
    {

        $users_query =  $this->user_services->getUsersQuery();
        return Datatables::of($users_query)
            ->addIndexColumn()
            ->addColumn('action', function (User $user) {
                $html = '<div class="dropdown">
                <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">';
                $html .= '
                <a href="javascript:void(0)" data-toggle="modal"   data-target="#userRoleModal"  class="dropdown-item user-group"  data-id="' . json_encode($user->id) . '"  data-user="' . json_encode($user->id) . '" data-username="' . json_encode($user->name).'">Roles</a>
                </div>
              </div>';
                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getUserRoles($user_id)
    {
        try {

            //Validation unkor?
            // $user_id = request()->input($user_id);
            $user_roles =   $this->user_services->getUserRoles(compact('user_id'));//->toArray();
            $all_roles = Role::get(['name', 'id']);


            return response()->json(['success' => true, 'message' => 'User Roles Retrieved Successfully', 'data' => ['all_roles'=> $all_roles, 'user_roles'=>$user_roles], 'request' => request()->all()]);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'message' => 'Could not retrieve user roles\n' . $ex->getMessage()]);
        }
    }


    //Saves a User
    public function editProfile()
    {
        return view('users.edit-profile');
    }

    public function showChangePasswordView()
    {
        return view('auth.passwords.change');
    }

    public function ShowProfileView()
    {
        return view('users.profile');
    }

    public function UpdateProfile()
    {
        try {
            $data = request()->only(['user_id', 'phone_number', 'email', 'alternate_phone_number', 'alternate_email']);
            $data['logged_in_user'] = Auth::user();
            $this->user_services->updateUserProfile($data);
            return redirect()->back()->with("success", "Profile Updated successfully Thanks");
        } catch (UserServicesException $ex) {
            return redirect()->back()->with("error", $ex->getMessage());
        } catch (Exception $ex) {
            return redirect()->back()->with("error", "Could not update user password. Please try again." . $ex->getMessage());
        }
    }

    public function changePassword()
    {

        //Validates and throws exception
        $validatedData = request()->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        // dd("I am here\n",  Auth::user()->password);
        try {

            // dd(Auth::user()->password);

            $this->user_services->updateUserPassword(
                [
                    'user' => Auth::user(),
                    'current_password' => request()->input('current_password'),
                    'new_password' => request()->input('new_password')
                ]

            );

            $logout_url = "<a href=" . route("logout") . ">LOGOUT</a>";
            return redirect()->home()->with("success", "Password changed successfully ! <br/>  Kindly $logout_url and then login with your new password. Thanks");
        } catch (UserServicesException $ex) {
            return redirect()->back()->with("error", $ex->getMessage());
        } catch (Exception $ex) {
            return redirect()->back()->with("error", "Could not update user password. Please try again.");
        }
    }

    public function resetUserPassword()
    {

        return response()->json("You want to reset user password?");
    }


    public function deactivateUserAccount()
    {
        return response()->json("You want to deactivate user account?");
    }

    public function activateUserAccount()
    {
        return response()->json("You want to account user account ?");
    }

    public function sentPersonalMessageTo()
    {
        return response()->json("You want to send Personal message to ?");
    }
}
