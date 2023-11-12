<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Requests\FlightRequest;
use App\Http\Requests\FlightRequestUpdate;
use App\Models\FlightRservation;
use App\Models\Pages;
use Illuminate\Http\Request;

class FlightReservationController extends Controller
{
    private $ControllerHandler;

    public function __construct()
    {


        $this->ControllerHandler = new ControllerHandler(new FlightRservation());
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return $this->ControllerHandler->getAllWith("flights",['media','country','state']);
    }

    public function paginateIndex(Request $request)
    {
        return $this->ControllerHandler->getAllWithPagination("flights",$request,['media','country','state']);
    }


    /**
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(FlightRservation $flight)
    {


        return $this->ControllerHandler->showWith("flight", $flight,['media','country','state']);
    }

    /**
     * @param ChildRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(FlightRequest $request)
    {


        return $this->ControllerHandler->store("flight",  $request->except('images'));
    }

    /**
     * @param ChildRequest $request
     * @param Child $child
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */

    public function update(FlightRequestUpdate $request, FlightRservation $flight)
    {

        $flight->clearMediaCollection('photos');
//
        if (request()->images)
        $flight->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
            $fileAdder->toMediaCollection('photos');
        });
        return $this->ControllerHandler->update("flight", $flight, $request->except('images'));
    }


    public function destroy(FlightRservation $flight)
    {
        // here some validation check parent or admin


        return $this->ControllerHandler->destory("flight", $flight);
    }
}
