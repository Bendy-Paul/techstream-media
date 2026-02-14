@php
    $isSaved = auth()->check() && auth()->user()->hasSaved($item);
@endphp

@auth
    @if(auth()->user()->hasVerifiedEmail())
        <button class="btn btn-outline-primary btn-save-item {{ $isSaved ? 'active' : '' }}" 
                data-item-id="{{ $item->id }}" 
                data-item-type="{{ get_class($item) }}">
            <i class="fas {{ $isSaved ? 'fa-bookmark' : 'fa-bookmark' }} me-1"></i> 
            <span class="save-text">{{ $isSaved ? 'Saved' : 'Save' }}</span>
        </button>
    @else
        <button class="btn btn-outline-secondary" disabled title="Please verify your email to save items">
            <i class="far fa-bookmark me-1"></i> Save
        </button>
    @endif
@else
    <a href="{{ route('login') }}" class="btn btn-outline-secondary" title="Login to save">
        <i class="far fa-bookmark me-1"></i> Save
    </a>
@endauth
