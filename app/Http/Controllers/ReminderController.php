<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReminderRequest;
use App\Jobs\SendMailJob;
use App\Models\Driver;
use App\Models\Guides;
use App\Models\Tourist;
use App\Notifications\SendEmailForApprove;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function sendEmail(ReminderRequest $request)
    {
        $users = null;
        if ($request->type == 0) {

            $users = $request->selected == null? Driver::query() : Driver::whereIn('id', $request->selected)->get();

        } elseif ($request->type == 1) {

            $users = $request->selected == null? Guides::query() : Guides::whereIn('id', $request->selected)->get();

        } else {
            $users = Tourist::query();
        }

        $job = new SendMailJob($users, $request->subject, $request->body);
        if ($job){
            return response(['message' => 'success', 'status' => 200]);
        }
//        $users->chunk(30,function ($user)use ($request){
//            $job = new SendMailJob($user, $request->subject, $request->body);
//            dispatch($job);
//        });

        return response(['message' => 'fail', 'status' => 400]);


    }
}
