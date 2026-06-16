<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
                (object)['id' => 1, 'value' => 'nick', 'label' => "Nick's Side"],
                (object)['id' => 2, 'value' => 'ollie', 'label' => "Ollie's Side"],
                (object)['id' => 3, 'value' => 'both', 'label' => 'Both Sides'],
            ]);
        }

        if ($coreGroups->isEmpty()) {
            $coreGroups = collect([
                (object)['id' => 1, 'value' => 'Immediate Family', 'label' => 'Immediate Family'],
                (object)['id' => 2, 'value' => 'Extended Family / Relatives', 'label' => 'Extended Family / Relatives'],
                (object)['id' => 3, 'value' => 'Sponsors & Godparents', 'label' => 'Sponsors & Godparents'],
                (object)['id' => 4, 'value' => 'Friends & Community', 'label' => 'Friends & Community'],
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
            'name' => ['nullable', 'string', 'max:255'],
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
            'name' => $validated['name'] ?? $validated['guest_name'],
            'guest_name' => $validated['guest_name'],
            'first_name' => $validated['first_name'] ?? null,
            'last_name' => $validated['last_name'] ?? null,
            'username' => $validated['username'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'connection' => $validated['connection'],
            'core_group' => $coreGroupLabel ?? $validated['core_group'],
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

        // Create address book entry for this user
        $fullName = trim(($validated['first_name'] ?? '') . ' ' . ($validated['last_name'] ?? '')) ?: $validated['guest_name'];
        DB::table('address_book')->insert([
            'user_id' => $user->id,
            'entry_name' => $fullName,
            'first_name' => $validated['first_name'] ?? '',
            'address' => $validated['address'] ?? '',
            'city' => $validated['city'] ?? '',
            'state' => $validated['state'] ?? '',
            'zip' => $validated['zip'] ?? '',
            'email' => $validated['phone_email'] ?? '',
            'phone' => $validated['phone'] ?? '',
            'mobile' => $validated['mobile'] ?? '',
            'notes' => '',
            'show_in_phonebook' => 1,
            'created_at' => now(),
        ]);

        // Send welcome email (non-blocking: errors logged but not surfaced)
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Welcome email failed for user ' . $user->id . ': ' . $e->getMessage());
        }

        return redirect(route('dashboard', absolute: false));
    }
}
