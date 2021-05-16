<?php

namespace App\Providers;

use App\Repositories\SubscriptionRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SubscriptionRepository::class
        );
    }
}
