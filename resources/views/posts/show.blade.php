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
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">
                            🗑️ Delete
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="text-muted mb-3">
                <span>👤 By <a href="{{ route('users.posts', $post->user) }}" class="text-decoration-none fw-bold">{{ $post->user->name }}</a></span>
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
                        <a href="{{ route('tags.posts', $tag) }}" class="badge bg-info ms-1 text-decoration-none">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    
    <div class="mt-4">
        <h3 class="mb-3">💬 Comments ({{ $post->comments->count() }})</h3>
        
        @forelse($post->comments as $comment)
            <div class="bg-white p-3 rounded shadow-sm mb-2">
                <div class="d-flex justify-content-between">
                    <strong>{{ $comment->user->name }}</strong>
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-0 mt-2">{{ $comment->body }}</p>
            </div>
        @empty
            <p class="text-muted fst-italic">No comments yet. Be the first to comment!</p>
        @endforelse
        
        <div class="mt-4 bg-light p-4 rounded">
            <h5 class="mb-3">💭 Add a Comment</h5>
            
            <form action="{{ route('comments.store', $post) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="body" class="form-label">Your Comment</label>
                    <textarea name="body" id="body" rows="3" class="form-control" placeholder="Write your comment here..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
            ← Back to All Posts
        </a>
    </div>
@endsection