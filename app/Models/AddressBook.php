<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddressBook extends Model
{
    use HasFactory;

    protected $table = 'address_book';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'entry_name',
        'first_name',
        'last_name',
        'address',
        'city',
        'state',
        'zip',
        'email',
        'phone',
        'mobile',
        'show_in_phonebook',
    ];

    protected $casts = [
        'show_in_phonebook' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->entry_name ?: trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }
}
