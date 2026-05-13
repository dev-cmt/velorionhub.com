<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'status',
        'is_menu',
        'is_home',
        'is_section',
        'is_footer',
    ];

    public function seo(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }
    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function getImageAttribute(): ?string
    {
        $media = $this->relationLoaded('media')
            ? $this->media->first()
            : $this->media()->ordered()->first();

        if ($media) {
            return $media->path;
        }

        return $this->attributes['image'] ?? null;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(fn($category) => $category->slug = Str::slug($category->name));
        static::updating(fn($category) => $category->slug = Str::slug($category->name));
    }

}
