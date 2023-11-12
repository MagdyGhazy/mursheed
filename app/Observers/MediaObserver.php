<?php

namespace App\Observers;

use App\Models\accommmodition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MediaObserver
{





    /**
     * Handle the accommodition "created" event.
     */
    public function created(Model $model)

    {

        if (request()->images)
            $model->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('photos');
                });
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
