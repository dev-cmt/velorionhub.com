<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Page;

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

        if ($settings && $settings->active_theme) {
            $themes = config('theme');
            if (isset($themes[$settings->active_theme])) {
                config([
                    'theme.getTheme' => $themes[$settings->active_theme],
                    'theme.frontend' => $themes[$settings->active_theme],
                ]);
            }
        }

        $viewsPath = config("theme.getTheme.views_path");
        View::share('viewsPath', $viewsPath);

        $categories = Category::where('status', true)->whereNull('parent_id')->with('children')->get();
        View::share('categories', $categories);

        $pages = Page::where('status', true)->get();
        View::share('pages', $pages);
    }
}
