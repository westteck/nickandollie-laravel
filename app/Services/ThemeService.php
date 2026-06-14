<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ThemeService
{
    public const PRESETS = [
        'fortune_gold' => [
            'name' => 'Fortune Gold',
            'primary' => '#8b7355',
            'secondary' => '#d4c4b0',
            'accent' => '#c9a86c',
            'background' => '#faf8f5',
            'text' => '#3d3530',
        ],
        'blush_romance' => [
            'name' => 'Blush Romance',
            'primary' => '#c4827f',
            'secondary' => '#f5e0dc',
            'accent' => '#e8b4b8',
            'background' => '#fdf6f6',
            'text' => '#4a3030',
        ],
        'sage_garden' => [
            'name' => 'Sage Garden',
            'primary' => '#7d9b76',
            'secondary' => '#d4e0cb',
            'accent' => '#b2c9a3',
            'background' => '#f7faf5',
            'text' => '#2d3b2d',
        ],
        'navy_cream' => [
            'name' => 'Navy & Cream',
            'primary' => '#2c3e50',
            'secondary' => '#f0ece3',
            'accent' => '#8b7355',
            'background' => '#faf8f7',
            'text' => '#2c3e50',
        ],
        'plum_gold' => [
            'name' => 'Plum & Gold',
            'primary' => '#6b3a5b',
            'secondary' => '#f0e4d7',
            'accent' => '#c9a86c',
            'background' => '#faf6f8',
            'text' => '#3d2a3d',
        ],
    ];

    public static function getPresets(): array
    {
        return self::PRESETS;
    }

    public static function getPreset(string $key): ?array
    {
        return self::PRESETS[$key] ?? null;
    }

    public static function applyPreset(string $key): bool
    {
        $preset = self::getPreset($key);
        if (!$preset) {
            return false;
        }

        // Unset 'name' as it's not a DB column
        unset($preset['name']);

        DB::table('theme_settings')->updateOrInsert(
            ['id' => 1],
            array_merge($preset, ['updated_at' => now()])
        );

        return true;
    }

    public static function getCurrentColors(): array
    {
        $row = DB::table('theme_settings')->where('id', 1)->first();
        if (!$row) {
            return [
                'primary' => '#8b7355',
                'secondary' => '#d4c4b0',
                'accent' => '#c9a86c',
                'background' => '#faf8f5',
                'text' => '#3d3530',
            ];
        }

        return [
            'primary' => $row->primary,
            'secondary' => $row->secondary,
            'accent' => $row->accent,
            'background' => $row->background,
            'text' => $row->text,
        ];
    }

    public static function getCurrentPreset(): ?string
    {
        $current = self::getCurrentColors();

        foreach (self::PRESETS as $key => $preset) {
            $presetColors = [
                'primary' => $preset['primary'],
                'secondary' => $preset['secondary'],
                'accent' => $preset['accent'],
                'background' => $preset['background'],
                'text' => $preset['text'],
            ];

            if ($current === $presetColors) {
                return $key;
            }
        }

        return null;
    }
}
