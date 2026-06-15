<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContestVote extends Model
{
    use HasFactory;

    protected $table = 'contest_votes';
    public $timestamps = false;

    protected $fillable = [
        'contest_entry_id',
        'user_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(ContestEntry::class, 'contest_entry_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
