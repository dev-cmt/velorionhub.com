<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'logo',
        'logo_dark',
        'logo_light',
        'favicon',
        'phone',
        'phone2',
        'email',
        'email2',
        'alert_email',
        'address',
        'map_url',
        'description',
        'copyright_text',
        'social_links',

        'currency_rates',
        'currency_symbols',

        'shipping_inside',
        'shipping_outside',
        'shipping_active',

        'fb_pixel',
        'gtm_head',
        'gtm_body',

        'is_loading',
        'is_slider',
        'active_theme',
    ];

    protected $casts = [
        'currency_symbols' => 'array',
        'currency_rates' => 'array',
        'social_links' => 'array',
    ];
}
