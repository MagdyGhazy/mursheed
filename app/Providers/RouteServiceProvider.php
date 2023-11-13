<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));


            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/users.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/tourism.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/guides.php'));
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/offer.php'));
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/flight.php'));
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/page.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/driver.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/banner.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/settings.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/password_reset.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/tourist.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/country_price.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/trip.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/rating.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/favourite.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/report.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/TermsAndCondition.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/permaission.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/Chat.php'));

        });
    }
}