@extends('layouts.app')

@section('title', 'Create New Post')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">✍️ Create New Post</h4>
        </div>
        
        <div class="card-body">
            <form action="{{ route('posts.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text" name="title" id="title" class="form-control" required placeholder="Enter post title">
                </div>
                
                <div class="mb-3">
                    <label for="body" class="form-label">Content *</label>
                    <textarea name="body" id="body" rows="8" class="form-control" required placeholder="Write your post content here..."></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="tags" class="form-label">Tags (comma separated)</label>
                    <input type="text" name="tags" id="tags" class="form-control" placeholder="laravel, php, bootstrap">
                    <small class="form-text text-muted">Separate tags with commas (e.g., laravel, php, blog)</small>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Publish Post</button>
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection