<?php

namespace App\Observers;

use App\Http\Controllers\Api\EmailConfirmationController;
use App\Http\Controllers\Api\OTPController;
use App\Models\MursheedUser;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class ClientObserver
{
    public function created(Model $model)

    {

        $user = $model->mursheed_user()->create(
            [
                'email' => $model->email,
                'password' => Hash::make(request()->password)
            ]
        );

        (new OTPController)->generateOTP($user->email, '0');
    }

    /**
     * Handle the accommodition "updated" event.
     */
    public function updated(Model $model)
    {
        //        $model->clearMediaCollection('photos');
        ////
        //////        if (request()->images)
        //            $model->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
        //                $fileAdder->toMediaCollection('photos');
        //            });
    }

    /**
     * Handle the accommodition "
     * deleted" event.
     */
    public function deleted(Model $accommodition): void
    {
        //
    }

    /**
     * Handle the accommodition "restored" event.
     */
    public function restored(Model $accommodition): void
    {
        //
    }

    /**
     * Handle the accommodition "force deleted" event.
     */
    public function forceDeleted(Model $accommodition): void
    {
        //\
    }
}
