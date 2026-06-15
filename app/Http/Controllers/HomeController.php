<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        // Logged-in users go straight to gallery
        if (Auth::check()) {
            return redirect()->route('gallery');
        }

        // Fetch hero content from site_pages table
        $hero_content = DB::table('site_pages')
            ->where('page_key', 'index_hero')
            ->value('content');

        // Default if null
        if (!$hero_content) {
            $hero_content = '<h1>Nick &amp; Ollie Fortune</h1><p class="hero__date">November 13, 2026</p><p class="hero__tagline">Tulay sa aming pagdiriwang — ikuwento ang iyong kwento, ikuwento ang saya!</p>';
        }

        return view('home', [
            'hero_content' => $hero_content,
        ]);
    }
}