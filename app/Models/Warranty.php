<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warranty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'period',
        'description',
        'status',
    ];

    /**
     * Accessor: Full warranty label
     * Example: 12 Months
     */
    public function getFullDurationAttribute()
    {
        return $this->duration . ' ' . ucfirst($this->period);
    }
}
