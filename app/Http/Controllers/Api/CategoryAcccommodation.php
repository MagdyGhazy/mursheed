<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Requests\AccommoditionRequest;
use App\Http\Requests\CategoryRequest;
use App\Models\accommmodition;
use App\Models\CategoryAccommodation;
use Illuminate\Http\Request;

class CategoryAcccommodation extends Controller
{
    private $ControllerHandler;
    private $category;

    public function __construct()
    {

        $this->category = new CategoryAccommodation();
        $this->ControllerHandler = new ControllerHandler($this->category);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return $this->ControllerHandler->getAll("accommmoditions_category");
    }



    /**
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(CategoryAccommodation $accommmodition)
    {
        return $this->ControllerHandler->showWith("accommmoditions_category", $accommmodition,['accommodations']);
    }

    /**
     * @param ChildRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        //        return response(['k'=>$request->images]);

        //            $created->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
        //            }
        //            $fileAdder->toMediaCollection('photos');
        //        });

        return $this->ControllerHandler->store("accommmoditions_category", $request->validated());
    }

    /**
     * @param ChildRequest $request
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */

    public function update(CategoryRequest $request, accommmodition $accommmodition)
    {

        // here some validation check parent or admin

        return $this->ControllerHandler->update("accommmoditions_category", $accommmodition, $request->validated());
    }


    public function destroy(CategoryAccommodation $accommmodition)
    {
        // here some validation check parent or admin


        return $this->ControllerHandler->destory("accommmoditions_category", $accommmodition);
    }
}
