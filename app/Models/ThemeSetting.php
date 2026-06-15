<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    use HasFactory;

    protected $table = 'theme_settings';
    public $timestamps = false;

    protected $fillable = [
        'primary',
        'secondary',
        'accent',
        'background',
        'text',
    ];

    public static function getCurrent(): self
    {
        return static::first() ?? new static([
            'primary' => '#8b7355',
            'secondary' => '#d4c4b0',
            'accent' => '#c9a86c',
            'background' => '#faf8f5',
            'text' => '#3d3530',
        ]);
    }

    public static function getColors(): array
    {
        $theme = static::getCurrent();
        return [
            'primary' => $theme->primary,
            'secondary' => $theme->secondary,
            'accent' => $theme->accent,
            'background' => $theme->background,
            'text' => $theme->text,
        ];
    }
}
