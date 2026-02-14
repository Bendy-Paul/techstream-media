<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} | Tech Media Directory</title>
    @include('partials.links')
    <style>
        .event-banner {
            height: 400px;
            object-fit: cover;
            width: 100%;
        }
        .ticket-card {
            top: 20px;
            position: sticky;
            z-index: 10;
        }
        .event-header-overlay {
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 3rem;
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <!-- Banner -->
    <div class="position-relative">
        <img src="{{ $event->banner_image_url ?? 'https://placehold.co/1200x400' }}" class="event-banner" alt="{{ $event->title }}">
        <div class="event-header-overlay text-white">
            <div class="container">
                <span class="badge bg-primary mb-2">{{ $event->event_type ?? 'Event' }}</span>
                <h1 class="display-4 fw-bold">{{ $event->title }}</h1>
                <p class="lead mb-0"><i class="fas fa-map-marker-alt me-2"></i> {{ $event->location_name ?? 'Online' }}</p>
            </div>
        </div>
    </div>

    <section class="section-padding py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- About -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3">About this Event</h3>
                        <div class="text-muted">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    </div>

                    <!-- Organizers -->
                    @if($event->organizers && $event->organizers->count() > 0)
                    <div class="mb-5">
                        <h4 class="fw-bold mb-4">Organized By</h4>
                        <div class="d-flex gap-4">
                            @foreach($event->organizers as $org)
                                <div class="d-flex align-items-center">
                                    <img src="{{ $org->logo ?? 'https://placehold.co/50' }}" class="rounded-circle me-3 border" width="50" height="50" alt="{{ $org->name }}">
                                    <span class="fw-bold">{{ $org->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Reviews -->
                    @include('partials.reviews', ['item' => $event])
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 ticket-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <h4 class="fw-bold mb-0">Event Details</h4>
                                @include('partials.save-button', ['item' => $event])
                            </div>
                            
                            <div class="d-flex mb-3 align-items-center">
                                <div class="me-3 text-primary"><i class="fas fa-calendar-alt fa-lg"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Date & Time</h6>
                                    <p class="text-muted small mb-0">
                                        {{ \Carbon\Carbon::parse($event->start_datetime)->format('M d, Y') }} <br>
                                        {{ \Carbon\Carbon::parse($event->start_datetime)->format('h:i A') }} - 
                                        {{ $event->end_datetime ? \Carbon\Carbon::parse($event->end_datetime)->format('h:i A') : '' }}
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex mb-3 align-items-center">
                                <div class="me-3 text-primary"><i class="fas fa-map-marked-alt fa-lg"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Location</h6>
                                    <p class="text-muted small mb-0">
                                        {{ $event->location_name }} <br>
                                        {{ $event->city ? $event->city->name : '' }}
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex mb-4 align-items-center">
                                <div class="me-3 text-primary"><i class="fas fa-ticket-alt fa-lg"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Price</h6>
                                    <p class="text-muted small mb-0">
                                        {{ $event->price == 0 ? 'Free' : '₦'.number_format($event->price) }}
                                    </p>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ $event->ticket_url ?? '#' }}" class="btn btn-primary btn-lg fw-bold" {{ !$event->ticket_url ? 'disabled' : '' }} target="_blank">
                                    Register Now
                                </a>
                            </div>

                            @if($event->social_links)
                                <div class="text-center mt-4 border-top pt-3">
                                    <small class="text-muted text-uppercase mb-2 d-block">Share this event</small>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-light btn-sm rounded-circle"><i class="fab fa-facebook-f"></i></button>
                                        <button class="btn btn-light btn-sm rounded-circle"><i class="fab fa-twitter"></i></button>
                                        <button class="btn btn-light btn-sm rounded-circle"><i class="fab fa-linkedin-in"></i></button>
                                        <button class="btn btn-light btn-sm rounded-circle"><i class="fab fa-whatsapp"></i></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')
    
    @include('partials.scripts-reviews-save')

</body>
</html>
