<div class="reviews-section mt-5">
    <h4 class="fw-bold mb-4">Reviews</h4>

    <!-- Review Form -->
    @auth
        @if(auth()->user()->hasVerifiedEmail())
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Leave a Review</h5>
                    <form id="review-form" action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <input type="hidden" name="item_type" value="{{ get_class($item) }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" required>
                                        <label class="form-check-label" for="rating{{ $i }}">{{ $i }} <i class="fas fa-star text-warning"></i></label>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Share your experience..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" id="btn-submit-review">Submit Review</button>
                    </form>
                    <div id="review-message" class="mt-3"></div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                Please <a href="{{ route('verification.notice') }}">verify your email</a> to leave a review.
            </div>
        @endif
    @else
        <div class="alert alert-info">
            Please <a href="{{ route('login') }}">login</a> to leave a review.
        </div>
    @endauth

    <!-- Reviews List -->
    <div class="reviews-list">
        @forelse($item->reviews()->with('user')->approved()->latest()->take(5)->get() as $review)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="fw-bold mb-0">{{ $review->user->name ?? 'Anonymous' }}</h6>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="text-warning">
                            @for($i = 0; $i < $review->rating; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="mt-2 mb-0">{{ $review->comment }}</p>
                </div>
            </div>
        @empty
            <p class="text-muted">No reviews yet. Be the first to review!</p>
        @endforelse
    </div>
</div>
