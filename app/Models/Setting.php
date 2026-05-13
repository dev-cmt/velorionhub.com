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
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'whatsapp',
        'telegram',
        'is_loading',
        'is_slider',
    ];
}
