<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Requests\OfferRequest;
use App\Http\Requests\OfferRequestUpdate;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    private $ControllerHandler;

    public function __construct()
    {
        $this->ControllerHandler = new ControllerHandler(new Offer());
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return $this->ControllerHandler->getAllWith("offers",['media']);
    }

    public function approvedOffers()
    {
        return $this->ControllerHandler->getAllWithWhere("offers",['media'],'status',1);
    }



    /**
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        return $this->ControllerHandler->showWith("offer", $offer,['media']);
    }

    /**
     * @param ChildRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(OfferRequest $request)
    {
        $validated = $request->validated();
    
        $offer = Offer::create($request->except('images'));
        if (!$offer) {
            return response([
                "data" => null,
                "message" => "Not Saved",
                "status" => false,
            ], 400);
        }
    
        if ($request->hasFile('images')) {
            $offer->addMediaFromRequest('images')->toMediaCollection('Offer');
        }
    
        return response([
            "data" => $offer,
            "message" => "Success",
            "status" => true,
        ], 200);
    }

    /**
     * @param ChildRequest $request
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */

    public function update(OfferRequestUpdate $request, Offer $offer)
    {
        if ($request->hasFile('images')) {
            $offer->clearMediaCollection('photos');
            //

            $offer->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('photos');
            });
        }


        return $this->ControllerHandler->update("offer", $offer, array_merge($request->except('images'), ['status' => $request->status == "true" ? 1 : 0]));
    }


    public function destroy(Offer $offer)
    {
        // here some validation check parent or admin
        return $this->ControllerHandler->destory("offer", $offer);
    }
}
