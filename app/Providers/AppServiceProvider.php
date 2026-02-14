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

                
        View::share('headerStates', State::orderBy('name', 'asc')->limit(10)->get());

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
    }
}
