<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    /**
     * The courier's setting for a given store.
     * Usage: $courier->setting (requires store_id scope or global context).
     * For per-store use, rely on the CourierSetting model directly.
     */
    public function settings()
    {
        return $this->hasMany(CourierSetting::class);
    }

    /**
     * Convenience: get setting for the currently active store.
     * Override $storeId as needed in your controller.
     */
    public function settingForStore(int $storeId)
    {
        return $this->settings()->where('store_id', $storeId)->first();
    }
}
