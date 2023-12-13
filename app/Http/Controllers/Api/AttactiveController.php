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


        $data = json_decode(json_encode($paginatedLocations), true);

        return response([
            "message" => "success",
            "status" => true,
            "current_page" => $data['current_page'],
            "locations" => $data['data'],
            "first_page_url" => $data['first_page_url'],
            "from" => $data['from'],
            "last_page" => $data['last_page'],
            "last_page_url" => $data['last_page_url'],
            "links" => $data['links'],
            "next_page_url" => $data['next_page_url'],
            "path" => $data['path'],
            "prev_page_url" => $data['prev_page_url'],
            "to" => $data['to'],
            "total" => $data['total'],

        ], 200);

    }

    public function show(AttractiveLocation $attractiveLocation)
    {
        $mapUrl = "https://www.google.com/maps?q={$attractiveLocation->lat},{$attractiveLocation->long}";

        $photos = [];

        $attractiveLocation->load(['country', 'state']);

        if (count($attractiveLocation->getMedia('photos')) >= 0) {
            foreach ($attractiveLocation->getMedia('photos') as $media) {
                $photos[] = $media->getUrl();
            }
        }

        $data = json_decode(json_encode($attractiveLocation), true);
        return response()->json([
            "status" => true,
            "message" => "Attractive Location details",
            "attractiveLocation" => [
                "id" => $attractiveLocation->id,
                "name" => $data['name'],
                "country_id" => $attractiveLocation->country->id,
                "country" => $attractiveLocation->country->country,
                "state" => $attractiveLocation->state->state,
                "city_id" => $attractiveLocation->state_id,
                "url" => $attractiveLocation->url,
                "location" => $mapUrl,
                "long" => $attractiveLocation->long,
                "lat" => $attractiveLocation->lat,
                "description" => $data['description'],
                "photos" => empty($photos) ? [url("car_photo_default.jpg")] : $photos,
            ],
        ], 200);
    }

    public function store(AttractiveRequest $request)
    {
            //    return response(['k'=>$request->images]);

            //        $created->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
            //        }
            //        $fileAdder->toMediaCollection('photos');
            //    });

        return $this->ControllerHandler->store("attrractive", $request->except('images'));
    }

    public function update(AttractiveUpdateRequest $request, $id)
    {
        $attrractive=AttractiveLocation::find($id); 
     

        if ($request->images) {
          
            $attrractive->clearMediaCollection('photos');

            $attrractive->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
            $fileAdder->toMediaCollection('photos');

            });
                // $attrractive->addMedia('images')->toMediaCollection('photos');

        }

        if (count($attrractive->getMedia('images')) >= 0) {
            foreach ($attrractive->getMedia('images') as $media) {
                $images[] = $media->getUrl();
            }
        }

// return response()->json($request->all());
        if (!$attrractive) {
            return response([
                "data" => null,
                "message" => "Not Found",
                "status" => false,
            ], 404);
        }

        return $this->ControllerHandler->update("attrractive", $attrractive, $request->except('images'));
    }


    public function destroy( $attrractive)
    {
        $attracive = AttractiveLocation::find($attrractive);
        $attracive->delete();
        return response()->json("suucses");
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
            "status" => true,
            "message" => "attractive locations",
            "locations" => $locations
        ], 200);
    }
}
