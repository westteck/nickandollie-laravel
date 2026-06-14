<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ThemeController extends Controller
{
    public function index()
    {
        $presets = ThemeService::getPresets();
        $currentPreset = ThemeService::getCurrentPreset();
        $currentColors = ThemeService::getCurrentColors();

        // Cast to object for blade compatibility
        $theme = (object) $currentColors;

        return view('admin.themes', compact('presets', 'currentPreset', 'currentColors', 'theme'));
    }

    public function switch(Request $request)
    {
        $key = $request->input('preset');

        if (!array_key_exists($key, ThemeService::getPresets())) {
            return redirect()->back()->with('error', 'Invalid preset selected.');
        }

        ThemeService::applyPreset($key);

        return redirect()->back()->with('status', 'Theme preset "' . ThemeService::getPreset($key)['name'] . '" applied.');
    }

    public function preview(Request $request)
    {
        $key = $request->input('preset');

        $preset = ThemeService::getPreset($key);

        if (!$preset) {
            return response()->json(['error' => 'Invalid preset'], 404);
        }

        // Return only the color fields, not the name
        $colors = $preset;
        unset($colors['name']);

        return response()->json(['colors' => $colors]);
    }

    public function update(Request $request)
    {
        $data = Validator::make($request->all(), [
            'primary' => ['required', 'string', 'size:7', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'secondary' => ['required', 'string', 'size:7', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'accent' => ['required', 'string', 'size:7', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'background' => ['required', 'string', 'size:7', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'text' => ['required', 'string', 'size:7', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ])->validate();

        DB::table('theme_settings')->updateOrInsert(['id' => 1], array_merge($data, ['updated_at' => now()]));

        return redirect()->back()->with('status', 'Theme updated');
    }
}
