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
        $offer = Offer::create($request->except('images'));

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $offer->addMediaFromRequest('images')->toMediaCollection('offer');
        }

        $data = Offer::where('id',$offer->id)->with('media')->get();


        if ($offer) {
            return response([
                "message" => "Success",
                'data' => $data,
                "status" => true,
            ], 200);
        }

        return response([
            "data" => null,
            "message" => "Not Save",
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
        $offer = Offer::find($id);

        if (!$offer) {
            return response([
                "data" => null,
                "message" => "Not Found",
                "status" => false,
            ], 404);
        }

        $offer->clearMediaCollection('offer');
        $offer->update([
            'number' => $request->number,
            'title' => $request->title,
            'status' => $request->status,
            'price' => $request->price,
            'lang' => $request->lang,
        ]);
        $offer->addMediaFromRequest('images')->toMediaCollection('offer');

        return response([
            "data" => new OfferRequestUpdate($offer),
            "message" => "Updated Success",
            "status" => true,
        ], 200);
    }


    public function destroy(Offer $offer)
    {
        // here some validation check parent or admin
        return $this->ControllerHandler->destory("offer", $offer);
    }
}
