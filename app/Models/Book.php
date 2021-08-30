<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Book extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'description', 'price', 'discount', 'user_id', 'approved'];

    protected $casts = ['discount' => 'integer'];

    protected $perPage = 25;

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', '1');
    }

    public function scopeUnapproved($query)
    {
        return $query->where('approved', 0);
    }

    public function approve()
    {
        return $this->update(['approved' => 1]);
    }

    public function attachCover($file)
    {
        return $this->addMedia($file)->toMediaCollection('covers');
    }

    public function getDiscountPriceAttribute()
    {
        return round($this->price - ($this->price * $this->discount / 100));
    }

    public function getFinalPriceAttribute()
    {
        if ($this->discount) {
            return (int) $this->discount_price;
        }

        return (int) $this->price;
    }

    public function getIsNewAttribute()
    {
        return now()->subDays(7) <= $this->created_at;
    }

    public function getCoverAttribute()
    {
        return $this->getMedia('covers')->last();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('cover')
              ->fit(Manipulations::FIT_CONTAIN, 330, 384);
    }
}
