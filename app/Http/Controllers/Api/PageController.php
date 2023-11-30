<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Requests\PageRequest;
use App\Http\Requests\PageUpdateRequest;
use App\Models\Pages;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private $ControllerHandler;

    public function __construct()
    {


        $this->ControllerHandler = new ControllerHandler(new Pages());
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return $this->ControllerHandler->getAll("pages");
    }


    /**
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(Pages $page)
    {


        return $this->ControllerHandler->showWith("page", $page,['media']);
    }

    /**
     * @param ChildRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(PageRequest $request)
    {

        $data = $request->validated();

        // Encode HTML entities
        $data['description'] = htmlspecialchars($request['description'], ENT_QUOTES, 'UTF-8');

        return $this->ControllerHandler->store("page",  $data);
    }

    /**
     * @param ChildRequest $request
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */

    public function update(PageUpdateRequest $request, Pages $page)
    {

        if (request()->images)
        {
            $page->clearMediaCollection('photos');
//

            $page->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('photos');
            });
        }

        return $this->ControllerHandler->update("page", Pages::find($page->id), $request->except('images'));
    }


    public function destroy(Pages $page)
    {
        // here some validation check parent or admin


        return $this->ControllerHandler->destory("page", $page);
    }
}
