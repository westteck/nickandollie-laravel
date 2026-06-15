<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookupOption extends Model
{
    use HasFactory;

    protected $table = 'lookup_options';
    public $timestamps = false;

    protected $fillable = [
        'option_type',
        'option_value',
        'label',
        'sort_order',
    ];

    public static function getOptions(string $type): array
    {
        return static::where('option_type', $type)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($o) => ['value' => $o->option_value, 'label' => $o->label])
            ->toArray();
    }

    public static function getConnections(): array
    {
        return static::getOptions('connection');
    }

    public static function getCoreGroups(): array
    {
        return static::getOptions('core_group');
    }

    public static function getRelationships(): array
    {
        return static::getOptions('specific_relationship');
    }
}
