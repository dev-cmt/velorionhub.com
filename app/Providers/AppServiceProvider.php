<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

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
        $settings = Setting::first() ?? null;
        View::share('settings', $settings);

        $categories = \App\Models\Category::where('status', true)->whereNull('parent_id')->with('children')->get();
        View::share('categories', $categories);

        $pages = \App\Models\Page::where('status', true)->get();
        View::share('pages', $pages);
    }
}
