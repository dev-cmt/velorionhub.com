<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\SearchTermService;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Page;
use App\Models\Order;
use App\Observers\OrderObserver;

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

        // Also bind to IoC container so section templates can access via app('settings')
        $this->app->instance('settings', $settings);

        // Theme Configuration
        if ($settings && $settings->active_theme) {
            $themes = config('theme');
            if (isset($themes[$settings->active_theme])) {
                config([
                    'theme.getTheme' => $themes[$settings->active_theme],
                    'theme.frontend' => $themes[$settings->active_theme],
                ]);
            }
        }
        $filePath = config("theme.getTheme.file_path");
        View::share('filePath', $filePath);

        $categories = Category::where('status', true)->whereNull('parent_id')->with('children')->get();
        View::share('categories', $categories);

        $pages = Page::where('status', true)->orderBy('order')->orderBy('title')->get();
        View::share('pages', $pages);

        View::share('popularSearches', SearchTermService::popular());

        // Register Order Observer
        Order::observe(OrderObserver::class);
    }
}
