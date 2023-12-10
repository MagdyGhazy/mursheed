<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Controllers\Filter\SearchByAccomoditionCategory;
use App\Http\Controllers\Filter\SearchByAccomoditionRooms;
use App\Http\Requests\AccommoditionRequest;
use App\Models\accommmodition;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AccommmoditionController extends Controller
{
    private $ControllerHandler;
    private $accommmodition;

    public function __construct()
    {

        $this->accommmodition = new accommmodition();
        $this->ControllerHandler = new ControllerHandler($this->accommmodition);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $model = Pipeline::send($this->accommmodition::query())->through(
            [
              //  SearchByAccomoditionCategory::class,
               // SearchByAccomoditionRooms::class
            ]
        )->then(
            fn($user) => $user->with(['media', 'country', 'state'])->get()
        );
        $k = array_search('media', ['media', 'country', 'state'], true);
        if ($k !== false) {
            $model = $this->accommmodition::
                when($request->has('rooms'),fn($query)=>$query->where('rooms',$request->rooms))
                ->when($request->has('category_id'),fn($query)=>$query->where('category_accommodations_id',$request->category_id))
                ->where('aval_status',1)
                ->with(['media', 'country', 'state'])->get()->map(function ($data) {
                $collect = collect(collect($data)['media'])->groupBy('collection_name')->toArray();

                $data['pictures'] = count($collect) ? $collect : null;
                return $data;
            });
        }
        return response([
            "accommmoditions" => $model,
            "message" => "success",
            "status" => true
        ], 200);
        return $this->ControllerHandler->getAllWith("accommmoditions", ['media', 'country', 'state']);
    }


    public function paginatedIndex(Request $request)
    {

        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $page = $request->input('page', 1);


        $model = $this->accommmodition::query()->with(['media', 'country', 'state']);


        $k = array_search('media', ['media', 'country', 'state'], true);
        if ($k !== false) {
            $model = $this->accommmodition::query()
                ->when($request->has('rooms'),fn($query)=>$query->where('rooms',$request->rooms))
                ->when($request->has('category_id'),fn($query)=>$query->where('category_accommodations_id',$request->category_id))
                ->where('aval_status',1)
                ->with(['media', 'country', 'state']);
        }

        $paginatedModel = $model->paginate($perPage, ['*'], 'page', $page);

        $paginatedModel->map(function ($data) {
        $collect = collect(collect($data)['media'])->groupBy('collection_name')->toArray();

        $data['pictures'] = count($collect) ? $collect : null;
        return $data;
        });


        $data = json_decode(json_encode($paginatedModel), true);

        return response([
            "message" => "success",
            "status" => true,
            "current_page" => $data['current_page'],
            "accommmoditions" => $data['data'],
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



    /**
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(accommmodition $accommmodition)
    {
        return $this->ControllerHandler->showWith("accommmodition", $accommmodition, ['media', 'country', 'state']);
    }

    /**
     * @param ChildRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(AccommoditionRequest $request)
    {
        //        return response(['k'=>$request->images]);

        //            $created->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
        //            }
        //            $fileAdder->toMediaCollection('photos');
        //        });

        return $this->ControllerHandler->store("accommmodition", array_merge($request->validated(), [
            "aval_status" => $request->aval_status ? 1 : 0,
            "info_status" => $request->info_status ? 1 : 0
        ]));
    }

    /**
     * @param ChildRequest $request
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */

    public function update(AccommoditionRequest $request, accommmodition $accommmodition)
    {
//        return $request;

        // here some validation check parent or admin
        $request->media_id == null ?   : $accommmodition->deleteMedia($request->media_id) ;

        if (request()->images) {
//            $accommmodition->clearMediaCollection('photos');

            //
            $accommmodition->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('photos');
            });
        }

        return $this->ControllerHandler->update("accommmodition", $accommmodition, array_merge($request->validated(), [
            "aval_status" => $request->aval_status ? 1 : 0,
            "info_status" => $request->info_status ? 1 : 0
        ]));
    }

    public function destroy(accommmodition $accommmodition)
    {
        // here some validation check parent or admin


        return $this->ControllerHandler->destory("accommmodition", $accommmodition);
    }

    public function deleteImage($id)
    {
        // Find the media item by ID
        $mediaItem = Media::find($id);

        // Delete the media item along with the associated file
        if ($mediaItem) {
            $mediaItem->delete();
        }
        return response([
            'status' => "success",
            'message' => "deleted",
        ]);
    }
}
