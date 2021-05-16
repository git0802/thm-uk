<?php

namespace App\Providers;

use App\Image;
use App\Observers\ImageObserver;
use App\Observers\ProductObserver;
use App\Observers\StoreObserver;
use App\Observers\UserObserver;
use App\Observers\VideoObserver;
use App\Product;
use App\Store;
use App\User;
use App\Video;
use App\CouponCode;
use App\Observers\CouponCodeObserver;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Observers
        Store::observe(StoreObserver::class);
        Product::observe(ProductObserver::class);
        Video::observe(VideoObserver::class);
        CouponCode::observe(CouponCodeObserver::class);
        Image::observe(ImageObserver::class);
        User::observe(UserObserver::class);

        \URL::forceScheme('https');

        // MariaDB fix
        Builder::defaultStringLength(191);
    }
}
