@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">✏️ Edit Post</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('posts.update', $post) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text" name="title" id="title" class="form-control" required 
                           value="{{ old('title', $post->title) }}">
                </div>
                
                <div class="mb-3">
                    <label for="body" class="form-label">Content *</label>
                    <textarea name="body" id="body" rows="8" class="form-control" required>{{ old('body', $post->body) }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="tags" class="form-label">Tags (comma separated)</label>
                    <input type="text" name="tags" id="tags" class="form-control" 
                           value="{{ $post->tags->pluck('name')->implode(', ') }}">
                    <small class="form-text text-muted">Separate tags with commas (e.g., laravel, php, bootstrap)</small>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Post</button>
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection