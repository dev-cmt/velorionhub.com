<?php

namespace App\Services;

use App\Models\SearchTerm;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SearchTermService
{
    public const CACHE_KEY = 'popular_searches.weekly';

    public static function record(?string $term): void
    {
        if (! Schema::hasTable('search_terms')) {
            return;
        }

        $term = self::normalize($term);

        if ($term === null) {
            return;
        }

        $searchTerm = SearchTerm::firstOrNew(['term' => $term]);
        $searchTerm->hits = ((int) $searchTerm->hits) + 1;
        $searchTerm->last_searched_at = now();
        $searchTerm->save();

        Cache::forget(self::CACHE_KEY);
    }

    public static function popular(int $limit = 6)
    {
        return Cache::remember(self::CACHE_KEY, now()->addWeek(), function () use ($limit) {
            if (! Schema::hasTable('search_terms')) {
                return collect();
            }

            return SearchTerm::where('last_searched_at', '>=', now()->subWeek())
                ->orderByDesc('hits')
                ->orderByDesc('last_searched_at')
                ->limit($limit)
                ->pluck('term');
        });
    }

    private static function normalize(?string $term): ?string
    {
        $term = trim((string) $term);
        $term = preg_replace('/\s+/', ' ', $term);
        $term = Str::lower($term);

        if ($term === '' || Str::length($term) < 2) {
            return null;
        }

        return Str::limit($term, 80, '');
    }
}
