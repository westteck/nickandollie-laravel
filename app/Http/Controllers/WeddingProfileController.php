<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WeddingProfileController extends Controller
{
    /**
     * Display a user's wedding profile.
     * If $id is null, show own profile (requires auth).
     * If $id is provided, show that user's public profile.
     */
    public function show(Request $request, int $id = null): View|RedirectResponse
    {
        // If no ID provided and not authenticated, redirect to login
        if ($id === null && !auth()->check()) {
            return redirect()->route('login');
        }

        // Determine which user to show
        $userId = $id ?? auth()->id();

        // Fetch user info
        $user = \App\Models\User::find($userId);

        if (!$user) {
            abort(404);
        }

        // Hydrate legacy contact fields for view compatibility
        $nameParts = explode(' ', (string) $user->name, 2);
        $userArray = (array) $user;
        $userArray['first_name'] = $nameParts[0] ?? null;
        $userArray['last_name'] = $nameParts[1] ?? null;
        $userArray['guest_name'] = $user->name;
        $user = (object) $userArray;

        // Count user's photos
        $photoCount = DB::table('photos')
            ->where('uploader_id', $userId)
            ->count();

        // Fetch user's photos (paginated)
        $page = $request->input('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $photos = DB::table('photos')
            ->where('uploader_id', $userId)
            ->orderBy('uploaded_at', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'thumb_url' => '/storage/thumbs/' . $p->thumb_filename,
                    'photo_url' => '/storage/print/' . $p->print_filename,
                    'caption' => $p->caption ?? '',
                    'likes' => (int) $p->likes,
                ];
            });

        $totalPhotos = DB::table('photos')
            ->where('uploader_id', $userId)
            ->count();

        // If viewing own profile, fetch favorites
        $favorites = [];
        if (auth()->check() && auth()->id() === $userId) {
            $favorites = DB::table('favorites')
                ->select('photos.*')
                ->join('photos', 'favorites.photo_id', '=', 'photos.id')
                ->where('favorites.user_id', $userId)
                ->orderBy('favorites.created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'thumb_url' => '/storage/thumbs/' . $p->thumb_filename,
                        'photo_url' => '/storage/print/' . $p->print_filename,
                        'caption' => $p->caption ?? '',
                    ];
                });
        }

        // Get relationship label
        $relationshipLabel = $user->specific_relationship ?? $user->core_group ?? 'Guest';

        return view('wedding.profile', [
            'profileUser' => $user,
            'photoCount' => $photoCount,
            'photos' => $photos,
            'totalPhotos' => $totalPhotos,
            'currentPage' => $page,
            'totalPages' => ceil($totalPhotos / $limit),
            'favorites' => $favorites,
            'relationshipLabel' => $relationshipLabel,
            'isOwnProfile' => auth()->check() && auth()->id() === $userId,
        ]);
    }
}
