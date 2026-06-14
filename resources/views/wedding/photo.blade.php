@extends('layouts.app')

@section('title', 'Photo - ' . ($photo->caption ?? 'Wedding Photo'))
@section('meta_description', 'View this wedding photo from Nick & Ollie Fortune.')

@section('content')
<div class="container py-4">
    <a href="{{ route('gallery') }}" class="btn btn-light btn-sm mb-3">
        <i class="fas fa-arrow-left me-1"></i> Back to Gallery
    </a>

    {{-- Contest Banner (hidden by default) --}}
    <div id="contest-banner" class="d-none alert alert-info mb-3"></div>

    {{-- Photo Card --}}
    <div class="card mb-3">
        <img id="photo-img" src="/storage/print/{{ $photo->print_filename }}" 
             class="card-img-top" 
             alt="{{ $photo->caption ?? 'Wedding photo' }}"
             style="max-height: 70vh; object-fit: contain;">
        <div class="card-body">
            <p id="photo-caption" class="card-text fw-medium mb-1">{{ $photo->caption ?? '' }}</p>
            <p id="photo-meta" class="text-muted small mb-3">
                Uploaded by {{ $photo->uploader_name ?? 'Guest' }}
                • {{ $photo->uploaded_at ? \Carbon\Carbon::parse($photo->uploaded_at)->toFormattedDateString() : 'recently' }}
            </p>

            {{-- Action Buttons --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                {{-- Like Button --}}
                <button class="btn btn-outline-danger btn-sm @guest disabled @endguest"
                        id="btn-like"
                        @guest disabled title="Login to like" @endguest>
                    <i class="@if($userLiked) fas @else far @endif fa-heart me-1"></i>
                    <span id="like-label">@if($userLiked) Liked @else Like @endif</span>
                </button>

                {{-- Favorite Button --}}
                <button class="btn btn-outline-danger btn-sm @guest disabled @endguest"
                        id="btn-favorite"
                        @guest disabled title="Login to favorite" @endguest>
                    <i class="@if($userFavorited) fas @else far @endif fa-heart me-1"></i>
                    <span id="fav-label">@if($userFavorited) Favorited @else Favorite @endif</span>
                </button>

                {{-- Contest Entry Dropdown --}}
                @if(auth()->check() && count($contests) > 0)
                <select class="form-select form-select-sm" style="width: auto; max-width: 220px;" id="contest-select" aria-label="Select contest">
                    <option value="">Enter in Contest...</option>
                    @foreach($contests as $contest)
                        <option value="{{ $contest->id }}"
                                {{ in_array($contest->id, $enteredContests) ? 'selected' : '' }}>
                            {{ $contest->title }}
                            @if($contest->end_date)
                                (ends {{ \Carbon\Carbon::parse($contest->end_date)->toFormattedDateString() }})
                            @endif
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-sm" id="btn-enter">Enter</button>
                @endif
            </div>

            {{-- Star Rating --}}
            <div class="mb-3">
                <label class="form-label small">Your Rating</label>
                <div class="star-rating" id="star-rating" role="radiogroup" aria-label="Rate this photo">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="{{ $i <= $userRating ? 'fas' : 'far' }} fa-star"
                           data-rating="{{ $i }}"
                           tabindex="0"
                           role="radio"
                           aria-label="{{ $i }} star{{ $i > 1 ? 's' : '' }}"></i>
                    @endfor
                </div>
                <small id="rating-feedback" class="text-muted"></small>
            </div>

            {{-- Likes Count --}}
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-heart text-danger"></i>
                <span id="photo-likes">{{ $likes }}</span> likes
            </div>
        </div>
    </div>

    {{-- Comments Section --}}
    <h5 class="mb-3">Comments</h5>

    @auth
    <div class="mb-3" id="comment-form">
        <textarea class="form-control" id="comment-input" rows="2" placeholder="Add a comment..."></textarea>
        <button class="btn btn-primary btn-sm mt-2" id="btn-comment">Post Comment</button>
    </div>
    @endauth

    <div id="login-prompt" class="mb-3 text-muted small" @auth style="display:none" @endauth>
        <a href="{{ route('login') }}">Login</a> to post comments.
    </div>

    <div id="comments-list">
        @forelse($comments as $comment)
            <div class="card mb-2 comment-card">
                <div class="card-body py-2">
                    <strong>{{ $comment['user'] }}</strong>
                    <span class="text-muted">• {{ $comment['time'] }}</span>
                    <p class="mb-0 mt-1">{{ $comment['text'] }}</p>
                </div>
            </div>
        @empty
            <p class="text-muted small">No comments yet. Be the first!</p>
        @endforelse
    </div>
    <div id="comments-loading" class="text-muted small" style="display: none;">Loading comments...</div>
</div>
@endsection

@section('scripts')
<script>
(function() {
    var photoId = {{ $photo->id }};
    var userRating = {{ $userRating }};
    var isFavorited = {{ $userFavorited ? 'true' : 'false' }};
    var isLiked = {{ $userLiked ? 'true' : 'false' }};
    var likesCount = {{ $likes }};
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // --- Like Toggle ---
    document.getElementById('btn-like')?.addEventListener('click', function() {
        fetch('/api/photo/' + photoId + '/like', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                isLiked = data.liked;
                likesCount = data.likes;
                updateLikeButton();
            }
        })
        .catch(function() {});
    });

    function updateLikeButton() {
        var btn = document.getElementById('btn-like');
        var icon = btn.querySelector('i');
        var label = document.getElementById('like-label');
        if (isLiked) {
            icon.className = 'fas fa-heart me-1';
            label.textContent = 'Liked';
        } else {
            icon.className = 'far fa-heart me-1';
            label.textContent = 'Like';
        }
        document.getElementById('photo-likes').textContent = likesCount;
    }

    // --- Favorite Toggle ---
    document.getElementById('btn-favorite')?.addEventListener('click', function() {
        fetch('/api/photo/' + photoId + '/favorite', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                isFavorited = data.favorited;
                updateFavoriteButton();
            }
        })
        .catch(function() {});
    });

    function updateFavoriteButton() {
        var btn = document.getElementById('btn-favorite');
        var icon = btn.querySelector('i');
        var label = document.getElementById('fav-label');
        if (isFavorited) {
            icon.className = 'fas fa-heart me-1';
            label.textContent = 'Favorited';
        } else {
            icon.className = 'far fa-heart me-1';
            label.textContent = 'Favorite';
        }
    }

    // --- Star Rating ---
    function updateStars(rating) {
        userRating = rating;
        document.querySelectorAll('#star-rating i').forEach(function(star, i) {
            star.className = (i < rating ? 'fas' : 'far') + ' fa-star';
        });
        var labels = ['', 'Terrible', 'Poor', 'Average', 'Good', 'Excellent'];
        document.getElementById('rating-feedback').textContent =
            rating ? 'You rated: ' + labels[rating] : '';
    }

    document.querySelectorAll('#star-rating i').forEach(function(star) {
        star.addEventListener('click', function() {
            var r = parseInt(this.dataset.rating);
            updateStars(r);
            submitRating(r);
        });
        star.addEventListener('mouseenter', function() {
            updateStars(parseInt(this.dataset.rating));
        });
        document.getElementById('star-rating').addEventListener('mouseleave', function() {
            updateStars(userRating);
        });
    });

    function submitRating(rating) {
        fetch('/api/photo/' + photoId + '/rate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ rating: rating })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success && data.average_rating) {
                document.getElementById('rating-feedback').textContent +=
                    ' (Avg: ' + data.average_rating + ' from ' + data.rating_count + ' votes)';
            }
        })
        .catch(function() {});
    }

    // --- Contest Entry ---
    document.getElementById('btn-enter')?.addEventListener('click', function() {
        var sel = document.getElementById('contest-select');
        var contestId = sel.value;
        if (!contestId) {
            alert('Please select a contest first.');
            return;
        }
        fetch('/api/photo/' + photoId + '/enter-contest', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ contest_id: contestId })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                alert('Photo entered in contest successfully!');
            } else {
                alert(data.error || 'Failed to enter contest.');
            }
        })
        .catch(function() { alert('Failed to enter contest.'); });
    });

    // --- Comments ---
    document.getElementById('btn-comment')?.addEventListener('click', function() {
        var input = document.getElementById('comment-input');
        var text = input.value.trim();
        if (!text) return;

        fetch('/api/photo/' + photoId + '/comment', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ comment: text })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                input.value = '';
                loadComments();
            }
        })
        .catch(function() {});
    });

    function loadComments() {
        document.getElementById('comments-loading').style.display = 'block';
        fetch('/api/photo/' + photoId + '/comments', {
            headers: { 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            document.getElementById('comments-loading').style.display = 'none';
            if (data.success) {
                renderComments(data.comments || []);
            }
        })
        .catch(function() {
            document.getElementById('comments-loading').style.display = 'none';
        });
    }

    function renderComments(comments) {
        var list = document.getElementById('comments-list');
        if (!comments.length) {
            list.innerHTML = '<p class="text-muted small">No comments yet. Be the first!</p>';
            return;
        }
        list.innerHTML = comments.map(function(c) {
            return '<div class="card mb-2 comment-card">' +
                '<div class="card-body py-2">' +
                '<strong>' + escapeHtml(c.user) + '</strong> ' +
                '<span class="text-muted">• ' + c.time + '</span>' +
                '<p class="mb-0 mt-1">' + escapeHtml(c.text) + '</p>' +
                '</div></div>';
        }).join('');
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
})();
</script>
@endsection

<style>
.star-rating {
    display: inline-flex;
    gap: 4px;
    cursor: pointer;
}
.star-rating i {
    color: #d4c4b0;
    font-size: 1.25rem;
    transition: color 0.2s;
}
.star-rating i.fas {
    color: #f5a623;
}
.star-rating i:hover {
    color: #f5a623;
}
.comment-card {
    border-radius: 0.5rem;
}
</style>
