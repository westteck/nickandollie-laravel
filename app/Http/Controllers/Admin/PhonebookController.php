<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PhonebookController extends Controller
{
    public function index(Request $request): View
    {
        $contacts = DB::table('address_book')
            ->orderByDesc('id')
            ->get();

        $editing = null;
        if ($request->has('edit')) {
            $editing = DB::table('address_book')->where('id', $request->get('edit'))->first();
        }

        return view('admin.phonebook', compact('contacts', 'editing'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'entry_name' => ['required', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'family_connection' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'show_in_phonebook' => ['nullable', 'in:1'],
        ]);

        $data['show_in_phonebook'] = $data['show_in_phonebook'] ?? 0;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('address_book')->insert($data);

        return redirect()->route('admin.phonebook')->with('status', 'Contact added.');
    }

    public function update(Request $request, int $id)
    {
        $contact = DB::table('address_book')->where('id', $id)->first();
        if (!$contact) {
            return redirect()->route('admin.phonebook')->with('error', 'Contact not found.');
        }

        $data = $request->validate([
            'entry_name' => ['required', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'family_connection' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'show_in_phonebook' => ['nullable', 'in:1'],
        ]);

        $data['show_in_phonebook'] = $data['show_in_phonebook'] ?? 0;
        $data['updated_at'] = now();

        DB::table('address_book')->where('id', $id)->update($data);

        return redirect()->route('admin.phonebook')->with('status', 'Contact updated.');
    }

    public function destroy(int $id)
    {
        $contact = DB::table('address_book')->where('id', $id)->first();
        if (!$contact) {
            return redirect()->route('admin.phonebook')->with('error', 'Contact not found.');
        }

        DB::table('address_book')->where('id', $id)->delete();

        return redirect()->route('admin.phonebook')->with('status', 'Contact deleted.');
    }
}
