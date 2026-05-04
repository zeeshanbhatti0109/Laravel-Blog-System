@extends('layouts.app')

@section('title', 'Posts tagged: ' . $tag->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6">🏷️ Posts tagged: #{{ $tag->name }}</h1>
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
            ← Back to All Posts
        </a>
    </div>
    
    <div class="row g-4">
        @forelse($posts as $post)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 shadow-sm post-card">
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2">
                            👤 <a href="/author/{{ $post->user->slug }}/posts" class="text-white text-decoration-none">{{ $post->user->name }}</a>
                        </span>
                        
                        <h5 class="card-title mb-3">{{ $post->title }}</h5>
                        
                        <p class="card-text text-muted mb-3">
                            {{ Str::limit($post->body, 100) }}
                        </p>
                        
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
                <p class="lead">No posts found with tag "{{ $tag->name }}".</p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
@endsection