<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PhonebookController;
use Illuminate\Support\Facades\DB;

// Phonebook API (existing)
Route::get('/phonebook-list', PhonebookController::class)->name('api.phonebook.list');

// Photo API endpoints
Route::prefix('photo/{id}')->group(function () {
    
    // Toggle like
    Route::post('/like', function (int $id) {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }
        
        $userId = auth()->id();
        $photo = DB::table('photos')->where('id', $id)->first();
        if (!$photo) {
            return response()->json(['success' => false, 'error' => 'Photo not found'], 404);
        }
        
        // Check if already liked
        $existing = DB::table('votes')
            ->where('photo_id', $id)
            ->where('user_id', $userId)
            ->first();
        
        if ($existing) {
            // Unlike
            DB::table('votes')->where('id', $existing->id)->delete();
            $liked = false;
        } else {
            // Like
            DB::table('votes')->insert([
                'photo_id' => $id,
                'user_id' => $userId,
                'created_at' => now(),
            ]);
            $liked = true;
        }
        
        $likes = DB::table('votes')->where('photo_id', $id)->count();
        
        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes' => $likes,
        ]);
    })->name('api.photo.like');

    // Toggle favorite
    Route::post('/favorite', function (int $id) {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }
        
        $userId = auth()->id();
        $photo = DB::table('photos')->where('id', $id)->first();
        if (!$photo) {
            return response()->json(['success' => false, 'error' => 'Photo not found'], 404);
        }
        
        // Check if already favorited
        $existing = DB::table('favorites')
            ->where('photo_id', $id)
            ->where('user_id', $userId)
            ->first();
        
        if ($existing) {
            // Unfavorite
            DB::table('favorites')->where('id', $existing->id)->delete();
            $favorited = false;
        } else {
            // Favorite
            DB::table('favorites')->insert([
                'photo_id' => $id,
                'user_id' => $userId,
                'created_at' => now(),
            ]);
            $favorited = true;
        }
        
        return response()->json([
            'success' => true,
            'favorited' => $favorited,
        ]);
    })->name('api.photo.favorite');

    // Set rating (1-5)
    Route::post('/rate', function (int $id, \Illuminate\Http\Request $request) {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }
        
        $userId = auth()->id();
        $rating = (int) $request->input('rating', 0);
        
        if ($rating < 1 || $rating > 5) {
            return response()->json(['success' => false, 'error' => 'Rating must be 1-5'], 400);
        }
        
        $photo = DB::table('photos')->where('id', $id)->first();
        if (!$photo) {
            return response()->json(['success' => false, 'error' => 'Photo not found'], 404);
        }
        
        // Upsert rating
        $existing = DB::table('ratings')
            ->where('photo_id', $id)
            ->where('user_id', $userId)
            ->first();
        
        if ($existing) {
            DB::table('ratings')->where('id', $existing->id)->update(['rating' => $rating]);
        } else {
            DB::table('ratings')->insert([
                'photo_id' => $id,
                'user_id' => $userId,
                'rating' => $rating,
                'created_at' => now(),
            ]);
        }
        
        // Calculate average
        $stats = DB::table('ratings')
            ->where('photo_id', $id)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as count')
            ->first();
        
        return response()->json([
            'success' => true,
            'average_rating' => round($stats->avg_rating, 1),
            'rating_count' => $stats->count,
        ]);
    })->name('api.photo.rate');

    // Get comments
    Route::get('/comments', function (int $id) {
        $photo = DB::table('photos')->where('id', $id)->first();
        if (!$photo) {
            return response()->json(['success' => false, 'error' => 'Photo not found'], 404);
        }
        
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
        
        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]);
    })->name('api.photo.comments');

    // Add comment
    Route::post('/comment', function (int $id, \Illuminate\Http\Request $request) {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }
        
        $userId = auth()->id();
        $comment = trim($request->input('comment', ''));
        
        if (empty($comment)) {
            return response()->json(['success' => false, 'error' => 'Comment cannot be empty'], 400);
        }
        
        $photo = DB::table('photos')->where('id', $id)->first();
        if (!$photo) {
            return response()->json(['success' => false, 'error' => 'Photo not found'], 404);
        }
        
        $commentId = DB::table('comments')->insertGetId([
            'photo_id' => $id,
            'user_id' => $userId,
            'comment' => $comment,
            'created_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'comment_id' => $commentId,
        ]);
    })->name('api.photo.comment');

    // Enter photo in contest
    Route::post('/enter-contest', function (int $id, \Illuminate\Http\Request $request) {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }
        
        $userId = auth()->id();
        $contestId = (int) $request->input('contest_id', 0);
        
        if (!$contestId) {
            return response()->json(['success' => false, 'error' => 'Contest ID required'], 400);
        }
        
        $photo = DB::table('photos')->where('id', $id)->first();
        if (!$photo) {
            return response()->json(['success' => false, 'error' => 'Photo not found'], 404);
        }
        
        $contest = DB::table('contests')->where('id', $contestId)->first();
        if (!$contest) {
            return response()->json(['success' => false, 'error' => 'Contest not found'], 404);
        }
        
        // Check if already entered
        $existing = DB::table('contest_entries')
            ->where('photo_id', $id)
            ->where('contest_id', $contestId)
            ->first();
        
        if ($existing) {
            return response()->json(['success' => false, 'error' => 'Photo already entered in this contest'], 400);
        }
        
        // Verify user owns the photo or is admin
        if ($photo->uploader_id !== $userId && auth()->user()->user_type !== 'admin') {
            return response()->json(['success' => false, 'error' => 'You can only enter your own photos'], 403);
        }
        
        DB::table('contest_entries')->insert([
            'photo_id' => $id,
            'contest_id' => $contestId,
            'submitted_by' => $userId,
            'created_at' => now(),
        ]);
        
        return response()->json(['success' => true]);
    })->name('api.photo.enter-contest');
})->name('api.photo');

// Contest vote toggle
Route::post('/contest-vote', function (\Illuminate\Http\Request $request) {
    if (!auth()->check()) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
    }

    $entryId = (int) $request->input('entry_id', 0);
    if (!$entryId) {
        return response()->json(['success' => false, 'error' => 'Entry ID required'], 400);
    }

    $userId = auth()->id();
    $entry = DB::table('contest_entries')->where('id', $entryId)->first();
    if (!$entry) {
        return response()->json(['success' => false, 'error' => 'Entry not found'], 404);
    }

    // Check if contest is still active
    $contest = DB::table('contests')->where('id', $entry->contest_id)->first();
    if (!$contest || $contest->status === 'closed') {
        return response()->json(['success' => false, 'error' => 'Contest is closed'], 400);
    }

    $existing = DB::table('votes')
        ->where('photo_id', $entry->photo_id)
        ->where('user_id', $userId)
        ->first();

    if ($existing) {
        DB::table('votes')->where('id', $existing->id)->delete();
        DB::table('contest_entries')->where('id', $entryId)->decrement('votes');
        $voted = false;
    } else {
        DB::table('votes')->insert([
            'photo_id' => $entry->photo_id,
            'user_id' => $userId,
            'created_at' => now(),
        ]);
        DB::table('contest_entries')->where('id', $entryId)->increment('votes');
        $voted = true;
    }

    $updatedEntry = DB::table('contest_entries')->where('id', $entryId)->first();

    return response()->json([
        'success' => true,
        'voted' => $voted,
        'votes' => $updatedEntry->votes ?? 0,
    ]);
})->name('api.contest.vote');
