<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReminderRequest;
use App\Jobs\SendMailJob;
use App\Models\Driver;
use App\Models\Guides;
use App\Models\MursheedUser;
use App\Models\Tourist;
use App\Notifications\SendEmailForApprove;
use App\Notifications\SendOtpNotification;
use App\Notifications\SendReminder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function sendEmail(ReminderRequest $request)
    {
//        $users = null;
        if ($request->type == 0) {

            $users = $request->selected == [null]? Driver::all() : Driver::whereIn('id', $request->selected)->get();

        } elseif ($request->type == 1) {

            $users = $request->selected == [null]? Guides::all() : Guides::whereIn('id', $request->selected)->get();

        } else {
            $users = Tourist::all();
        }

        if($request->hasFile('image') && $request->file('image')->isValid()){
            $users->addMediaFromRequest('image')->toMediaCollection('images');
        }


//        $attachment = $request->has('attachment')? $request->attachment: null;

//        $user = MursheedUser::where('email','megoghazy55@gmail.com')->first();
//
//        $user->notify(new SendReminder($request->subject, $request->body));

//        $job = new SendMailJob($users, $request->subject, $request->body);
//        if ($job){
//            return response(['message' => 'success', 'status' => 200]);
//        }
//        $users->chunk(10,function ($user)use ($request){
//            $job = new SendMailJob($user, $request->subject, $request->body);
//            dispatch($job);
//        });

//        foreach ($users as $user )
//        {
//            $user->notify(new SendReminder($request->subject, $request->body));
//        }
        SendMailJob::dispatch($users, $request->subject, $request->body, $attachment);

        return response(['message' => 'success', 'status' => 200]);


    }
}
