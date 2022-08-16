<?php

namespace App\Services;

use App\User;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Events\PasswordChanged;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UserServicesException;

class UserServices
{
    function __construct()
    {
        //Remember with Trashed Idea ?

    }

    /*
        retrieves a particular user

    */
    function getUser($user_id)
    {
            //Validation
            //Authorization
            return User::find($user_id);
    }

    function getAllUsers()
    {
        return User::all();
    }



    public function getUsersQuery()
    {
        //:with(['scan_site', 'next_site',
        return User::with(['site']);
    }


    function getAllUsersByRole($data)
    {
        return User::role($data['roles'])->get();
    }

    function getUserRoles($data)
    {

        return  User::find((int)$data['user_id'])->getRoleNames();
    }


    function getAllSiteUsersByRole($data)
    {
        //Using the role scope from spartie
        return User::where('site_id', $data['site_id'])->role($data['roles'])->get();
    }

    function getAllSiteUsers($site_id)
    {
        //With trashed?
        return User::where('site_id', $site_id)->get();

    }

    /*
        Expected Data
        [
            'user_id' => user id
            'new_password' => unhashed string
            'updated_at' =>  DateTime
            'updated_by' => user object,
            'updated_by =>
        ]

    */
    function updateUserPassword($data)
    {
        if (!array_key_exists('user', $data) || $data['user'] === null) {

            throw new UserServicesException("User cannot be null");
            // The passwords matches            // return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if (!(Hash::check($data['current_password'], $data['user']->password))) {

            throw new UserServicesException("Your current password does not matches with the password you provided. Please try again.");
            // The passwords matches            // return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($data['current_password'], $data['new_password']) == 0){
            //Current password and new password are same
            throw new UserServicesException("error New Password cannot be same as your current password. Please choose a different password.");
        }


        try {

            $new_password = Hash::make($data['new_password']);
            $user = User::find($data['user']->id);
            $user->password = $new_password;
            $user->updated_at = now();
            $user->updated_by = $data['user']->id;
            $user->save();

            // dd($data['user'],  $new_password, $user->password);
            event(new PasswordChanged($data['user']));


            return ['success' => true,  'message' => 'Password has been updated successfully'];
            // return $result;
        }
        catch(Exception $ex)
        {
            //Log Error
            return ['success' => false,  'message' => 'Falied to update password.. An error occurred at the server level'];
        }

    }

    function resetUserPassword($data)
    {
        $user = User::find($data['user_id']);
        $new_password = $this->getRandomString();
        $result = $user->update(['password'=>Hash::make($new_password), 'updated_at' => now(), 'updated_by' => $data['logged_in_user']->id]);
        return $new_password;
    }

    function resetAllUsersPassword($data)
    {
        $users= User::all();
        $new_password = $this->getRandomString();
        $result = User::where('id', $users->pluck('id')->toArray())->update(['password'=> Hash::make($new_password), 'updated_at' => now(), 'updated_by' => $data['logged_in_user']->id]);
        return $new_password;
    }


    function getRandomString()
    {
        return Str::substr(Str::uuid()->toString(), 0, 6);
    }

    function updateUserRoles($data)
    {
            //$roles = ['writer', 'admin'],$user_id, $logged_in_user
            //authorization
            $user = User::find($data['user_id']);
            // All current roles will be removed from the user and replaced by the array given
            $user->syncRoles($data['roles']);
            // $user->syncRo
    }

    function deleteUser($user_id)
    {
        //authorization
        $user = User::find($user_id);
        //delete
        $user->delete();
    }

    function deactivateUser($user_id)
    {
        //authorization
        // $user = User::find($user_id);
        // //delete
        // $user->delete();
    }

    function activateUser($user_id)
    {
        // $user = User::withTrashed()->find($user_id);
        // $user->restore();
    }


    function restoreUser($user_id)
    {
        $user = User::withTrashed()->find($user_id);
        $user->restore();
    }


    /*

UPDATE `speedafx`.`users`
SET
`id` = <{id: }>,
`password` = <{password: }>,
`name` = <{name: }>,
`site_id` = <{site_id: }>,
`email` = <{email: }>,
`email_verified_at` = <{email_verified_at: }>,
`alternate_email` = <{alternate_email: }>,
`alternate_email_verified_at` = <{alternate_email_verified_at: }>,
`phone_number` = <{phone_number: }>,
`phone_number_verification_code` = <{phone_number_verification_code: }>,
`phone_number_verified_at` = <{phone_number_verified_at: }>,
`alternate_phone_number` = <{alternate_phone_number: }>,
`alternate_phone_number_verification_code` = <{alternate_phone_number_verification_code: }>,
`alternate_phone_number_verified_at` = <{alternate_phone_number_verified_at: }>,
`deleted_at` = <{deleted_at: }>,
`remember_token` = <{remember_token: }>,
`created_by` = <{created_by: }>,
`updated_by` = <{updated_by: }>,
`is_disabled` = <{is_disabled: 0}>,
`is_super_administrator` = <{is_super_administrator: 0}>,
`updated_on_k9_by` = <{updated_on_k9_by: }>,
`updated_on_k9_at` = <{updated_on_k9_at: }>,
`created_at` = <{created_at: }>,
`updated_at` = <{updated_at: }>
WHERE `id` = <{expr}>;



    */


    function updateUserProfile($data)
    {

        // dd($data);
        //Does this logged in user has the ability to update another user profile ?
        //Support updates of only some data
        $user = User::find($data['user_id']);
        // dd($user);
        $user->update(
            [
            'email' => $data['email'],
            'phone_number'=> $data['phone_number'],
            'alternate_phone_number'=> $data['alternate_phone_number'],
            'alternate_email' => $data['alternate_email'],
            'updated_at' => now(),
            'updated_by' => $data['logged_in_user']->id
            ]);
    }


   static function emailExists($email)
    {
        return User::where('emaail', $email)->orWhere('alternate_email')->count();
    }

   static function phoneNumberExists($email)
    {
        return User::where('phone_number', $email)->orWhere('alternate_phone_number')->count();
    }



    function verifyUserPhoneNumber($data)
    {
      $user =  User::where('id', $data['user_id'])
                ->where('phone_number', $data['phone_number'])
                ->where('alternate_phone_number_verification_code', $data['verification_code']);


        if($user == null)
        {
            throw new UserServicesException("Record not found");
        }


       $updated =  $user->update([
                'phone_number_verified_at' => Carbon::now()
        ]);

        //Fire event, Send message ?


        return $updated;
     }

    function verifyUserAlternatePhoneNumber($data)
    {

    }

    function verifyUserEmail($data)
    {

    }

    function verifyUserAlternateEmail($data)
    {

    }
}
