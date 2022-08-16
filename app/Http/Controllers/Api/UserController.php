<?php

// namespace App\Http\Controllers\Api;

// use App\Users;
// use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;

// class UserController extends Controller
// {

//       public function register(Request $request){

//         $request->validate([
//               "name" => 'required|string',
//               "email" => "required|email|unique:users",
//               "address" => "required",
//               "phone" => "required|numeric",
//               "password" => "required|confirmed|min:6",
//         ]);

//         $user = new Users();
//         $user->name = $request->name;
//         $user->email = $request->email;
//         $user->address = $request->address;
//         $user->phone = $request->phone;
//         $user->password =  Hash::make($request->password);
//         $user->save();

//         return json([
//            "status" => true,
//            "message" => "User Registration Success"
//         ],201);

//       }

//     public function login(Request $request){


//       $request->validate([
//         "email" => "required|email|unique:users",
//         "password" => "required|confirmed|min:6",
//      ]);

//      if(!$token = auth()->attempt(["email" =>$request->email, "password" =>$request->password]));{
//          return response()->json([
//              "status" => false,
//              "message" => "invalid login credentials",
//          ],401);
//      }
//      return $this->newTokenCreated($token);
//     }


//     public function newTokenCreated($token){
//         return response()->json([
//             'status' => true,
//             'message' => 'User logged in successfully',
//             'access_token' => $token,
//             'token_type' => 'bearer',
//             'user' => auth()->user()
//         ]);
//     }


// }
