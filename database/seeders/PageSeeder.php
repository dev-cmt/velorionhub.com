<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    /**
     * php artisan db:seed --class=PageSeeder
     */
    public function run(): void
    {
        $pages = [
            ['title' => 'Home Page', 'slug' => 'home', 'content' => 'This is the home page content.'],
            ['title' => 'About Us', 'slug' => 'about-us', 'content' => 'This is the about us page content.'],
            ['title' => 'Contact Us', 'slug' => 'contacts', 'content' => 'This is the contact us page content.'],
            ['title' => 'Shop', 'slug' => 'shop', 'content' => 'This is the shop page content.'],
            ['title' => 'Catalog', 'slug' => 'catalog', 'content' => 'This is the catalog page content.'],
            ['title' => 'Blog', 'slug' => 'blog', 'content' => 'This is the blog page content.'],
            ['title' => 'FAQ', 'slug' => 'faq', 'content' => 'This is the FAQ page content.'],
            ['title' => 'Track Order', 'slug' => 'track-order', 'content' => 'This is the track order page content.'],
            ['title' => 'Cart', 'slug' => 'cart', 'content' => 'This is the cart page content.'],
            ['title' => 'Checkout', 'slug' => 'checkout', 'content' => 'This is the checkout page content.'],
            ['title' => 'Wishlist', 'slug' => 'wishlist', 'content' => 'This is the wishlist page content.'],
            ['title' => 'Compare', 'slug' => 'compare', 'content' => 'This is the compare page content.'],
            ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'content' => 'This is the privacy policy content.'],
            ['title' => 'Terms & Conditions', 'slug' => 'terms-and-conditions', 'content' => 'This is the terms and conditions content.'],
        ];

        foreach ($pages as $p) {
            $page = Page::updateOrCreate(['slug' => $p['slug']], [
                'title' => $p['title'],
                'content' => $p['content']
            ]);

            // Seed SEO for each page
            $page->seo()->updateOrCreate([], [
                'meta_title' => $p['title'] . ' - ' . config('app.name'),
                'meta_description' => 'Description for ' . $p['title'],
                'meta_keywords' => strtolower($p['title']) . ', keyword1, keyword2',
            ]);
        }
    }
}
