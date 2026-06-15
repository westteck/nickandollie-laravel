<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Photo extends Model
{
    use HasFactory;

    protected $table = 'photos';
    public $timestamps = true;

    protected $fillable = [
        'filename',
        'original_filename',
        'thumb_filename',
        'print_filename',
        'uploader_id',
        'caption',
        'photo_number',
        'likes',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function contestEntries(): HasMany
    {
        return $this->hasMany(ContestEntry::class);
    }

    public function isLikedBy(int $userId): bool
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function isFavoritedBy(int $userId): bool
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    public function getUserRating(int $userId): int
    {
        return (int) $this->ratings()->where('user_id', $userId)->value('rating');
    }

    public function averageRating(): float
    {
        return (float) $this->ratings()->avg('rating');
    }

    public function ratingCount(): int
    {
        return (int) $this->ratings()->count();
    }
}
