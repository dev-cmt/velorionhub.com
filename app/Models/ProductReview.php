<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'name',
        'email',
        'rating',
        'comment',
        'status',
    ];

    // Relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Polymorphic media relationship
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    // Relationship to User (optional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for approved reviews
    public function scopeApproved($query)
    {
        return $query->where('status', 1);
    }
}
