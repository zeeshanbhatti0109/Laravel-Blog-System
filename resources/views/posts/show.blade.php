@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h2 class="mb-0">{{ $post->title }}</h2>

            <div>
                <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-warning">
                    ✏️ Edit
                </a>

                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('Are you sure you want to delete this post?')">
                        🗑️ Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- FIXED: Changed route('users.posts') to direct URL with slug -->
        <div class="text-muted mb-3">
            <span>👤 By <a href="/author/{{ $post->user->slug }}/posts" class="text-decoration-none fw-bold">{{
                    $post->user->name }}</a></span>
            <span class="mx-2">•</span>
            <span>📅 {{ $post->created_at->format('F j, Y') }}</span>
        </div>

        <div class="mb-4">
            <p class="lead">{{ nl2br(e($post->body)) }}</p>
        </div>

        @if($post->tags->count() > 0)
        <div class="mb-4">
            <strong class="fw-bold">Tags:</strong>
            @foreach($post->tags as $tag)
            <a href="{{ route('tags.posts', $tag) }}" class="badge bg-info ms-1 text-decoration-none">#{{ $tag->name
                }}</a>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="comments-section mt-4">
    <h3 class="mb-3">
        💬 Comments ({{ $post->comments->whereNull('parent_id')->count() }})
    </h3>

    @forelse($post->comments->whereNull('parent_id') as $comment)
    @include('comments.comment', ['comment' => $comment, 'level' => 0])
    @empty
    <p class="text-muted fst-italic">No comments yet. Be the first to comment!</p>
    @endforelse

    <div class="mt-4 bg-light p-4 rounded">
        <h5 class="mb-3">💭 Add a Comment</h5>

        <form action="{{ route('comments.store', $post) }}" method="POST">
            @csrf
            <textarea name="body" rows="3" class="form-control" placeholder="Write your comment..."></textarea>
            <button type="submit" class="btn btn-primary mt-2">Post Comment</button>
        </form>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
        ← Back to All Posts
    </a>
</div>
@endsection