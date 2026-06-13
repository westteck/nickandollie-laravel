<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PhonebookController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = $request->input('search', '');
        $group = $request->input('group', '');

        $query = DB::table('address_book')
            ->select('address_book.*', 'users.guest_name', 'users.connection', 'users.core_group')
            ->leftJoin('users', 'address_book.user_id', '=', 'users.id')
            ->where('address_book.show_in_phonebook', 1);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('address_book.entry_name', 'like', "%{$search}%")
                  ->orWhere('address_book.first_name', 'like', "%{$search}%")
                  ->orWhere('address_book.family_connection', 'like', "%{$search}%");
            });
        }

        if ($group) {
            $query->where('users.core_group', $group);
        }

        $entries = $query->orderBy('address_book.entry_name')->get();

        // Get unique groups for filter
        $groups = DB::table('address_book')
            ->select('users.core_group')
            ->leftJoin('users', 'address_book.user_id', '=', 'users.id')
            ->whereNotNull('users.core_group')
            ->distinct()
            ->pluck('users.core_group');

        return view('phonebook', [
            'entries' => $entries,
            'groups' => $groups,
            'search' => $search,
            'group' => $group,
        ]);
    }
}
