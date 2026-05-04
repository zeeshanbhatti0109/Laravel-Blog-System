@props(['comment', 'level' => 0])

<div class="comment-item mb-3" id="comment-{{ $comment->id }}" style="margin-left: {{ $level * 30 }}px;">
    <div class="bg-white p-3 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <strong><a href="/author/{{ $comment->user->slug }}/posts" class="text-decoration-none text-dark">{{ $comment->user->name }}</a></strong>
                <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
            
            @if(auth()->check() && auth()->id() == 1)
            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this comment and all replies?')">
                    🗑️
                </button>
            </form>
            @endif
        </div>
        
        <p class="mb-2 mt-2">{{ $comment->body }}</p>
        
        <button class="btn btn-sm btn-outline-primary reply-btn" data-comment-id="{{ $comment->id }}">
            💬 Reply
        </button>
        
        <div class="reply-form mt-2" id="reply-form-{{ $comment->id }}" style="display: none;">
            <form action="{{ route('comments.store', $comment->post) }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <textarea name="body" rows="2" class="form-control" placeholder="Write your reply..."></textarea>
                <button type="submit" class="btn btn-sm btn-primary mt-2">Post Reply</button>
                <button type="button" class="btn btn-sm btn-secondary mt-2 cancel-reply" data-comment-id="{{ $comment->id }}">Cancel</button>
            </form>
        </div>
    </div>
    
    @if($comment->replies->count() > 0)
        <div class="replies-container mt-2">
            @foreach($comment->replies as $reply)
                @include('comments.comment', ['comment' => $reply, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>

<script>
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.commentId;
            const form = document.getElementById(`reply-form-${id}`);
            if (form) form.style.display = 'block';
        });
    });
    
    document.querySelectorAll('.cancel-reply').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.commentId;
            const form = document.getElementById(`reply-form-${id}`);
            if (form) form.style.display = 'none';
        });
    });
</script>