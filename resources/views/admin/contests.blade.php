@extends('layouts.app')
@section('title', 'Admin Contests')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sec">Admin</p>
            <h1 class="text-3xl font-bold sm:text-4xl">Contests</h1>
        </div>
        <a href="{{ route('admin.contests') }}" class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary">
            + New Contest
        </a>
    </div>

    @if(session('status'))
        <div class="mb-4 rounded-md bg-sec/20 p-3 text-sm text-accent">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-50 p-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Contest List --}}
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="border-b px-4 py-3">
            <h2 class="font-semibold text-slate-800">All Contests</h2>
        </div>
        @if($contests->isEmpty())
            <div class="p-8 text-center text-body/70">No contests yet.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-slate-50 text-left text-body/80">
                            <th class="px-4 py-3 font-medium">Title</th>
                            <th class="px-4 py-3 font-medium">Icon</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Start</th>
                            <th class="px-4 py-3 font-medium">End</th>
                            <th class="px-4 py-3 font-medium">Prize</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($contests as $contest)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $contest->title }}</td>
                                <td class="px-4 py-3 text-body/80">
                                    @if($contest->icon)
                                        <i class="{{ $contest->icon }}"></i>
                                    @else
                                        <span class="text-body/60">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                        @if($contest->status === 'active') bg-green-100 text-green-700
                                        @elseif($contest->status === 'closed') bg-red-100 text-red-700
                                        @elseif($contest->status === 'draft') bg-slate-100 text-body/80
                                        @else bg-yellow-100 text-yellow-700 @endif">
                                        {{ ucfirst($contest->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-body/80">{{ $contest->start_date ? \Carbon\Carbon::parse($contest->start_date)->format('M j, Y') : '—' }}</td>
                                <td class="px-4 py-3 text-body/80">{{ $contest->end_date ? \Carbon\Carbon::parse($contest->end_date)->format('M j, Y') : '—' }}</td>
                                <td class="px-4 py-3 text-body/80">{{ $contest->prize ?: '—' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.contests', ['edit' => $contest->id]) }}"
                                           class="rounded px-2 py-1 text-xs font-medium text-indigo-600 hover:bg-indigo-50">Edit</a>
                                        <form method="POST" action="{{ route('admin.contests.destroy', $contest->id) }}"
                                              onsubmit="return confirm('Delete this contest?');">
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
            <h2 class="font-semibold text-slate-800">{{ $isEditing ? 'Edit Contest' : 'Create Contest' }}</h2>
        </div>
        <form method="POST" action="{{ $isEditing ? route('admin.contests.update', $editing->id) : route('admin.contests.store') }}"
              class="space-y-4 p-6">
            @csrf
            @if($isEditing)
                @method('PUT')
            @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $isEditing ? $editing->title : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]"
                           required>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">Icon (e.g. fa-trophy)</label>
                    <input type="text" name="icon" value="{{ old('icon', $isEditing ? $editing->icon : '') }}"
                           placeholder="fa-trophy"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">Status</label>
                    <select name="status"
                            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                        @foreach(['draft', 'active', 'inactive', 'closed'] as $status)
                            <option value="{{ $status }}"
                                {{ (old('status', $isEditing ? $editing->status : 'draft') === $status) ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">Prize</label>
                    <input type="text" name="prize" value="{{ old('prize', $isEditing ? $editing->prize : '') }}"
                           placeholder="e.g. Gift card"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">Start Date</label>
                    <input type="date" name="start_date"
                           value="{{ old('start_date', $isEditing && $editing->start_date ? \Carbon\Carbon::parse($editing->start_date)->format('Y-m-d') : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-body">End Date</label>
                    <input type="date" name="end_date"
                           value="{{ old('end_date', $isEditing && $editing->end_date ? \Carbon\Carbon::parse($editing->end_date)->format('Y-m-d') : '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-body">Description</label>
                <textarea name="description" rows="3"
                          class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">{{ old('description', $isEditing ? $editing->description : '') }}</textarea>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-body">Rules</label>
                <textarea name="rules" rows="3"
                          class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#171d33] focus:outline-none focus:ring-1 focus:ring-[#171d33]">{{ old('rules', $isEditing ? $editing->rules : '') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-md bg-primary px-5 py-2 text-sm font-medium text-white hover:bg-primary">
                    {{ $isEditing ? 'Update Contest' : 'Create Contest' }}
                </button>
                @if($isEditing)
                    <a href="{{ route('admin.contests') }}"
                       class="rounded-md border border-slate-300 px-5 py-2 text-sm font-medium text-body/80 hover:bg-slate-50">
                        Cancel
                    </a>
                @endif
            </div>
        </form>
    </div>
</section>
@endsection
