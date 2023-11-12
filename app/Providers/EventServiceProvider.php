<?php

namespace App\Providers;

use App\Http\Controllers\Api\AccommmoditionController;
use App\Http\Controllers\Api\AttactiveController;
use App\Models\accommmodition;
use App\Models\AttractiveLocation;
use App\Models\Banner;
use App\Models\Driver;
use App\Models\FlightRservation;
use App\Models\Guides;
use App\Models\Offer;
use App\Models\Pages;
use App\Models\Tourist;
use App\Observers\ClientObserver;
use App\Observers\MediaObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {

        Pages::observe(MediaObserver::class);
        Offer::observe(MediaObserver::class);
        FlightRservation::observe(MediaObserver::class);
        accommmodition::observe(MediaObserver::class);
        AttractiveLocation::observe(MediaObserver::class);
        Banner::observe(MediaObserver::class);
        //clients models
        Guides::observe(ClientObserver::class);
        Driver::observe(ClientObserver::class);
        Tourist::observe(ClientObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
