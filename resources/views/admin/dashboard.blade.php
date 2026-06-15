@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sec">Admin</p>
        <h1 class="text-3xl font-bold sm:text-4xl">Dashboard</h1>
        <p class="max-w-2xl text-body">Overview of wedding site activity and quick admin actions.</p>
    </div>

    {{-- Stats Row --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-2xl glass-panel p-5 text-center">
            <p class="text-3xl font-bold text-sec">{{ $stats['users'] }}</p>
            <p class="text-xs uppercase tracking-widest text-body/60 mt-1">Guests</p>
        </div>
        <div class="rounded-2xl glass-panel p-5 text-center">
            <p class="text-3xl font-bold text-sec">{{ $stats['photos'] }}</p>
            <p class="text-xs uppercase tracking-widest text-body/60 mt-1">Photos</p>
        </div>
        <div class="rounded-2xl glass-panel p-5 text-center">
            <p class="text-3xl font-bold text-sec">{{ $stats['comments'] }}</p>
            <p class="text-xs uppercase tracking-widest text-body/60 mt-1">Comments</p>
        </div>
        <div class="rounded-2xl glass-panel p-5 text-center">
            <p class="text-3xl font-bold text-sec">{{ $stats['votes'] }}</p>
            <p class="text-xs uppercase tracking-widest text-body/60 mt-1">Votes</p>
        </div>
        <div class="rounded-2xl glass-panel p-5 text-center">
            <p class="text-3xl font-bold text-sec">{{ $stats['contests'] }}</p>
            <p class="text-xs uppercase tracking-widest text-body/60 mt-1">Active Contests</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Recent Uploads --}}
        <div class="rounded-2xl glass-panel overflow-hidden">
            <div class="border-b border-sec/20 px-5 py-3 flex items-center justify-between">
                <h2 class="font-semibold text-slate-800">Recent Uploads</h2>
                <a href="{{ route('gallery') }}" class="text-xs text-sec hover:underline">View Gallery</a>
            </div>
            <div class="p-4">
                @if($recentPhotos->isEmpty())
                    <p class="text-sm text-body/60 text-center py-4">No uploads yet.</p>
                @else
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($recentPhotos as $photo)
                            <a href="{{ route('photo.show', $photo->id) }}" class="aspect-square overflow-hidden rounded-lg">
                                <img src="/storage/thumbs/{{ $photo->thumb_filename }}"
                                     alt="Photo"
                                     class="h-full w-full object-cover hover:scale-105 transition-transform"
                                     onerror="this.src='/storage/originals/{{ $photo->thumb_filename }}'">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Registrations --}}
        <div class="rounded-2xl glass-panel overflow-hidden">
            <div class="border-b border-sec/20 px-5 py-3">
                <h2 class="font-semibold text-slate-800">Recent Registrations</h2>
            </div>
            <div class="p-4">
                @if($recentUsers->isEmpty())
                    <p class="text-sm text-body/60 text-center py-4">No registrations yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($recentUsers as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-sec/20 flex items-center justify-center text-xs font-bold text-sec">
                                        {{ strtoupper(substr($user->guest_name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-800">{{ $user->guest_name }}</span>
                                </div>
                                <span class="text-xs text-body/60">{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Contest Summary --}}
    <div class="rounded-2xl glass-panel overflow-hidden">
        <div class="border-b border-sec/20 px-5 py-3 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800">Contests</h2>
            <a href="{{ route('admin.contests') }}" class="text-xs text-sec hover:underline">Manage Contests</a>
        </div>
        @if($contests->isEmpty())
            <div class="p-8 text-center text-body/60">No contests yet. <a href="{{ route('admin.contests') }}" class="text-sec hover:underline">Create one</a>.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-slate-50 text-left text-body/80">
                            <th class="px-4 py-3 font-medium">Title</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Entries</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($contests as $contest)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $contest->title }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                        @if($contest->status === 'active') bg-green-100 text-green-700
                                        @elseif($contest->status === 'closed') bg-red-100 text-red-700
                                        @elseif($contest->status === 'draft') bg-slate-100 text-body/80
                                        @else bg-yellow-100 text-yellow-700 @endif">
                                        {{ ucfirst($contest->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-body/80">{{ $contest->entry_count }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('contest.show', $contest->id) }}" class="text-xs text-sec hover:underline mr-2">View</a>
                                    <a href="{{ route('admin.contests', ['edit' => $contest->id]) }}" class="text-xs text-indigo-600 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Quick Links --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('admin.themes') }}" class="rounded-2xl glass-panel p-5 hover:shadow-md transition-shadow">
            <i class="fas fa-palette text-2xl text-sec mb-2"></i>
            <h3 class="font-semibold text-slate-800">Themes</h3>
            <p class="text-xs text-body/60 mt-1">Customize colors and presets</p>
        </a>
        <a href="{{ route('admin.contests') }}" class="rounded-2xl glass-panel p-5 hover:shadow-md transition-shadow">
            <i class="fas fa-trophy text-2xl text-sec mb-2"></i>
            <h3 class="font-semibold text-slate-800">Contests</h3>
            <p class="text-xs text-body/60 mt-1">Manage photo contests</p>
        </a>
        <a href="{{ route('admin.phonebook') }}" class="rounded-2xl glass-panel p-5 hover:shadow-md transition-shadow">
            <i class="fas fa-address-book text-2xl text-sec mb-2"></i>
            <h3 class="font-semibold text-slate-800">Phonebook</h3>
            <p class="text-xs text-body/60 mt-1">Manage contact entries</p>
        </a>
        <a href="{{ route('admin.settings') }}" class="rounded-2xl glass-panel p-5 hover:shadow-md transition-shadow">
            <i class="fas fa-cog text-2xl text-sec mb-2"></i>
            <h3 class="font-semibold text-slate-800">Settings</h3>
            <p class="text-xs text-body/60 mt-1">Site title, hero, contact</p>
        </a>
    </div>
</section>
@endsection
