<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PhonebookController extends Controller
{
    /**
     * Return all public phonebook entries (for JS consumption on legacy pages).
     * Mirrors the old api/phonebook-list.php logic.
     */
    public function __invoke(): JsonResponse
    {
        $entries = DB::table('address_book')
            ->select(
                'users.id as user_id',
                'address_book.entry_name',
                'address_book.first_name',
                DB::raw('COALESCE(NULLIF(address_book.family_connection,""), users.specific_relationship) as family_connection'),
                'address_book.address',
                'address_book.city',
                'address_book.state',
                'address_book.zip',
                'address_book.email',
                'address_book.phone',
                'address_book.mobile'
            )
            ->join('users', 'address_book.user_id', '=', 'users.id')
            ->whereIn('users.user_type', ['user', 'admin', 'partner'])
            ->whereNotNull('address_book.entry_name')
            ->where('address_book.entry_name', '!=', '')
            ->where('address_book.show_in_phonebook', 1)
            ->orderBy('address_book.entry_name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'entries' => $entries,
        ]);
    }
}
