@extends('layouts.company')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-0 fw-bold">Manage Events</h2>
        <p class="text-muted small mb-0">Events you have submitted or organised.</p>
    </div>
    <a href="{{ route('company.events.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Create Event
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Title</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $event->title }}</div>
                            <small class="text-muted">{{ $event->location_name ?? ($event->is_virtual ? 'Virtual' : '—') }}</small>
                        </td>
                        <td>
                            @if($event->start_datetime)
                                <span class="text-dark">{{ \Carbon\Carbon::parse($event->start_datetime)->format('M d, Y') }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'published' => 'success',
                                    'pending'   => 'warning',
                                    'draft'     => 'secondary',
                                    'rejected'  => 'danger',
                                    'archived'  => 'dark',
                                ];
                                $color = $statusColors[$event->event_status ?? 'draft'] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst($event->event_status ?? 'draft') }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('company.events.edit', $event->id) }}" class="btn btn-sm btn-outline-secondary me-1">
                                <i class="fas fa-pen me-1"></i> Edit
                            </a>
                            <form action="{{ route('company.events.destroy', $event->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this event? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            <i class="fas fa-calendar-times fa-2x mb-3 d-block text-light"></i>
                            No events yet. <a href="{{ route('company.events.create') }}">Create one now</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $events->links() }}
</div>
@endsection
