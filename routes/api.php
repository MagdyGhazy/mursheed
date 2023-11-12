<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmailConfirmationController;
use App\Http\Controllers\Api\OTPController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\MobileApi\PriceServiceController;
use App\Http\Controllers\Api\TotalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Console\Input\Input;


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

Route::get('/event', function (Request $request) {

    //    event(new \App\Events\MessageNotification('hello world '));
    $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
        "instanceId" => "140343aa-f173-4a2d-940a-7724c7c12be1",
        "secretKey" => "7D70A732FDB61A5566B7DAD488F4FAFAD39120B6B172A8A91A9A605F4B3653D5",
    ));

    $publishResponse = $beamsClient->publishToUsers(
        array("$request->id"),
        array(
            "fcm" => array(
                "notification" => array(
                    "title" => "Hi!",
                    "body" => "This is my first Push Notification!"
                )
            ),
            "apns" => array("aps" => array(
                "alert" => array(
                    "title" => "Hi!",
                    "body" => "This is my first Push Notification!"
                )
            )),
            "web" => array(
                "notification" => array(
                    "title" => "Hi!",
                    "body" => "This is my first Push Notification!"
                )
            )
        )
    );
    return response(['k' => $publishResponse]);
});
Route::get('/events', [EventController::class,'index']);


Route::middleware('auth:sanctum')->get('/pusher/beams-auth', function (Request $request) {
    $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
        "instanceId" => "140343aa-f173-4a2d-940a-7724c7c12be1",
        "secretKey" => "7D70A732FDB61A5566B7DAD488F4FAFAD39120B6B172A8A91A9A605F4B3653D5",
    ));

    $userID = $request->user()->id; // If you use a different auth system, do your checks here
//    $userIDInQueryParam = $request->user_id;

//    if (strval($userID) != $userIDInQueryParam) {
//        return response(['m' => 'Inconsistent request', 'u' => strval($userID), 'q' => $userIDInQueryParam], 401);
//    } else {
        $beamsToken = $beamsClient->generateToken(strval($userID));
        return response()->json($beamsToken);
//    }
});

    Route::post('/roles/create', [\App\Http\Controllers\Api\RoleController::class, 'roles_create']);
    Route::post('/permissions/create', [\App\Http\Controllers\Api\RoleController::class, 'permissions_create']);
    Route::post('user/roles', [\App\Http\Controllers\Api\RoleController::class, 'UserRoles']);

    Route::get('roles/index', [\App\Http\Controllers\Api\RoleController::class, 'roleIndex']);
    Route::get('permissions/index', [\App\Http\Controllers\Api\RoleController::class, 'permissionsIndex']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'createUser']);

Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/login-clients', [AuthController::class, 'loginClients']);
Route::post('resset-password', [AuthController::class, 'ressetPassword']);

Route::get('/getUser', function () {
    return response(["message" => auth()->user()]);
})->middleware("auth:sanctum");

Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware("auth:sanctum");

Route::get("/evaluations", function () {
    return response(["hello" => app()->getLocale()]);
});


Route::get('/state/{country}', [\App\Http\Controllers\Api\CountryStateController::class, 'getStateInCountry']);
Route::get('/countries', [\App\Http\Controllers\Api\CountryStateController::class, 'getCountry']);
Route::get('/languages', [\App\Http\Controllers\Api\CountryStateController::class, 'langauages']);

Route::get('/total', [\App\Http\Controllers\Api\TotalController::class, 'index'])->middleware("auth:sanctum");

Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);

    return response()->json([
        'app_locale' => app()->getLocale(),
        'message' => 'locale has been sent in backend locale system'
    ]);

    return redirect()->back();
});


Route::controller(OTPController::class)->group(function () {
    Route::post("generate-otp", 'generateOTP')->name("generate.otp");
    Route::post("validate-otp", 'validateOTP')->name("validate.otp");
});


Route::get('all-cities', function () {
    return response(['cities' => \App\Models\State::where('lang', 'en')->get()]);
});

Route::post('city/{id}/update', function (Request $request, $id) {

    $city = \App\Models\State::find($id);
    $city->update(['lat' => $request->lat, 'longitude' => $request->longitude]);
    return response(['status' => true, 'city' => $city->fresh()]);
});


// Route::prefix()->as()->middleware("auth:sanctum")->group(function () {
Route::apiResource('price-services', PriceServiceController::class)->middleware("auth:sanctum");
// });



Route::post('/reminderEmail', [\App\Http\Controllers\ReminderController::class, 'sendEmail']);