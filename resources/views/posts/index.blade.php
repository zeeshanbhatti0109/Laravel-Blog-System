@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6">📖 All Blog Posts</h1>
        <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill">
            ➕ Create New Post
        </a>
    </div>
    
    <div class="row g-4">
        @forelse($posts as $post)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 shadow-sm post-card">
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2">
                            👤 <a href="{{ route('users.posts', $post->user) }}" class="text-white text-decoration-none">{{ $post->user->name }}</a>
                        </span>
                        
                        <h5 class="card-title mb-3">{{ $post->title }}</h5>
                        
                        <p class="card-text text-muted mb-3">
                            {{ Str::limit($post->body, 100) }}
                        </p>
                        
                        @if($post->tags->count() > 0)
                            <div class="mb-3">
                                @foreach($post->tags as $tag)
                                <a href="{{ route('tags.posts', $tag) }}" class="badge bg-info me-1 text-decoration-none">#{{ $tag->name }}</a>                                @endforeach
                            </div>
                        @endif
                        
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary w-100">
                            Read More →
                        </a>
                    </div>
                    
                    <div class="card-footer text-muted">
                        <small>💬 {{ $post->comments->count() }} comments</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <h1 class="display-6">📭</h1>
                <p class="lead">No posts yet. Be the first to create one!</p>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    Create First Post
                </a>
            </div>
        @endforelse
    </div>
    
    <!-- SIMPLE PAGINATION - Works with both Collection and Paginator -->
    @if(method_exists($posts, 'links'))
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center">
            @if (method_exists($posts, 'onFirstPage') && $posts->onFirstPage())
                <span class="btn btn-outline-secondary disabled">
                    ← Previous
                </span>
            @elseif(method_exists($posts, 'previousPageUrl'))
                <a href="{{ $posts->previousPageUrl() }}" class="btn btn-outline-primary">
                    ← Previous
                </a>
            @else
                <span class="btn btn-outline-secondary disabled">
                    ← Previous
                </span>
            @endif
            
            <div class="text-muted">
                <small>
                    @if(method_exists($posts, 'currentPage') && method_exists($posts, 'lastPage'))
                        Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}
                    @else
                        Showing {{ $posts->count() }} posts
                    @endif
                </small>
            </div>
            
            @if (method_exists($posts, 'hasMorePages') && $posts->hasMorePages())
                <a href="{{ $posts->nextPageUrl() }}" class="btn btn-outline-primary">
                    Next →
                </a>
            @elseif(method_exists($posts, 'nextPageUrl'))
                <a href="{{ $posts->nextPageUrl() }}" class="btn btn-outline-primary">
                    Next →
                </a>
            @else
                <span class="btn btn-outline-secondary disabled">
                    Next →
                </span>
            @endif
        </div>
    </div>
    @endif
@endsection