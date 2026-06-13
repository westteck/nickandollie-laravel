<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 30);
        $offset = ($page - 1) * $limit;

        // Total count
        $total = DB::table('photos')->count();

        // Fetch photos with uploader info
        $photos = DB::table('photos')
            ->select('photos.id', 'photos.filename', 'photos.thumb_filename', 'photos.print_filename', 'photos.caption', 'photos.likes', 'photos.uploaded_at', 'users.guest_name as uploader')
            ->leftJoin('users', 'photos.uploader_id', '=', 'users.id')
            ->orderBy('photos.uploaded_at', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'thumb_url' => '/storage/thumbs/' . $p->thumb_filename,
                    'photo_url' => '/storage/print/' . $p->print_filename,
                    'original_url' => '/storage/originals/' . $p->filename,
                    'caption' => $p->caption ?? '',
                    'uploader' => $p->uploader ?? 'Guest',
                    'likes' => (int) $p->likes,
                    'uploaded_at' => $p->uploaded_at,
                ];
            });

        return view('wedding.gallery', [
            'photos' => $photos,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($total / $limit),
        ]);
    }
}
