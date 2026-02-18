<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use App\Models\State;

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
        //


        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('states')) {
                View::share('headerStates', State::orderBy('name', 'asc')->limit(10)->get());
            } else {
                View::share('headerStates', collect());
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('categories')) {
                View::share('parentCategories', Category::where('parent_id', 0)
                    ->where('type', 'company')
                    ->with('children')
                    ->orderBy('name', 'asc')
                    ->get());

                View::share('eventCategories', Category::where('parent_id', 0)
                    ->where('type', 'event')
                    ->with('children')
                    ->orderBy('name', 'asc')
                    ->get());
            } else {
                View::share('parentCategories', collect());
                View::share('eventCategories', collect());
            }
        } catch (\Exception $e) {
            // Suppress errors during migration or if tables don't exist
            View::share('headerStates', collect());
            View::share('parentCategories', collect());
            View::share('eventCategories', collect());
        }
    }
}
