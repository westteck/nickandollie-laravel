<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CommentModerationController extends Controller
{
    /**
     * Display the comment moderation page.
     */
    public function index(): View
    {
        return view('admin.comments');
    }

    /**
     * List all comments (JSON).
     */
    public function list(): JsonResponse
    {
        $comments = DB::table('comments')
            ->select(
                'comments.*',
                'users.guest_name as user_name',
                'photos.caption as photo_caption',
                'photos.thumb_filename'
            )
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->leftJoin('photos', 'comments.photo_id', '=', 'photos.id')
            ->orderBy('comments.created_at', 'desc')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'photo_id' => $c->photo_id,
                    'user_id' => $c->user_id,
                    'user_name' => $c->user_name ?? 'Unknown',
                    'comment' => $c->comment,
                    'photo_caption' => $c->photo_caption ?? '',
                    'thumb_url' => $c->thumb_filename ? '/storage/thumbs/' . $c->thumb_filename : '',
                    'created_at' => $c->created_at,
                    'time' => $c->created_at ? \Carbon\Carbon::parse($c->created_at)->diffForHumans() : 'recently',
                ];
            });

        return response()->json(['success' => true, 'comments' => $comments]);
    }

    /**
     * Delete a comment (JSON).
     */
    public function destroy(int $id): JsonResponse
    {
        $comment = DB::table('comments')->where('id', $id)->first();
        if (!$comment) {
            return response()->json(['success' => false, 'error' => 'Comment not found'], 404);
        }

        DB::table('comments')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Comment deleted']);
    }

    /**
     * Bulk delete comments (JSON).
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $input = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        DB::table('comments')->whereIn('id', $input['ids'])->delete();

        return response()->json(['success' => true, 'message' => count($input['ids']) . ' comments deleted']);
    }
}
