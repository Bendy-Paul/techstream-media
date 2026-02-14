@include('admin.partials.header')
@include('admin.partials.sidebar')

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Edit Review</h2>
            <p class="text-muted mb-0">Update review details.</p>
        </div>
        <div>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Back to Reviews</a>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Company</label>
                    <input type="text" class="form-control" value="{{ $review->company->name ?? 'N/A' }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">User</label>
                    <input type="text" class="form-control" value="{{ $review->user->name ?? 'N/A' }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Rating</label>
                    <input type="number" name="rating" class="form-control" min="1" max="5" value="{{ old('rating', $review->rating) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Comment</label>
                    <textarea name="comment" class="form-control" rows="4" required>{{ old('comment', $review->comment) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" @if($review->status=='pending') selected @endif>Pending</option>
                        <option value="approved" @if($review->status=='approved') selected @endif>Approved</option>
                        <option value="rejected" @if($review->status=='rejected') selected @endif>Rejected</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Review</button>
            </form>
        </div>
    </div>
</main>
