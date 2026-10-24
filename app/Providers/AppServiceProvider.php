<?php

namespace App\Providers;

use App\Contracts\SaeIntegrationInterface;
use App\Models\RefSiswa;
use App\Policies\RefSiswaPolicy;
use App\Services\Integration\SaeDatabaseDriver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SaeIntegrationInterface::class, SaeDatabaseDriver::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(RefSiswa::class, RefSiswaPolicy::class);
    }
}
