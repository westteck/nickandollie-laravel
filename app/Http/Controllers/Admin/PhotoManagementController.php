<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PhotoManagementController extends Controller
{
    /**
     * Display the photo management page.
     */
    public function index(): View
    {
        return view('admin.photos');
    }

    /**
     * List all photos (JSON).
     */
    public function list(): JsonResponse
    {
        $photos = DB::table('photos')
            ->select(
                'photos.*',
                'users.guest_name as uploader_name'
            )
            ->leftJoin('users', 'photos.uploader_id', '=', 'users.id')
            ->orderBy('photos.uploaded_at', 'desc')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'filename' => $p->filename,
                    'thumb_filename' => $p->thumb_filename,
                    'print_filename' => $p->print_filename,
                    'caption' => $p->caption ?? '',
                    'uploader' => $p->uploader_name ?? 'Unknown',
                    'uploader_id' => $p->uploader_id,
                    'likes' => (int) $p->likes,
                    'uploaded_at' => $p->uploaded_at,
                    'thumb_url' => '/storage/thumbs/' . $p->thumb_filename,
                    'photo_url' => '/storage/print/' . $p->print_filename,
                ];
            });

        return response()->json(['success' => true, 'photos' => $photos]);
    }

    /**
     * Update photo caption (JSON).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $photo = DB::table('photos')->where('id', $id)->first();
        if (!$photo) {
            return response()->json(['success' => false, 'error' => 'Photo not found'], 404);
        }

        $input = $request->validate([
            'caption' => ['nullable', 'string', 'max:500'],
        ]);

        DB::table('photos')
            ->where('id', $id)
            ->update([
                'caption' => $input['caption'] ?? '',
                'updated_at' => now(),
            ]);

        return response()->json(['success' => true, 'message' => 'Photo updated']);
    }

    /**
     * Delete a photo (JSON).
     */
    public function destroy(int $id): JsonResponse
    {
        $photo = DB::table('photos')->where('id', $id)->first();
        if (!$photo) {
            return response()->json(['success' => false, 'error' => 'Photo not found'], 404);
        }

        // Delete files
        @unlink(storage_path('app/public/originals/' . $photo->filename));
        @unlink(storage_path('app/public/thumbs/' . $photo->thumb_filename));
        @unlink(storage_path('app/public/print/' . $photo->print_filename));

        // Clean up related records
        DB::table('comments')->where('photo_id', $id)->delete();
        DB::table('favorites')->where('photo_id', $id)->delete();
        DB::table('ratings')->where('photo_id', $id)->delete();
        DB::table('votes')->where('photo_id', $id)->delete();
        DB::table('contest_entries')->where('photo_id', $id)->delete();
        DB::table('photos')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Photo deleted']);
    }
}
