@extends('layouts.user')

@section('content')
<div class="row">
    <!-- Profile Card -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
                @if($organizer->logo_path)
                    <img src="{{ Storage::url($organizer->logo_path) }}" class="rounded-circle mb-3 object-fit-cover" width="100" height="100" alt="{{ $organizer->name }}">
                @else
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3 text-secondary" style="width: 100px; height: 100px;">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                @endif
                
                <h4 class="mb-1">{{ $organizer->name }}</h4>
                <p class="text-muted small mb-3">{{ $organizer->email }}</p>
                
                @if($organizer->website)
                    <a href="{{ $organizer->website }}" target="_blank" class="d-block mb-2 text-decoration-none">
                        <i class="fas fa-globe me-1"></i> {{ parse_url($organizer->website, PHP_URL_HOST) }}
                    </a>
                @endif

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('user.organizer.edit') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit Profile
                    </a>
                    <a href="{{ route('user.organizer.events.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Create New Event
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats & Recent Events -->
    <div class="col-md-8 mb-4">
        <div class="row g-4 mb-4">
            <div class="col-sm-6">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 text-white-50">Total Events</h6>
                                <h2 class="mb-0 fw-bold">{{ $organizer->events()->count() }}</h2>
                            </div>
                            <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                 <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 text-muted">Upcoming Events</h6>
                                <h2 class="mb-0 fw-bold">{{ $organizer->events()->where('start_datetime', '>', now())->count() }}</h2>
                            </div>
                            <i class="fas fa-clock fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Events</h5>
                <a href="{{ route('user.organizer.events.index') }}" class="btn btn-sm btn-link">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($organizer->events()->latest()->take(5)->get() as $event)
                        <div class="list-group-item px-4 py-3 border-0 border-bottom">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('user.organizer.events.edit', $event->id) }}" class="text-dark text-decoration-none">
                                            {{ $event->title }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="far fa-calendar me-1"></i> {{ $event->start_datetime->format('M d, Y') }}
                                        &bull; 
                                        <span class="badge bg-{{ $event->event_status === 'published' ? 'success' : ($event->event_status === 'pending' ? 'warning' : 'secondary') }} bg-opacity-10 text-{{ $event->event_status === 'published' ? 'success' : ($event->event_status === 'pending' ? 'warning' : 'secondary') }} px-2 py-1 rounded-pill ms-1" style="font-size: 0.75em;">
                                            {{ ucfirst($event->event_status) }}
                                        </span>
                                    </small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('user.organizer.events.edit', $event->id) }}">Edit</a></li>
                                        <li><a class="dropdown-item" href="{{ route('events.show', $event->slug) }}" target="_blank">View</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times fa-2x mb-2 opacity-50"></i>
                            <p>No events found. Start by creating one!</p>
                            <a href="{{ route('user.organizer.events.create') }}" class="btn btn-sm btn-primary mt-2">Create Event</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
