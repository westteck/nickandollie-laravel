<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = DB::table('settings')->first() ?? (object) [
            'site_title' => 'Nick & Ollie Fortune',
            'site_tagline' => '',
            'hero_title' => '',
            'hero_subtitle' => '',
            'contact_email' => '',
            'maintenance_mode' => false,
        ];

        $sitePages = DB::table('site_pages')
            ->orderBy('page_key', 'asc')
            ->get();

        return view('admin.settings', compact('settings', 'sitePages'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_title' => ['nullable', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:500'],
            'hero_title' => ['nullable', 'string', 'max:500'],
            'hero_subtitle' => ['nullable', 'string', 'max:500'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'maintenance_mode' => ['nullable', 'in:1'],
        ]);

        $data['maintenance_mode'] = $data['maintenance_mode'] ?? false;

        $existing = DB::table('settings')->first();
        if ($existing) {
            DB::table('settings')->where('id', $existing->id)->update(array_merge($data, ['updated_at' => now()]));
        } else {
            DB::table('settings')->insert(array_merge($data, ['created_at' => now(), 'updated_at' => now()]));
        }

        return redirect()->route('admin.settings')->with('status', 'Settings saved.');
    }

    /**
     * Save a single site_pages row (page content editor).
     */
    public function savePage(Request $request)
    {
        $data = $request->validate([
            'page_key' => ['required', 'string', 'max:100'],
            'content' => ['nullable', 'string'],
        ]);

        $existing = DB::table('site_pages')
            ->where('page_key', $data['page_key'])
            ->first();

        if ($existing) {
            DB::table('site_pages')
                ->where('id', $existing->id)
                ->update([
                    'content' => $data['content'],
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('site_pages')->insert([
                'page_key' => $data['page_key'],
                'content' => $data['content'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
