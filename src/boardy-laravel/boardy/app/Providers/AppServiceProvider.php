<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \Laravel\Passport\Contracts\AuthorizationViewResponse::class,
            \App\Http\Responses\AuthorizationViewResponse::class
        );
    }

    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addMinutes(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    	User::observe(UserObserver::class);
    }
}