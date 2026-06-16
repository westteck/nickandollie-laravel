<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form (HTML or JSON).
     */
    public function edit(Request $request): View|JsonResponse
    {
        $user = $request->user();

        // If JSON request (AJAX from profile page), return user data + address book
        if ($request->wantsJson() || $request->expectsJson()) {
            $addr = DB::table('address_book')
                ->where('user_id', $user->id)
                ->first();

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'guest_name' => $user->guest_name,
                    'firstname' => $user->first_name,
                    'lastname' => $user->last_name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'connection' => $user->connection,
                    'core_group' => $user->core_group,
                    'specific_relationship' => $user->specific_relationship,
                    'profile_pic' => $user->profile_pic,
                    'user_type' => $user->user_type,
                    'created_at' => $user->created_at?->toISOString(),
                    'pb_show_in_phonebook' => $addr ? (bool)$addr->show_in_phonebook : true,
                    'pb_address' => $addr->address ?? '',
                    'pb_city' => $addr->city ?? '',
                    'pb_state' => $addr->state ?? '',
                    'pb_zip' => $addr->zip ?? '',
                    'pb_email' => $addr->email ?? '',
                    'pb_phone' => $addr->phone ?? '',
                    'pb_mobile' => $addr->mobile ?? '',
                ],
            ]);
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information (supports both form POST and JSON API).
     */
    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $input = $request->all();

        // Password change
        if (!empty($input['current_password']) && !empty($input['password'])) {
            if (!Hash::check($input['current_password'], $user->password)) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'error' => 'Current password is incorrect'], 422);
                }
                return Redirect::route('profile.edit')->with('error', 'Current password is incorrect');
            }
            $user->password = Hash::make($input['password']);
            $user->save();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Password updated']);
            }
            return Redirect::route('profile.edit')->with('status', 'password-updated');
        }

        // Update user fields
        $fields = [];
        if (isset($input['name'])) {
            $user->guest_name = htmlspecialchars(trim($input['name']), ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['firstname'])) {
            $user->first_name = htmlspecialchars(trim($input['firstname']), ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['lastname'])) {
            $user->last_name = htmlspecialchars(trim($input['lastname']), ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['username'])) {
            $user->guest_name = htmlspecialchars(trim($input['username']), ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['email'])) {
            $newEmail = strtolower(trim($input['email']));
            if ($newEmail !== $user->email) {
                $user->email = $newEmail;
                $user->email_verified_at = null;
            }
        }
        if (isset($input['connection'])) {
            $user->connection = htmlspecialchars(trim($input['connection']), ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['core_group'])) {
            $user->core_group = htmlspecialchars(trim($input['core_group']), ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['specific_relationship'])) {
            $user->specific_relationship = htmlspecialchars(trim($input['specific_relationship']), ENT_QUOTES, 'UTF-8');
        }

        // Handle profile pic (base64 data URI from camera/file input)
        if (isset($input['profile_pic']) && str_starts_with($input['profile_pic'], 'data:image')) {
            $data = $input['profile_pic'];
            // Extract base64 data
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $matches)) {
                $ext = $matches[1];
                $base64 = substr($data, strlen($matches[0]));
                $binary = base64_decode($base64);
                if ($binary !== false) {
                    $filename = 'profile_' . $user->id . '_' . time() . '.' . $ext;
                    $path = storage_path('app/public/profile_pics/' . $filename);
                    @mkdir(dirname($path), 0755, true);
                    file_put_contents($path, $binary);
                    $user->profile_pic = '/storage/profile_pics/' . $filename;
                }
            }
        }

        $user->save();

        // Update address book
        $pbFields = ['address', 'city', 'state', 'zip', 'phone_email', 'phone', 'mobile'];
        $hasPb = false;
        foreach ($pbFields as $f) {
            if (isset($input[$f]) || isset($input['pb_' . $f])) {
                $hasPb = true;
                break;
            }
        }

        if ($hasPb || isset($input['show_in_phonebook'])) {
            $addr = DB::table('address_book')->where('user_id', $user->id)->first();

            $pbData = [
                'entry_name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->guest_name,
                'first_name' => $user->first_name ?? '',
                'address' => $input['pb_address'] ?? $input['address'] ?? null,
                'city' => $input['pb_city'] ?? $input['city'] ?? null,
                'state' => $input['pb_state'] ?? $input['state'] ?? null,
                'zip' => $input['pb_zip'] ?? $input['zip'] ?? null,
                'email' => $input['pb_email'] ?? $input['phone_email'] ?? null,
                'phone' => $input['pb_phone'] ?? $input['phone'] ?? null,
                'mobile' => $input['pb_mobile'] ?? $input['mobile'] ?? null,
                'show_in_phonebook' => isset($input['show_in_phonebook']) ? 1 : 0,
            ];

            if ($addr) {
                DB::table('address_book')
                    ->where('user_id', $user->id)
                    ->update(array_merge($pbData, ['updated_at' => now()]));
            } else {
                DB::table('address_book')->insert(array_merge($pbData, [
                    'user_id' => $user->id,
                    'created_at' => now(),
                ]));
            }

            // Sync address_book.entry_name with guest_name
            DB::table('address_book')
                ->where('user_id', $user->id)
                ->update(['entry_name' => $user->guest_name]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Profile updated']);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Get user's favorited photos.
     */
    public function favorites(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $favorites = DB::table('favorites')
            ->join('photos', 'favorites.photo_id', '=', 'photos.id')
            ->where('favorites.user_id', $userId)
            ->select('photos.id', 'photos.caption', 'photos.thumb_filename', 'photos.print_filename')
            ->orderBy('favorites.created_at', 'desc')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'caption' => $p->caption,
                    'thumb_url' => '/storage/thumbs/' . ($p->thumb_filename ?? $p->print_filename),
                ];
            });

        return response()->json(['success' => true, 'favorites' => $favorites]);
    }

    /**
     * Get user's uploaded photos.
     */
    public function uploads(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $photos = DB::table('photos')
            ->where('uploader_id', $userId)
            ->orderBy('uploaded_at', 'desc')
            ->get(['id', 'caption', 'thumb_filename', 'filename'])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'caption' => $p->caption,
                    'thumb_url' => '/storage/thumbs/' . $p->thumb_filename,
                ];
            });

        return response()->json(['success' => true, 'uploads' => $photos]);
    }

    /**
     * Get user's votes.
     */
    public function votes(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $votes = DB::table('votes')
            ->join('photos', 'votes.photo_id', '=', 'photos.id')
            ->where('votes.user_id', $userId)
            ->select('photos.id', 'photos.caption', 'photos.thumb_filename', 'votes.created_at')
            ->orderBy('votes.created_at', 'desc')
            ->get()
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'caption' => $v->caption,
                    'thumb_url' => '/storage/thumbs/' . $v->thumb_filename,
                    'voted_at' => $v->created_at,
                ];
            });

        return response()->json(['success' => true, 'votes' => $votes]);
    }

    /**
     * Get user's comments.
     */
    public function comments(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $comments = DB::table('comments')
            ->join('photos', 'comments.photo_id', '=', 'photos.id')
            ->where('comments.user_id', $userId)
            ->select('comments.*', 'photos.caption as photo_caption', 'photos.thumb_filename')
            ->orderBy('comments.created_at', 'desc')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'photo_id' => $c->photo_id,
                    'comment' => $c->comment,
                    'photo_caption' => $c->photo_caption,
                    'thumb_url' => '/storage/thumbs/' . $c->thumb_filename,
                    'created_at' => $c->created_at ? \Carbon\Carbon::parse($c->created_at)->diffForHumans() : 'recently',
                ];
            });

        return response()->json(['success' => true, 'comments' => $comments]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
