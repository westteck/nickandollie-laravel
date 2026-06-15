@extends('layouts.app')
@section('title', 'Admin Phonebook')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sec">Admin</p>
            <h1 class="text-3xl font-bold sm:text-4xl">Phonebook</h1>
        </div>
        <a href="{{ route('admin.phonebook') }}" class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary">
            + New Contact
        </a>
    </div>

    @if(session('status'))
        <div class="mb-4 rounded-md bg-sec/20 p-3 text-sm text-accent">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Contact List --}}
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="border-b px-4 py-3">
            <h2 class="font-semibold text-slate-800">All Contacts</h2>
        </div>
        @if($contacts->isEmpty())
            <div class="p-8 text-center text-body/70">No contacts yet.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-slate-50 text-left text-body/80">
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Connection</th>
                            <th class="px-4 py-3 font-medium">Phone</th>
                            <th class="px-4 py-3 font-medium">Email</th>
                            <th class="px-4 py-3 font-medium">City</th>
                            <th class="px-4 py-3 font-medium">Public</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($contacts as $contact)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-800">{{ $contact->entry_name }}</div>
                                    @if($contact->first_name)
                                        <div class="text-xs text-body/70">{{ $contact->first_name }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-body/80">{{ $contact->family_connection ?: '—' }}</td>
                                <td class="px-4 py-3 text-body/80">
                                    @if($contact->phone)
                                        <div>{{ $contact->phone }}</div>
                                    @endif
                                    @if($contact->mobile)
                                        <div class="text-xs text-body/60">{{ $contact->mobile }} (mobile)</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-body/80">{{ $contact->email ?: '—' }}</td>
                                <td class="px-4 py-3 text-body/80">
                                    @if($contact->city || $contact->state)
                                        {{ $contact->city }}{{ $contact->city && $contact->state ? ', ' : '' }}{{ $contact->state }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($contact->show_in_phonebook)
                                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Yes</span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-body/70">No</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.phonebook', ['edit' => $contact->id]) }}"
                                           class="rounded px-2 py-1 text-xs font-medium text-indigo-600 hover:bg-indigo-50">Edit</a>
                                        <form method="POST" action="{{ route('admin.phonebook.destroy', $contact->id) }}"
                                              onsubmit="return confirm('Delete this contact?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded px-2 py-1 text-xs font-medium text-red-400 hover:bg-red-50">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Create / Edit Form --}}
    @php $isEditing = $editing !== null; @endphp
    <div class="mt-8 rounded-lg border bg-white shadow-sm">
        <div class="border-b px-4 py-3">
            <h2 class="font-semibold text-slate-800">{{ $isEditing ? 'Edit Contact' : 'Add Contact' }}</h2>
        </div>
        <form method="POST" action="{{ $isEditing ? route('admin.phonebook.update', $editing->id) : route('admin.phonebook.store') }}"
              class="space-y-4 p-6">
            @csrf
            @if($isEditing)
                @method('PUT')
            @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">Entry Name *</label>
                    <input type="text" name="entry_name"
                           value="{{ old('entry_name', $isEditing ? $editing->entry_name : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]"
                           required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">First Name</label>
                    <input type="text" name="first_name"
                           value="{{ old('first_name', $isEditing ? $editing->first_name : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">Family Connection</label>
                    <input type="text" name="family_connection"
                           value="{{ old('family_connection', $isEditing ? $editing->family_connection : '') }}"
                           placeholder="e.g. Friend, Family, Wedding Party"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">Email</label>
                    <input type="email" name="email"
                           value="{{ old('email', $isEditing ? $editing->email : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">Phone</label>
                    <input type="text" name="phone"
                           value="{{ old('phone', $isEditing ? $editing->phone : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">Mobile</label>
                    <input type="text" name="mobile"
                           value="{{ old('mobile', $isEditing ? $editing->mobile : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">Street Address</label>
                    <input type="text" name="address"
                           value="{{ old('address', $isEditing ? $editing->address : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">City</label>
                    <input type="text" name="city"
                           value="{{ old('city', $isEditing ? $editing->city : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">State</label>
                    <input type="text" name="state"
                           value="{{ old('state', $isEditing ? $editing->state : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">ZIP</label>
                    <input type="text" name="zip"
                           value="{{ old('zip', $isEditing ? $editing->zip : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-body">Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">{{ old('notes', $isEditing ? $editing->notes : '') }}</textarea>
            </div>

            <div>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="show_in_phonebook" value="1"
                           {{ old('show_in_phonebook', $isEditing ? $editing->show_in_phonebook : true) ? 'checked' : '' }}
                           class="rounded border-slate-300 text-sec focus:ring-[#171d33]">
                    <span class="text-sm font-medium text-body">Show in public phonebook</span>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-md bg-primary px-5 py-2 text-sm font-medium text-white hover:bg-primary">
                    {{ $isEditing ? 'Update Contact' : 'Add Contact' }}
                </button>
                @if($isEditing)
                    <a href="{{ route('admin.phonebook') }}"
                       class="rounded-md border border-slate-300 px-5 py-2 text-sm font-medium text-body/80 hover:bg-slate-50">
                        Cancel
                    </a>
                @endif
            </div>
        </form>
    </div>
</section>
@endsection
