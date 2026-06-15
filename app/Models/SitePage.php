<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    use HasFactory;

    protected $table = 'site_pages';
    public $timestamps = false;

    protected $fillable = [
        'page_key',
        'page_content',
        'title',
    ];

    public static function getContent(string $key, string $default = ''): string
    {
        $page = static::where('page_key', $key)->first();
        return $page ? $page->page_content : $default;
    }

    public static function setContent(string $key, string $content): void
    {
        static::updateOrCreate(
            ['page_key' => $key],
            ['page_content' => $content]
        );
    }
}
