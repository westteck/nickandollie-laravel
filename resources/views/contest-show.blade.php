@extends('layouts.app')

@section('title', $contest->title ?? 'Contest')

@section('content')
<div class="container-fluid px-2 py-3">
    <a href="{{ route('contest') }}" class="btn btn-light btn-sm mb-3">
        <i class="fas fa-arrow-left me-1"></i> All Contests
    </a>

    <div class="bg-white py-3 text-center border-bottom mb-3">
        <h1 class="h4 mb-2" style="color: var(--primary)">{{ $contest->title }}</h1>
        @if($contest->description)
            <p class="text-muted mb-1 px-3">{{ $contest->description }}</p>
        @endif
        <div class="d-flex justify-content-center gap-3 small text-muted">
            <span><i class="fas fa-images me-1"></i>{{ $entries->count() }} entries</span>
            @if($contest->prize)
                <span><i class="fas fa-gift me-1"></i>Prize: {{ $contest->prize }}</span>
            @endif
            @if($contest->start_date)
                <span><i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($contest->start_date)->format('M j, Y') }}</span>
            @endif
        </div>
        @if($contest->status === 'active')
            <span class="badge bg-success mt-2">Active</span>
        @elseif($contest->status === 'closed')
            <span class="badge bg-warning text-dark mt-2"><i class="fas fa-lock me-1"></i>Closed</span>
        @endif
    </div>

    @if($entries->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-images fa-3x text-muted mb-3"></i>
            <h2 class="h5 text-muted">No entries yet</h2>
            <p class="text-muted mb-4">Be the first to enter!</p>
            <a href="{{ route('gallery') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Enter Contest
            </a>
        </div>
    @else
        <div class="row row-cols-3 row-cols-md-4 row-cols-lg-5 g-2" id="entries-grid">
            @foreach($entries as $i => $entry)
            <div class="col contest-entry"
                 data-index="{{ $i }}"
                 data-entry-id="{{ $entry->id }}"
                 data-photo-id="{{ $entry->photo_id }}"
                 data-voted="0"
                 data-votes="{{ $entry->votes ?? 0 }}"
                 role="button" tabindex="0">
                <img src="/storage/thumbs/{{ $entry->filename }}"
                     alt="{{ $entry->caption ?? 'Entry' }}"
                     class="img-fluid rounded"
                     loading="lazy"
                     onerror="this.src='/storage/originals/{{ $entry->filename }}'">
                <div class="overlay">
                    <i class="fas fa-thumbs-up text-white mb-1"></i> {{ $entry->votes ?? 0 }}
                    <br><small class="text-white">{{ $entry->caption ?? 'Entry' }}</small>
                    @if($contest->status !== 'closed')
                        <br>
                        <button class="btn btn-sm btn-outline-light vote-btn mt-1" data-entry-id="{{ $entry->id }}" onclick="event.stopPropagation();">
                            <i class="far fa-thumbs-up"></i>
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif

    @if($contest->rules)
        <div class="card mt-4">
            <div class="card-header">
                <h2 class="h5 mb-0" style="color: var(--primary)">Contest Rules</h2>
            </div>
            <div class="card-body">
                {!! nl2br(e($contest->rules)) !!}
            </div>
        </div>
    @endif
</div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <button class="btn-close btn-close-white position-absolute top-0 end-0 m-3" id="lightbox-close"></button>
    <button class="lightbox-nav lightbox-prev" id="lightbox-prev"><i class="fas fa-chevron-left"></i></button>
    <button class="lightbox-nav lightbox-next" id="lightbox-next"><i class="fas fa-chevron-right"></i></button>
    <div class="text-center text-white px-4" style="max-width: 90vw;">
        <img id="lightbox-img" class="img-fluid mb-2" style="max-height: 60vh;">
        <p id="lightbox-caption" class="text-white fw-medium mb-1" style="font-size:1.1rem;"></p>
        <p id="lightbox-meta" class="small text-white-50 mb-2"></p>
        @if($contest->status !== 'closed')
        <button class="btn btn-outline-light" id="lightbox-vote">
            <i class="far fa-thumbs-up me-1"></i>Vote (<span id="vote-count">0</span>)
        </button>
        @endif
    </div>
</div>

<style>
.contest-entry {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    border-radius: 8px;
}
.contest-entry img {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
    transition: transform 0.2s;
}
.contest-entry:hover img {
    transform: scale(1.05);
}
.contest-entry .overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    padding: 8px;
    font-size: 0.75rem;
    opacity: 0;
    transition: opacity 0.2s;
}
.contest-entry:hover .overlay {
    opacity: 1;
}

