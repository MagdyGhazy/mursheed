<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TutorialController extends Controller
{
    public function __invoke()
    {
        if (auth()->user()->can('create-user')) {
            return view('welcome');
        }
    }
    public function permaissions()
    {
        return response()->json("hih");
    }
}
