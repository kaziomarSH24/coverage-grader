<?php

namespace App\Providers;

// use App\Models\Product;
// use App\Observers\ProductObserver;

use App\Models\PolicyCategory;
use App\Models\User;
use App\Observers\Admin\PolicyCategoryObserver;
use App\Observers\UserObserve;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Defining super-admin through Gate
        // This code will run before checking any permission
         Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // Observers
        // Product::observe(ProductObserver::class);
        User::observe(UserObserve::class);
        PolicyCategory::observe(PolicyCategoryObserver::class);

    }
}