/* Lightbox */
.lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.9);
    z-index: 1050;
    align-items: center;
    justify-content: center;
}
.lightbox.active {
    display: flex;
}
.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    font-size: 1.5rem;
    padding: 1rem;
    cursor: pointer;
    border-radius: 50%;
    z-index: 1051;
}
.lightbox-prev { left: 1rem; }
.lightbox-next { right: 1rem; }
.lightbox-nav:hover { background: rgba(255,255,255,0.3); }
</style>

@push('scripts')
<script>
(function() {
    var entries = [];
    var currentIndex = 0;
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    var contestStatus = '{{ $contest->status }}';
    var isClosed = contestStatus === 'closed';

    // Build entries array from DOM
    document.querySelectorAll('.contest-entry').forEach(function(el) {
        entries.push({
            entry_id: parseInt(el.dataset.entryId),
            photo_id: parseInt(el.dataset.photoId),
            photo_url: el.querySelector('img').src.replace('/thumbs/', '/print/'),
            thumb_url: el.querySelector('img').src,
            caption: el.querySelector('small')?.textContent || '',
            votes: parseInt(el.dataset.votes) || 0,
            voted: false
        });
    });

    // Open lightbox on entry click
    document.querySelectorAll('.contest-entry').forEach(function(el) {
        el.addEventListener('click', function(ev) {
            if (ev.target.closest('.vote-btn')) return;
            currentIndex = parseInt(this.dataset.index);
            openLightbox();
        });
    });

    function openLightbox() {
        var e = entries[currentIndex];
        document.getElementById('lightbox-img').src = e.photo_url;
        document.getElementById('lightbox-caption').textContent = e.caption;
        document.getElementById('vote-count').textContent = e.votes;
        var voteBtn = document.getElementById('lightbox-vote');
        if (voteBtn) {
            voteBtn.querySelector('i').className = e.voted ? 'fas fa-thumbs-up me-1' : 'far fa-thumbs-up me-1';
            voteBtn.style.display = isClosed ? 'none' : 'inline-block';
        }
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.getElementById('lightbox-close').addEventListener('click', closeLightbox);
    document.getElementById('lightbox-prev').addEventListener('click', function() {
        currentIndex = (currentIndex - 1 + entries.length) % entries.length;
        openLightbox();
    });
    document.getElementById('lightbox-next').addEventListener('click', function() {
        currentIndex = (currentIndex + 1) % entries.length;
        openLightbox();
    });
    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target === this) closeLightbox();
    });

    // Vote in lightbox
    document.getElementById('lightbox-vote')?.addEventListener('click', function() {
        var e = entries[currentIndex];
        e.voted = !e.voted;
        e.votes += e.voted ? 1 : -1;
        document.getElementById('vote-count').textContent = e.votes;
        this.querySelector('i').className = e.voted ? 'fas fa-thumbs-up me-1' : 'far fa-thumbs-up me-1';

        fetch('/api/contest-vote', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ entry_id: e.entry_id })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                e.votes = data.votes;
                e.voted = data.voted;
                document.getElementById('vote-count').textContent = data.votes;
            }
        })
        .catch(function() {});
    });

    // Vote buttons on grid
    document.querySelectorAll('.vote-btn').forEach(function(btn) {
        btn.addEventListener('click', function(ev) {
            ev.stopPropagation();
            var entryId = parseInt(this.dataset.entryId);
            var entry = entries.find(function(e) { return e.entry_id == entryId; });
            if (!entry) return;
            entry.voted = !entry.voted;
            entry.votes += entry.voted ? 1 : -1;
            this.querySelector('i').className = entry.voted ? 'fas fa-thumbs-up' : 'far fa-thumbs-up';

            fetch('/api/contest-vote', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ entry_id: entryId })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    entry.votes = data.votes;
                    entry.voted = data.voted;
                }
            })
            .catch(function() {});
        });
    });

    // Keyboard nav
    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('lightbox').classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') { currentIndex = (currentIndex - 1 + entries.length) % entries.length; openLightbox(); }
        if (e.key === 'ArrowRight') { currentIndex = (currentIndex + 1) % entries.length; openLightbox(); }
    });
})();
</script>
@endpush
@endsection
