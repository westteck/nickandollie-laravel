<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ContestController extends Controller
{
    public function index(): View
    {
        $contests = DB::table('contests')
            ->leftJoin('contest_entries', function ($join) {
                $join->on('contests.id', '=', 'contest_entries.contest_id')
                     ->where('contest_entries.status', '=', 'approved');
            })
            ->select('contests.id', 'contests.title', 'contests.description', 'contests.icon', 'contests.status', 'contests.prize', 'contests.rules', 'contests.start_date', 'contests.end_date', 'contests.created_at', 'contests.updated_at', DB::raw('COUNT(contest_entries.id) as entry_count'))
            ->groupBy('contests.id', 'contests.title', 'contests.description', 'contests.icon', 'contests.status', 'contests.prize', 'contests.rules', 'contests.start_date', 'contests.end_date', 'contests.created_at', 'contests.updated_at')
            ->orderBy('contests.created_at', 'desc')
            ->get();

        return view('contest', compact('contests'));
    }

    public function show($id): View
    {
        $contest = DB::table('contests')->where('id', $id)->first();
        if (!$contest) {
            abort(404);
        }

        $entries = DB::table('contest_entries')
            ->join('photos', 'contest_entries.photo_id', '=', 'photos.id')
            ->where('contest_entries.contest_id', $id)
            ->where('contest_entries.status', 'approved')
            ->select('contest_entries.*', 'photos.filename', 'photos.caption')
            ->orderBy('contest_entries.votes', 'desc')
            ->get();

        return view('contest-show', compact('contest', 'entries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:draft,active,completed,cancelled,closed',
            'prize' => 'nullable|string|max:255',
            'rules' => 'nullable|string',
        ]);

        DB::table('contests')->insert([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon ?? 'fa-trophy',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status ?? 'draft',
            'prize' => $request->prize,
            'rules' => $request->rules,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('contests')->with('status', 'Contest created');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:draft,active,completed,cancelled,closed',
            'prize' => 'nullable|string|max:255',
            'rules' => 'nullable|string',
        ]);

        DB::table('contests')->where('id', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'prize' => $request->prize,
            'rules' => $request->rules,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('status', 'Contest updated');
    }

    public function destroy($id)
    {
        DB::table('contest_entries')->where('contest_id', $id)->delete();
        DB::table('contests')->where('id', $id)->delete();

        return redirect()->route('contests')->with('status', 'Contest deleted');
    }
}
