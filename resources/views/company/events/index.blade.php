@extends('layouts.company')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Manage Events</h2>
    <a href="{{ route('company.events.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Create Event</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
            @forelse($events as $event)
                <li class="list-group-item p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">{{ $event->title }}</h5>
                            <small class="text-muted d-block mb-2">Created {{ $event->created_at->diffForHumans() }}</small>
                            <span class="badge bg-{{ $event->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($event->status) }}</span>
                        </div>
                        <div>
                            <a href="{{ route('company.events.edit', $event->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </div>
                    </div>
                </li>
            @empty
                <li class="list-group-item p-4 text-center text-muted">
                    No events hosted yet.
                </li>
            @endforelse
        </ul>
    </div>
</div>
<div class="mt-3">
    {{ $events->links() }}
</div>
@endsection
