<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events | Tech Media Directory</title>
    @include('partials.links')
    <style>
        .event-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .event-date-badge {
            background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));
            color: white;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            min-width: 60px;
        }
        .event-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
            border-top-left-radius: var(--bs-border-radius-lg);
            border-top-right-radius: var(--bs-border-radius-lg);
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <!-- Header Section -->
    <header class="py-5 bg-light border-bottom">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="fw-bold display-5 mb-3">Discover Tech Events</h1>
                    <p class="lead text-muted mb-4">Connect, learn, and grow with the best tech conferences, meetups, and workshops in Nigeria.</p>
                    
                    <form action="{{ route('events.index') }}" method="GET" class="d-flex gap-2">
                        <input type="text" name="s" class="form-control form-control-lg" placeholder="Search events..." value="{{ request('s') }}">
                        <button class="btn btn-primary btn-lg" type="submit">Search</button>
                    </form>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-end">
                    <!-- Optional: Add a hero image or illustration here -->
                    <i class="fas fa-calendar-alt fa-10x text-primary opacity-10"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Filter & Content Section -->
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm p-3">
                        <h5 class="fw-bold mb-3">Filters</h5>
                        <div class="mb-3">
                            <label class="form-label small text-uppercase text-muted fw-bold">Categories</label>
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('events.index') }}" class="text-decoration-none {{ !request('category') && !isset($category) ? 'fw-bold text-primary' : 'text-dark' }}">All Events</a>
                                @foreach(\App\Models\Category::where('type', 'event')->get() as $cat)
                                    <a href="{{ route('events.category', $cat->slug) }}" class="text-decoration-none {{ (isset($category) && $category->id == $cat->id) ? 'fw-bold text-primary' : 'text-dark' }}">
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Events Grid -->
                <div class="col-lg-9">
                    @if(isset($category))
                        <div class="mb-4">
                            <h4 class="fw-bold">Events in <span class="text-primary">{{ $category->name }}</span></h4>
                        </div>
                    @endif

                    @if($events->count() > 0)
                        <div class="row g-4">
                            @foreach($events as $event)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border-0 shadow-sm event-card rounded-4">
                                        <div class="position-relative">
                                            <img src="{{ $event->banner_image_url ?? 'https://placehold.co/600x400' }}" class="event-image" alt="{{ $event->title }}">
                                            @if($event->is_featured)
                                                <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark fw-bold shadow-sm">Featured</span>
                                            @endif
                                            <div class="position-absolute bottom-0 start-0 m-3">
                                                <span class="badge bg-white text-dark shadow-sm">
                                                    <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $event->city ? $event->city->name : 'Virtual' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="event-date-badge me-3 shadow-sm">
                                                    <div class="fw-bold fs-5">{{ \Carbon\Carbon::parse($event->start_datetime)->format('d') }}</div>
                                                    <div class="small text-uppercase">{{ \Carbon\Carbon::parse($event->start_datetime)->format('M') }}</div>
                                                </div>
                                                <div>
                                                    <h5 class="fw-bold mb-1">
                                                        <a href="{{ route('events.show', $event->slug) }}" class="text-dark text-decoration-none stretched-link">
                                                            {{ Str::limit($event->title, 40) }}
                                                        </a>
                                                    </h5>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($event->start_datetime)->format('h:i A') }}</small>
                                                </div>
                                            </div>
                                            <p class="text-muted small mb-3">{{ Str::limit($event->description, 80) }}</p>
                                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                                <span class="fw-bold text-primary">
                                                    {{ $event->price == 0 ? 'Free' : '₦'.number_format($event->price) }}
                                                </span>
                                                <span class="small text-muted">
                                                    <i class="fas fa-user-friends me-1"></i> {{ $event->attendees_count ?? 0 }} going
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-5">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-calendar-times fa-4x text-muted opacity-25"></i>
                            </div>
                            <h3>No events found</h3>
                            <p class="text-muted">Try adjusting your search or filters.</p>
                            <a href="{{ route('events.index') }}" class="btn btn-outline-primary">Browse All Events</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

</body>
</html>