<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'users'    => DB::table('users')->count(),
            'photos'   => DB::table('photos')->count(),
            'comments' => DB::table('comments')->count(),
            'votes'    => DB::table('votes')->count(),
            'contests' => DB::table('contests')->where('status', 'active')->count(),
        ];

        // Recent uploads
        $recentPhotos = DB::table('photos')
            ->select('photos.id', 'photos.thumb_filename', 'photos.uploaded_at', 'users.guest_name as uploader')
            ->leftJoin('users', 'photos.uploader_id', '=', 'users.id')
            ->orderBy('photos.uploaded_at', 'desc')
            ->limit(8)
            ->get();

        // Recent registrations
        $recentUsers = DB::table('users')
            ->select('id', 'guest_name', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Contest summary
        $contests = DB::table('contests')
            ->leftJoin('contest_entries', 'contests.id', '=', 'contest_entries.contest_id')
            ->select('contests.id', 'contests.title', 'contests.status', DB::raw('COUNT(contest_entries.id) as entry_count'))
            ->groupBy('contests.id', 'contests.title', 'contests.status')
            ->orderBy('contests.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPhotos', 'recentUsers', 'contests'));
    }
}
