<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Requests\GuideRequest;
use App\Http\Requests\Tourist\UpdateProfileRequest;
use App\Http\Requests\TouristRequest;
use App\Models\Guides;
use App\Models\MursheedUser;
use App\Models\Tourist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TouristController extends Controller
{
    private $ControllerHandler;

    public function __construct()
    {


        $this->ControllerHandler = new ControllerHandler(new Tourist());
    }

    public function index()
    {
        return $this->ControllerHandler->getAllWith("tourists", ['media']);
    }

    public function show(Tourist $tourist)
    {


        return $this->ControllerHandler->showWith("tourist", $tourist, ['media']);
    }

    public function store(TouristRequest $request)
    {

        $data = $request->validated();

        $data['password'] = Hash::make($request->validated('password'));

        $tourist = Tourist::create($data);

        $global_user = MursheedUser::where("email", $tourist->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'tourist successfully created',
            'token' => $global_user->createToken("API TOKEN")->plainTextToken,
            "user" => [
                "id" => $tourist->id,
                "name" => $tourist->name,
                "notification_id" => $tourist->mursheed_user->id,
                "phone" => $tourist->phone,
                "email" => $tourist->email,
                "is_verified" => $tourist->email_verified_at ? true : false,
                "type" =>  "Tourist",
                "nationality" => $tourist->nationality,
                "gender" => $tourist->gender ? ($tourist->gender == 1 ? "male" : "female") : null,
                "personal_photo" => empty($tourist->getFirstMediaUrl('personal_pictures')) ? null : $tourist->getFirstMediaUrl('personal_pictures'),
            ],
        ], 201);
    }

    public function update_mobile(UpdateProfileRequest $request)
    {

        $tourist = Tourist::where('email', $request->user()->email)->first();
    
        if ($tourist == null) {
            return response()->json(["message" => "unauthenticated"], 401);
        }

        $global_user = $request->user();
        $global_user->update([
            'email' => $request->email ? $request->email : $global_user->email,
            'password' => $request->password ? Hash::make($request->password) : $global_user->password
        ]);

        if ($request->personal_pictures) {
            $tourist->clearMediaCollection('personal_pictures');
            $tourist->addMultipleMediaFromRequest(['personal_pictures'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('personal_pictures');
            });

         
        }

        $data = $request->except('personal_pictures', 'languages', 'car_photos');

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $tourist->update($data);
        
        return response()->json([
            'status' => true,
            'message' => 'tourist successfully created',
            'token' => $global_user->createToken("API TOKEN")->plainTextToken,
            "user" => [
                "id" => $tourist->id,
                "name" => $tourist->name,
                "notification_id" => $tourist->mursheed_user->id,
                "phone" => $tourist->phone,
                "email" => $tourist->email,
                "is_verified" => $tourist->email_verified_at ? true : false,
                "type" =>  "Tourist",
                "dest_city_id"=>$tourist->dest_city_id,
                "county"=>$tourist->county,
                "country_id"=>$tourist->country_id,
                "state_id"=>$tourist->state_id,
                "nationality" => $tourist->nationality,
                "gender" => $tourist->gender ? ($tourist->gender == 1 ? "male" : "female") : null,
                "personal_photo" => empty($tourist->getFirstMediaUrl('personal_pictures')) ? null : $tourist->getFirstMediaUrl('personal_pictures'),
            ],
        ], 201);
    }

    public function update(TouristRequest $request, Tourist $tourist)
    {

        $global_user = MursheedUser::where('email', $tourist->email)->first();
        $global_user->update([
            'email' => $request->email ? $request->email : $global_user->email,
            'password' => $request->password ? Hash::make($request->password) : $global_user->password
        ]);

        if ($request->personal_pictures) {
            $tourist->clearMediaCollection('personal_pictures');
            $tourist->addMultipleMediaFromRequest(['personal_pictures'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('personal_pictures');
            });
        }

        return $this->ControllerHandler->update(
            "tourist",
            $tourist,
            array_merge(
                $request->except('personal_pictures', 'languages'),
                $request->password ? ['password' => Hash::make($request->password)] : []
            )
        );
    }

    public function destroy(Tourist $tourist)
    {
        return $this->ControllerHandler->destory("tourist", $tourist);
    }


    public function active(Guides $guide)
    {
        return $this->ControllerHandler->update("guide", $guide, ['status' => 1]);
    }

    public function inActive(Guides $guide)
    {
        return $this->ControllerHandler->update("guide", $guide, ['status' => 1]);
    }

    public function change(Guides $guide)
    {
        return $this->ControllerHandler->update("guide", $guide, ['status' => $guide->status ^ 1]);
    }
}
