<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Requests\AccommoditionRequest;
use App\Http\Requests\AttractiveRequest;
use App\Http\Requests\AttractiveUpdateRequest;
use App\Models\accommmodition;
use App\Models\AttractiveLocation;
use Elastic\Apm\TransactionContextRequestInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttactiveController extends Controller
{
    private $ControllerHandler;
    private $attractive;

    public function __construct()
    {

        $this->attractive = new AttractiveLocation();
        $this->ControllerHandler = new ControllerHandler($this->attractive);
    }

    public function index()
    {
        return $this->ControllerHandler->getAllWith("attractives", ['media', 'country', 'state']);
    }

    public function index_mobile(Request $request)
    {
        $locations = AttractiveLocation::query()
            ->select('id', 'name');

        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $page = $request->input('page', 1);
        $paginatedLocations = $locations->paginate($perPage, ['*'], 'page', $page);

        $paginatedLocations->each(function ($location) {
            $location->image_background =
                count($location->getMedia('photos')) == 0
                ? url("attaractive_default.jpg") : $location->getMedia('photos')->first()->getUrl();
            unset($location->media);
        });



        return response()->json([
            "success" => true,
            "message" => "attractive locations",
            "locations" => $paginatedLocations
        ], 200);
    }

    public function show(AttractiveLocation $attractiveLocation)
    {

        $photos = [];

        $attractiveLocation->load(['country', 'state']);

        if (count($attractiveLocation->getMedia('photos')) >= 0) {
            foreach ($attractiveLocation->getMedia('photos') as $media) {
                $photos[] = $media->getUrl();
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Attractive Location details",
            "attractiveLocation" => [
                "id" => $attractiveLocation->id,
                "name" => $attractiveLocation->name,
                "country" => $attractiveLocation->country->country,
                "state" => $attractiveLocation->state->state,
                "url" => $attractiveLocation->url,
                "description" => $attractiveLocation->description,
                "photos" => empty($photos) ? [url("car_photo_default.jpg")] : $photos,
            ],
        ], 200);
    }

    public function store(AttractiveRequest $request)
    {
        //        return response(['k'=>$request->images]);

        //            $created->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
        //            }
        //            $fileAdder->toMediaCollection('photos');
        //        });

        return $this->ControllerHandler->store("attrractive", $request->except('images'));
    }

    public function update(AttractiveUpdateRequest $request, AttractiveLocation $attrractive)
    {

        $attrractive->clearMediaCollection('photos');
        //
        if (request()->images)
        $attrractive->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
            $fileAdder->toMediaCollection('photos');
        });
        return $this->ControllerHandler->update("attrractive", $attrractive, $request->except('images'));
    }

    public function destroy(AttractiveLocation $attrractive)
    {
        // here some validation check parent or admin


        return $this->ControllerHandler->destory("attrractive", $attrractive);
    }

    public function home()
    {

        $locations = AttractiveLocation::query()
            ->select('id', 'name')
            ->limit(4)
            ->get();

        $locations->each(function ($location) {
            $location->image_background =
                count($location->getMedia('photos')) == 0
                ? url("attaractive_default.jpg") : $location->getMedia('photos')->first()->getUrl();
            unset($location->media);
        });



        return response()->json([
            "success" => true,
            "message" => "attractive locations",
            "locations" => $locations
        ], 200);
    }
}
