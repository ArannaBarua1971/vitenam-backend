<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // api response

    // user register
    public function register(Request $req)
    {

        $req->validate([
            "name" => "required",
            "email" => "required|email",
            "password" => "required|confirmed|min:8"
        ]);

        $newUser = new User();
        $newUser->name = $req->name;
        $newUser->email = $req->email;
        $newUser->password = Hash::make($req->password);

        $newUser->save();

        $newUser->assignRole('user');
        return response()->json([
            "message" => "authorize",
            "status" => 'success',
            "data" => $newUser
        ], 200);;
    }

    // user login
    public function login(Request $req)
    {

        $req->validate([
            "email" => "required|email",
            "password" => "required|min:8"
        ]);


        if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
            // if(auth()->user()->status==0) return $this->failRsoponse('user is banned ',403,[]);
            $user = User::with('roles')->select("id", "name", "email")->where('email', $req->email)->first();
            $token = $user->createToken('token-name' . $user->name)->plainTextToken;

            $data = [
                "user" => $user,
                "user_token" => $token
            ];

            return response()->json([
                "message" => "authorize",
                "status" => 'success',
                "data" => $data
            ], 200);
        } else {
            return response()->json([
                "message" => "unauthorized",
                "status" => 'failed',
                "data" => []
            ], 401);
        }
    }

    // update profile
    public function update_profile(Request $req)
    {
        $req->validate([
            "email" => "required|email",
            "name" => "required"
        ]);


        $updateUserProfile = User::where("id", $req->id)->first();

        if ($updateUserProfile->email != $req->email) {
            $count = User::where("email", $req->email)->count();
            if ($count) {
                return response()->json([
                    "message" => "email alreay is existed",
                    "status" => "failed",
                    "data" => []
                ], 401);
            }
        }
        $updateUserProfile->email = $req->email;
        $updateUserProfile->name = $req->name;
        $updateUserProfile->phone = $req->phone;
        $updateUserProfile->address = $req->address;

        $updateUserProfile->save();

        return response()->json([
            "message" => "authorize",
            "status" => 'success',
            "data" => $updateUserProfile
        ], 200);
    }

    // update password
    public function update_password(Request $req)
    {

        $req->validate([
            "old_password" => "required|min:8",
            "new_password" => "required|min:8"
        ]);
        $user = User::where("id", $req->id)->first();

        if (!Hash::check($req->old_password, $user->password)) {
            return response()->json([
                "message" => "old password is not match",
                "status" => "failed",
                "data" => []
            ], 401);
        } else if ($req->old_password == $req->new_password) {
            return response()->json([
                "message" => "old password is not match with new password",
                "status" => "failed",
                "data" => []
            ], 401);
        } else {
            $user->password = Hash::make($req->new_password);
            $user->save();

            return response()->json([
                "message" => "password changed",
                "status" => "success",
                "data" => $user
            ], 200);
        }
    }


    // login with third party
    public function loginWithThirdParty(Request $req)
    {

        if (Auth::attempt(["email" => $req->email,'password' => $req->password])) {
            $user = User::select("id", "name", "email")->where('email', $req->email)->first();
            $token = $user->createToken('token-name' . $user->name)->plainTextToken;

            $data = [
                "user" => $user,
                "user_token" => $token
            ];

            return response()->json([
                "message" => "authorize",
                "status" => 'success',
                "data" => $data
            ], 200);
        } else {
            $newUser = new User();
            $newUser->name = $req->name;
            $newUser->email = $req->email;
            $newUser->password = Hash::make($req->password);

            $newUser->save();

            return response()->json([
                "message" => "authorize",
                "status" => 'success',
                "data" => $newUser
            ], 200);
        }
    }
}
