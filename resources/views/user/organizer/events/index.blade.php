@extends('layouts.user')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">My Events</h4>
    <a href="{{ route('user.organizer.events.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Create New Event
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 px-4 py-3">Event</th>
                        <th class="border-0 px-4 py-3">Date</th>
                        <th class="border-0 px-4 py-3">Status</th>
                        <th class="border-0 px-4 py-3">Price</th>
                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    @if($event->banner_image_url)
                                        <img src="{{ Storage::url($event->banner_image_url) }}" class="rounded me-3 object-fit-cover" width="50" height="50" alt="{{ $event->title }}">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 50px; height: 50px;">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">{{ $event->title }}</h6>
                                        <small class="text-muted">{{ Str::limit($event->location_name ?? 'Online', 30) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                {{ $event->start_datetime->format('M d, Y') }}<br>
                                <small class="text-muted">{{ $event->start_datetime->format('h:i A') }}</small>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusClass = match($event->event_status) {
                                        'published' => 'success',
                                        'pending' => 'warning',
                                        'rejected' => 'danger',
                                        'archived' => 'secondary',
                                        default => 'info',
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} px-3 py-2 rounded-pill">
                                    {{ ucfirst($event->event_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                {{ $event->price > 0 ? '$' . number_format($event->price, 2) : 'Free' }}
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                        <li><a class="dropdown-item" href="{{ route('user.organizer.events.edit', $event->id) }}"><i class="fas fa-edit me-2"></i> Edit</a></li>
                                        <li><a class="dropdown-item" href="{{ route('events.show', $event->slug) }}" target="_blank"><i class="fas fa-eye me-2"></i> Preview</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('user.organizer.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt me-2"></i> Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 opacity-50"></i>
                                    <h5>No events found</h5>
                                    <p class="mb-3">You haven't created any events yet.</p>
                                    <a href="{{ route('user.organizer.events.create') }}" class="btn btn-primary">Create Your First Event</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($events->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
