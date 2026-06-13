<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ThemeController extends Controller
{
    public function index()
    {
        $theme = DB::table('theme_settings')->first();
        if (!$theme) {
            $theme = (object) [
                'primary' => '#8b7355',
                'secondary' => '#d4c4b0',
                'accent' => '#c9a86c',
                'background' => '#faf8f5',
                'text' => '#3d3530',
            ];
        }
        return view('admin.themes', compact('theme'));
    }

    public function update(Request $request)
    {
        $data = Validator::make($request->all(), [
            'primary' => ['required','string','size:7','regex:/^#[0-9a-fA-F]{6}$/'],
            'secondary' => ['required','string','size:7','regex:/^#[0-9a-fA-F]{6}$/'],
            'accent' => ['required','string','size:7','regex:/^#[0-9a-fA-F]{6}$/'],
            'background' => ['required','string','size:7','regex:/^#[0-9a-fA-F]{6}$/'],
            'text' => ['required','string','size:7','regex:/^#[0-9a-fA-F]{6}$/'],
        ])->validate();

        DB::table('theme_settings')->updateOrInsert(['id' => 1], array_merge($data, ['updated_at' => now()]));

        return redirect()->back()->with('status', 'Theme updated');
    }
}
