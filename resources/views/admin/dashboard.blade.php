@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h4 class="mb-0">👑 Admin Dashboard</h4>
    </div>
    <div class="card-body">
        <p>Welcome, {{ auth()->user()->name }}! You are logged in as <strong>{{ ucfirst(auth()->user()->role) }}</strong>.</p>

        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">👥 Manage Users</h5>
                        <p class="card-text">View, edit roles, and manage all users.</p>
                        <a href="{{ route('admin.users') }}" class="btn btn-primary">Go to Users</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">📖 All Posts</h5>
                        <p class="card-text">View and manage all blog posts.</p>
                        <a href="{{ route('posts.index') }}" class="btn btn-primary">Go to Posts</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">✍️ Create Post</h5>
                        <p class="card-text">Write a new blog post.</p>
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Post</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
