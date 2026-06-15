<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class PhotoController extends Controller
{
    /**
     * Display a single photo with all its details.
     */
    public function show(Request $request, int $id): View|JsonResponse
    {
        // Fetch photo with uploader info
        $photo = DB::table('photos')
            ->select(
                'photos.*',
                'users.guest_name as uploader_name',
                'users.id as uploader_id'
            )
            ->leftJoin('users', 'photos.uploader_id', '=', 'users.id')
            ->where('photos.id', $id)
            ->first();

        if (!$photo) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Photo not found'], 404);
            }
            abort(404);
        }

        // Count likes
        $likes = DB::table('votes')
            ->where('photo_id', $id)
            ->count();

        // Check if current user liked/favorited/rated
        $userLiked = false;
        $userFavorited = false;
        $userRating = 0;

        if (auth()->check()) {
            $userId = auth()->id();

            $userLiked = DB::table('votes')
                ->where('photo_id', $id)
                ->where('user_id', $userId)
                ->exists();

            $userFavorited = DB::table('favorites')
                ->where('photo_id', $id)
                ->where('user_id', $userId)
                ->exists();

            $userRatingRow = DB::table('ratings')
                ->where('photo_id', $id)
                ->where('user_id', $userId)
                ->first();
            $userRating = $userRatingRow->rating ?? 0;
        }

        // Fetch comments with user info
        $comments = DB::table('comments')
            ->select('comments.*', 'users.guest_name as user_name')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.photo_id', $id)
            ->orderBy('comments.created_at', 'desc')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'user' => $c->user_name ?? 'Guest',
                    'text' => $c->comment,
                    'time' => $c->created_at ? \Carbon\Carbon::parse($c->created_at)->diffForHumans() : 'recently',
                ];
            });

        // Fetch active contests for dropdown
        $contests = DB::table('contests')
            ->where('status', 'active')
            ->orderBy('end_date', 'asc')
            ->get()
            ->map(function ($c) {
                return (object) [
                    'id' => $c->id,
                    'title' => $c->title,
                    'end_date' => $c->end_date,
                ];
            });

        // Check if photo is entered in any contests
        $enteredContests = DB::table('contest_entries')
            ->where('photo_id', $id)
            ->pluck('contest_id')
            ->toArray();

        return view('wedding.photo', [
            'photo' => $photo,
            'likes' => $likes,
            'userLiked' => $userLiked,
            'userFavorited' => $userFavorited,
            'userRating' => $userRating,
            'comments' => $comments,
            'contests' => $contests,
            'enteredContests' => $enteredContests,
        ]);
    }
}
