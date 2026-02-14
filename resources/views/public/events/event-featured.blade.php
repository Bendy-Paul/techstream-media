<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} | Featured Event</title>
    @include('partials.links')
    <style>
        :root {
            --featured-bg: #0f172a;
            --featured-text: #f8fafc;
            --featured-accent: #38bdf8;
        }
        body {
            background-color: #f8f9fa;
        }
        .featured-header {
            background-color: var(--featured-bg);
            color: var(--featured-text);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        .featured-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("{{ $event->banner_image_url }}") no-repeat center center;
            background-size: cover;
            opacity: 0.2;
            filter: blur(8px);
        }
        .content-wrapper {
            position: relative;
            z-index: 2;
        }
        .featured-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        .btn-featured {
            background: var(--featured-accent);
            color: var(--featured-bg);
            border: none;
            font-weight: 700;
            padding: 12px 32px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .btn-featured:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(56, 189, 248, 0.4);
            color: var(--featured-bg);
        }
        .speaker-card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <!-- Premium Header -->
    <header class="featured-header">
        <div class="container content-wrapper">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-info text-dark mb-3 px-3 py-2 rounded-pill fw-bold">FEATURED EVENT</span>
                    <h1 class="display-3 fw-bold mb-4">{{ $event->title }}</h1>
                    
                    <div class="d-flex flex-wrap gap-4 mb-5 text-white-50">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-day text-info me-2 fs-5"></i>
                            <span class="fs-5">{{ \Carbon\Carbon::parse($event->start_datetime)->format('F d, Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-info me-2 fs-5"></i>
                            <span class="fs-5">{{ $event->location_name }}</span>
                        </div>
                    </div>

                    <a href="{{ $event->ticket_url ?? '#' }}" class="btn btn-featured btn-lg">
                        Secure Your Spot <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container" style="margin-top: -50px; position: relative; z-index: 10;">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card featured-card bg-white p-5 mb-5">
                    <h3 class="fw-bold mb-4 text-dark">Event Overview</h3>
                    <div class="text-secondary">
                        {!! nl2br(e($event->description)) !!}
                    </div>

                    @if($event->speakers && $event->speakers->count() > 0)
                        <hr class="my-5">
                        <h3 class="fw-bold mb-4 text-dark">Keynote Speakers</h3>
                        <div class="row g-4">
                            @foreach($event->speakers as $speaker)
                                <div class="col-md-4 text-center">
                                    <div class="speaker-card mb-3">
                                        <img src="{{ $speaker->photo_url ?? 'https://placehold.co/150' }}" class="rounded-circle" alt="{{ $speaker->name }}">
                                    </div>
                                    <h5 class="fw-bold mb-1">{{ $speaker->name }}</h5>
                                    <p class="text-muted small">{{ $speaker->position }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Schedule/Agenda (Optional placeholder logic) -->
                <div class="card featured-card bg-white p-5 mb-5">
                    <h3 class="fw-bold mb-4">Agenda Highlights</h3>
                    <div class="timeline">
                        <div class="d-flex mb-4">
                            <div class="me-4 text-center" style="min-width: 80px;">
                                <span class="fw-bold text-dark d-block">{{ \Carbon\Carbon::parse($event->start_datetime)->format('h:i A') }}</span>
                            </div>
                            <div class="border-start ps-4 border-info">
                                <h5 class="fw-bold">Registration & Networking</h5>
                                <p class="text-muted small">Check-in and meet fellow attendees.</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="me-4 text-center" style="min-width: 80px;">
                                <span class="fw-bold text-dark d-block">{{ \Carbon\Carbon::parse($event->start_datetime)->addHours(1)->format('h:i A') }}</span>
                            </div>
                            <div class="border-start ps-4 border-info">
                                <h5 class="fw-bold">Opening Keynote</h5>
                                <p class="text-muted small">Welcome address.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card featured-card bg-white p-4 mb-4">
                    <h5 class="fw-bold mb-4">Event Details</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3 pb-3 border-bottom d-flex justify-content-between">
                            <span class="text-muted">Date</span>
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($event->start_datetime)->format('M d, Y') }}</span>
                        </li>
                        <li class="mb-3 pb-3 border-bottom d-flex justify-content-between">
                            <span class="text-muted">Time</span>
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($event->start_datetime)->format('h:i A') }}</span>
                        </li>
                        <li class="mb-3 pb-3 border-bottom d-flex justify-content-between">
                            <span class="text-muted">Entry Fee</span>
                            <span class="fw-bold text-success">{{ $event->price == 0 ? 'Free' : '₦' . number_format($event->price) }}</span>
                        </li>
                    </ul>
                    <div class="d-grid">
                        <button class="btn btn-outline-dark" onclick="window.print()">
                            <i class="fas fa-print me-2"></i> Print Details
                        </button>
                    </div>
                </div>

                <!-- Organizers -->
                @if($event->organizers->count() > 0)
                <div class="card featured-card bg-white p-4">
                    <h5 class="fw-bold mb-4">Hosted By</h5>
                    @foreach($event->organizers as $org)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $org->logo ?? 'https://placehold.co/50' }}" class="rounded me-3 border" width="50" height="50">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $org->name }}</h6>
                                <a href="#" class="small text-decoration-none">View Profile</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    @include('partials.footer')

</body>
</html>
