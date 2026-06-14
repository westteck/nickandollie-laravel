<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view with lookup options for dropdowns.
     */
    public function create(): View
    {
        // Fetch connection options from lookup_options
        $connections = DB::table('lookup_options')
            ->where('category', 'connection')
            ->orderBy('sort_order', 'asc')
            ->get();

        // Fetch core_group options from lookup_options
        $coreGroups = DB::table('lookup_options')
            ->where('category', 'core_group')
            ->orderBy('sort_order', 'asc')
            ->get();

        // If no lookup_options, use defaults
        if ($connections->isEmpty()) {
            $connections = collect([
                (object)['id' => 1, 'option_value' => 'nick', 'display_text' => "Nick's Side"],
                (object)['id' => 2, 'option_value' => 'ollie', 'display_text' => "Ollie's Side"],
                (object)['id' => 3, 'option_value' => 'both', 'display_text' => 'Both Sides'],
            ]);
        }

        if ($coreGroups->isEmpty()) {
            $coreGroups = collect([
                (object)['id' => 1, 'option_value' => 'Immediate Family', 'display_text' => 'Immediate Family'],
                (object)['id' => 2, 'option_value' => 'Extended Family / Relatives', 'display_text' => 'Extended Family / Relatives'],
                (object)['id' => 3, 'option_value' => 'Sponsors & Godparents', 'display_text' => 'Sponsors & Godparents'],
                (object)['id' => 4, 'option_value' => 'Friends & Community', 'display_text' => 'Friends & Community'],
            ]);
        }

        return view('auth.register', [
            'connections' => $connections,
            'coreGroups' => $coreGroups,
        ]);
    }

    /**
     * Handle an incoming registration request with all legacy fields.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'username' => ['nullable', 'string', 'max:30', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'connection' => ['required', 'string'],
            'core_group' => ['required', 'string'],
            'specific_relationship' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:50'],
            'zip' => ['nullable', 'string', 'max:20'],
            'phone_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'mobile' => ['nullable', 'string', 'max:30'],
        ]);

        // Look up connection label from lookup_options if available
        $connectionLabel = DB::table('lookup_options')
            ->where('category', 'connection')
            ->where('value', $validated['connection'])
            ->value('label');

        // Look up core_group label from lookup_options if available
        $coreGroupLabel = DB::table('lookup_options')
            ->where('category', 'core_group')
            ->where('value', $validated['core_group'])
            ->value('label');

        $user = User::create([
            'guest_name' => $validated['guest_name'],
            'first_name' => $validated['first_name'] ?? null,
            'last_name' => $validated['last_name'] ?? null,
            'username' => $validated['username'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'connection' => $validated['connection'],  // nick/ollie/both — matches ENUM column directly
            'core_group' => $coreGroupLabel ?? $validated['core_group'], // label text matches ENUM column
            'specific_relationship' => $validated['specific_relationship'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'zip' => $validated['zip'] ?? null,
            'phone_email' => $validated['phone_email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'mobile' => $validated['mobile'] ?? null,
            'user_type' => 'user',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
