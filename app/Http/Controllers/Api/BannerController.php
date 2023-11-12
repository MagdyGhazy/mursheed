<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Requests\BannerRequest;
use App\Http\Requests\BannerRequestUpdate;
use App\Http\Requests\BannerUpdateRequest;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    private $ControllerHandler;

    public function __construct()
    {


        $this->ControllerHandler = new ControllerHandler(new Banner());
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return $this->ControllerHandler->getAllWith("banners",['media']);
    }


    /**
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        return $this->ControllerHandler->showWith("banner", $banner,['media']);
    }

    /**
     * @param ChildRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        //        return response($request->status == "true"?1 : 0);
        return $this->ControllerHandler->store("banner", $request->except('images'));
    }

    /**
     * @param ChildRequest $request
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */

    public function update( BannerUpdateRequest $request, Banner $banner)
    {
        if ($request->hasFile('images')) {
            $banner->clearMediaCollection('photos');
            //

            $banner->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('photos');
            });
        }


        return $this->ControllerHandler->update("banner",$banner, $request->except('images') );
    }


    public function destroy(Banner $banner)
    {
        // here some validation check parent or admin


        return $this->ControllerHandler->destory("banner", $banner);
    }
}
