<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'map_url',
        'ticket_price',
        'open_hours',
        'status',
    ];

    protected $casts = [
        'ticket_price' => 'decimal:2',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(DestinationImage::class);
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(DestinationImage::class)->where('is_primary', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->whereNotNull('approved_at');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
