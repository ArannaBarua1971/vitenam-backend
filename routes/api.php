<?php

use App\Http\Controllers\DaseboardController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// user related Route
Route::controller(UserController::class)->name("user")->group(function () {

    // authInfo
    Route::get("/authInfo/{id}", function ($id) {

        $user = User::select("id", "name", "email", "phone", "address")->where('id', $id)->first();
        return response()->json([
            "message" => "user data",
            "status" => "success",
            "data" => $user
        ], 200);
    })->name(".authInfo");

    // user register
    Route::post("/user_registration", "register")->name('.register');
    Route::post("/user_login", "login")->name('.login');

    // update profile
    Route::post("/update_profile", "update_profile")->name(".update_profile");
    Route::post("/update_password", "update_password")->name(".update_password");

    // login with Thirdparty
    Route::post("/loginWithThirdParty", "loginWithThirdParty")->name("loginWithThirdParty");
});

// daseboard related Route
Route::controller(DaseboardController::class)->name("daseboard")->group(function () {

    // add Article
    Route::post("/add-article","addArticle")->name(".addArticle");

});
