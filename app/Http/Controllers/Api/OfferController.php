<?php

namespace App\Http\Controllers\Api;

use App\Models\Offer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\OfferRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\OfferRequestUpdate;
use App\Http\Controllers\ControllerHandler;

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
        return $this->ControllerHandler->getAllWith("offers", ['media']);
    }

    public function approvedOffers()
    {
        return $this->ControllerHandler->getAllWithWhere("offers", ['media'], 'status', 1);
    }



    /**
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        return $this->ControllerHandler->showWith("offer", $offer, ['media']);
    }

    /**
     * @param ChildRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(OfferRequest $request)
    {
        $validated = $request->validated();
        $fillableData = $request->except('images');

        $offer = new Offer();
        $offer->title = $request->title;
        $offer->status = $request->status;
        $offer->price = $request->price;
        $offer->lang = $request->lang;
        $offer->number = 'MUR|' . Str::random(10);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $offer->addMediaFromRequest('images')->toMediaCollection('offer');
        }

        if ($offer->save()) {
            $offer->number .= '|' . $offer->id;
            $offer->save();

            $data = Offer::where('id', $offer->id)->with('media')->first();

            return response([
                "message" => "Success",
                'data' => $data,
                "status" => true,
            ], 200);
        }

        return response([
            "data" => null,
            "message" => "Not Saved",
            "status" => false,
        ], 400);
    }


    /**
     * @param ChildRequest $request
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */

    public function update(OfferRequestUpdate $request, $id)
    {
        $validated = $request->validated();
        $offer = Offer::with('media')->find($id);

        if (!$offer) {
            return response([
                "data" => null,
                "message" => "Offer Not Found",
                "status" => false,
            ], 404);
        }

        $offer->title = $request->title;
        $offer->status = $request->status;
        $offer->price = $request->price;
        $offer->lang = $request->lang;
        $offer->save();

        $offer->clearMediaCollection('offer');
        $offer->addMediaFromRequest('images')->toMediaCollection('offer');

        $mediaUrls = $offer->getMedia('offer')->map(function ($media) {
            return $media->getUrl();
        });

        return response([
            "data" => [
                "offer" => $offer,
                "media_urls" => $mediaUrls,
            ],
            "message" => "Offer Updated Successfully",
            "status" => true,
        ], 200);
    }





    public function destroy(Offer $offer)
    {
        // here some validation check parent or admin
        return $this->ControllerHandler->destory("offer", $offer);
    }
}
