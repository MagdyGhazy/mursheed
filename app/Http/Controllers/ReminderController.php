<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReminderRequest;
use App\Jobs\SendMailJob;
use App\Models\Driver;
use App\Models\Guides;
use App\Models\MailUser;
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

            $users = $request->selected == [null] ? Driver::all() : Driver::whereIn('id', $request->selected)->get();

        } elseif ($request->type == 1) {

            $users = $request->selected == [null] ? Guides::all() : Guides::whereIn('id', $request->selected)->get();
        } else {
            $users = Tourist::all();
        }
        $bodyMail = MailUser::create([
            'body' => $request->body
        ]);

        $attachment = null;

        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $attachment = $bodyMail->addMediaFromRequest('attachment')->toMediaCollection('mail_image');
        }

        SendMailJob::dispatch($users, $request->subject, $request->body, $attachment);

        return response(['message' => 'success', 'status' => 200]);
    }
}
