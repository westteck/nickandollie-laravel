<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContestController extends Controller
{
    public function index(Request $request): View
    {
        $contests = DB::table('contests')
            ->orderByDesc('id')
            ->get();

        $editing = null;
        if ($request->has('edit')) {
            $editing = DB::table('contests')->where('id', $request->get('edit'))->first();
        }

        return view('admin.contests', compact('contests', 'editing'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['active', 'inactive', 'draft', 'closed'])],
            'prize' => ['nullable', 'string'],
            'rules' => ['nullable', 'string'],
        ]);

        $data['status'] = $data['status'] ?? 'draft';
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('contests')->insert($data);

        return redirect()->route('admin.contests')->with('status', 'Contest created.');
    }

    public function update(Request $request, int $id)
    {
        $contest = DB::table('contests')->where('id', $id)->first();
        if (!$contest) {
            return redirect()->route('admin.contests')->with('error', 'Contest not found.');
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['active', 'inactive', 'draft', 'closed'])],
            'prize' => ['nullable', 'string'],
            'rules' => ['nullable', 'string'],
        ]);

        $data['updated_at'] = now();
        DB::table('contests')->where('id', $id)->update($data);

        return redirect()->route('admin.contests')->with('status', 'Contest updated.');
    }

    public function destroy(int $id)
    {
        $contest = DB::table('contests')->where('id', $id)->first();
        if (!$contest) {
            return redirect()->route('admin.contests')->with('error', 'Contest not found.');
        }

        DB::table('contests')->where('id', $id)->delete();

        return redirect()->route('admin.contests')->with('status', 'Contest deleted.');
    }
}
