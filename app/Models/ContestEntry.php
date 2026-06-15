<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContestEntry extends Model
{
    use HasFactory;

    protected $table = 'contest_entries';
    public $timestamps = false;

    protected $fillable = [
        'photo_id',
        'contest_id',
        'submitted_by',
        'status',
        'votes',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'votes' => 'integer',
    ];

    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class);
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photo::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
