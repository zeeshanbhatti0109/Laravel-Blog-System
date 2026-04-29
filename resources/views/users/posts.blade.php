@extends('layouts.app')

@section('title', $user->name . "'s Posts")

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6">📝 Posts by {{ $user->name }}</h1>
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
            ← Back to All Posts
        </a>
    </div>
    
    <div class="row g-4">
        @forelse($posts as $post)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 shadow-sm post-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ $post->title }}</h5>
                        <p class="card-text text-muted mb-3">
                            {{ Str::limit($post->body, 100) }}
                        </p>
                        
                        @if($post->tags->count() > 0)
                            <div class="mb-3">
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('tags.posts', $tag) }}" class="badge bg-info me-1 text-decoration-none">#{{ $tag->name }}</a>
                                @endforeach
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
                <p class="lead">No posts found by {{ $user->name }}.</p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
@endsection