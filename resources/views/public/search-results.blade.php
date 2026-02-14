<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Tech Media Directory</title>
    
    @include('partials.links')

    <style>
        .search-header {
            background: #f8fafc;
            padding: 3rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .search-filters {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            position: sticky;
            top: 20px;
        }
        .result-card {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        .result-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: var(--primary-accent);
        }
        .result-type {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .type-company { background: rgba(34, 197, 94, 0.1); color: #16a34a; }
        .type-event { background: rgba(59, 130, 246, 0.1); color: #2563eb; }
        .type-article { background: rgba(168, 85, 247, 0.1); color: #7c3aed; }
        .type-job { background: rgba(249, 115, 22, 0.1); color: #f97316; }

        .result-image {
            width: 80px;
            height: 80px;
            border-radius: 0.75rem;
            object-fit: cover;
            background: #f1f5f9;
        }
        .pagination .page-link {
            border: none;
            color: #64748b;
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 0.5rem;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary-accent);
            color: white;
        }
        .result-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }
        .result-meta-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.875rem;
            color: #64748b;
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <!-- Search Header -->
    <section class="search-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="fw-bold mb-3">Search Results</h1>
                    
                    <!-- Search Form -->
                    <form class="search-container mx-auto d-flex bg-white p-2 rounded-pill shadow-sm border" style="max-width: 600px;" method="GET" action="{{ route('search-results') }}">
                        <div class="d-flex align-items-center flex-grow-1 px-3">
                            <i class="fas fa-search text-muted me-2"></i>
                            <input type="text" name="s" class="form-control border-0 shadow-none" placeholder="Search companies, events, news..." value="{{ request('s', '') }}">
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fas fa-arrow-right"></i></button>
                    </form>
                    
                    <p class="text-muted mt-3 mb-0">
                        @if(request('s'))
                            Showing results for "<strong>{{ request('s') }}</strong>"
                        @else
                            Showing all results
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Results -->
    <section class="mt-5 mb-5">
        <div class="container">
            <div class="row">
                <!-- Filters -->
                <div class="col-lg-3 mb-4">
                    <div class="search-filters">
                        <form method="GET" action="{{ route('search-results') }}">
                            <!-- Hidden Search Term -->
                            <input type="hidden" name="s" value="{{ request('s', '') }}">
                            
                            <h5 class="fw-bold mb-3">Filters</h5>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Type</label>
                                @php
                                    $selectedTypes = $searchParams['types'] ?? [];
                                    $typeParam = function($type) {
                                        return request()->has('type_' . $type);
                                    };
                                @endphp
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="type_company" value="1" id="filter-company" {{ $typeParam('company') || in_array('company', $selectedTypes) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="filter-company">Companies</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="type_event" value="1" id="filter-event" {{ $typeParam('event') || in_array('event', $selectedTypes) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="filter-event">Events</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="type_article" value="1" id="filter-article" {{ $typeParam('article') || in_array('article', $selectedTypes) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="filter-article">Articles</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="type_job" value="1" id="filter-job" {{ $typeParam('job') || in_array('job', $selectedTypes) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="filter-job">Jobs</label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Location</label>
                                <input type="text" name="loc" class="form-control" placeholder="e.g. Lagos" value="{{ request('loc', '') }}">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Category (Slug)</label>
                                <input type="text" name="cat" class="form-control" placeholder="e.g. fintech" value="{{ request('cat', '') }}">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        </form>
                    </div>
                </div>
                
                <!-- Results -->
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="text-muted mb-0">{{ number_format($totalResults) }} results found</p>
                        <!-- Sorting UI -->
                        <div>
                            <label class="form-label me-2 small text-muted">Sort by:</label>
                            <select class="form-select d-inline-block w-auto form-select-sm" onchange="window.location.href='?s={{ urlencode(request('s', '')) }}&sort='+this.value">
                                <option value="relevance" {{ request('sort', 'relevance') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                                <option value="newest" {{ request('sort', 'relevance') == 'newest' ? 'selected' : '' }}>Newest</option>
                            </select>
                        </div>
                    </div>
                    
                    @if($results->count() > 0)
                        @foreach($results as $row)
                            @php
                                // Determine Link and Meta based on Type
                                $link = '#';
                                $badgeClass = '';
                                $metaIcon1 = 'fa-map-marker-alt';
                                $metaIcon2 = 'fa-clock';
                                
                                if ($row->type === 'company') {
                                    $link = url('company-profile/' . $row->url_slug);
                                    $badgeClass = 'type-company';
                                    $metaIcon1 = 'fa-map-marker-alt';
                                    $metaIcon2 = 'fa-users';
                                } elseif ($row->type === 'event') {
                                    $link = url('events/' . $row->url_slug);
                                    $badgeClass = 'type-event';
                                    $metaIcon1 = 'fa-map-marker-alt';
                                    $metaIcon2 = 'fa-calendar-alt';
                                } elseif ($row->type === 'article') {
                                    $link = url('article/' . $row->url_slug);
                                    $badgeClass = 'type-article';
                                    $metaIcon1 = 'fa-user-edit';
                                    $metaIcon2 = 'fa-calendar-day';
                                } elseif ($row->type === 'job') {
                                    $link = url('job/' . $row->url_slug);
                                    $badgeClass = 'type-job';
                                    $metaIcon1 = 'fa-map-marker-alt';
                                    $metaIcon2 = 'fa-building';
                                }
                                
                                // Image Fallback
                                $img = !empty($row->image_url) ? $row->image_url : 'https://placehold.co/100x100/f1f5f9/2563eb?text='.ucfirst($row->type);
                                
                                // Format description
                                $description = strip_tags($row->description ?? '');
                                if (strlen($description) > 150) {
                                    $description = substr($description, 0, 150) . '...';
                                }
                                
                                // Format meta_2 if it's a date
                                $meta2 = $row->meta_2;
                                if ($meta2 && strtotime($meta2) && $row->type !== 'company') {
                                    $meta2 = \Carbon\Carbon::parse($meta2)->format('M d, Y');
                                }
                            @endphp
                            
                            <!-- Result Item -->
                            <div class="result-card">
                                <span class="result-type {{ $badgeClass }}">{{ ucfirst($row->type) }}</span>
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img src="{{ $img }}" class="result-image" alt="Image">
                                    </div>
                                    <div class="col">
                                        <h4 class="fw-bold mb-2">
                                            <a href="{{ $link }}" class="text-dark text-decoration-none">{{ $row->title }}</a>
                                        </h4>
                                        <p class="text-muted mb-2 text-truncate" style="max-width: 600px;">
                                            {{ $description }}
                                        </p>
                                        
                                        <div class="result-meta">
                                            <!-- Meta 1 (Location/Author) -->
                                            @if($row->meta_1)
                                                <div class="result-meta-item">
                                                    <i class="fas {{ $metaIcon1 }}"></i>
                                                    <span>{{ $row->meta_1 }}</span>
                                                </div>
                                            @endif
                                            
                                            <!-- Meta 2 (Date/Size/Company) -->
                                            @if($meta2)
                                                <div class="result-meta-item">
                                                    <i class="fas {{ $metaIcon2 }}"></i>
                                                    <span>{{ $meta2 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto mt-3 mt-md-0">
                                        <a href="{{ $link }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">{{ $row->action_text ?? 'View Details' }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                    @else
                        <div class="text-center py-5">
                            <img src="{{ asset('assets/images/empty.svg') }}" alt="No results" style="width: 120px; opacity: 0.5;" class="mb-3">
                            <h4>No results found</h4>
                            <p class="text-muted">Try adjusting your search criteria or filters.</p>
                        </div>
                    @endif
                    
<!-- Pagination - Simple version -->
@if($totalPages > 1)
    <nav aria-label="Search results pagination" class="mt-5">
        <ul class="pagination justify-content-center">
            <!-- Previous -->
            <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                <a class="page-link" 
                   href="?s={{ urlencode(request('s', '')) }}&page={{ $currentPage - 1 }}&loc={{ urlencode(request('loc', '')) }}&sort={{ request('sort', 'relevance') }}{{ request('cat') ? '&cat=' . urlencode(request('cat', '')) : '' }}{{ request('type_company') ? '&type_company=1' : '' }}{{ request('type_event') ? '&type_event=1' : '' }}{{ request('type_article') ? '&type_article=1' : '' }}{{ request('type_job') ? '&type_job=1' : '' }}" 
                   tabindex="-1">Previous</a>
            </li>
            
            <!-- Page Numbers -->
            @for($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                    <a class="page-link" 
                       href="?s={{ urlencode(request('s', '')) }}&page={{ $i }}&loc={{ urlencode(request('loc', '')) }}&sort={{ request('sort', 'relevance') }}{{ request('cat') ? '&cat=' . urlencode(request('cat', '')) : '' }}{{ request('type_company') ? '&type_company=1' : '' }}{{ request('type_event') ? '&type_event=1' : '' }}{{ request('type_article') ? '&type_article=1' : '' }}{{ request('type_job') ? '&type_job=1' : '' }}">
                       {{ $i }}
                    </a>
                </li>
            @endfor
            
            <!-- Next -->
            <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                <a class="page-link" 
                   href="?s={{ urlencode(request('s', '')) }}&page={{ $currentPage + 1 }}&loc={{ urlencode(request('loc', '')) }}&sort={{ request('sort', 'relevance') }}{{ request('cat') ? '&cat=' . urlencode(request('cat', '')) : '' }}{{ request('type_company') ? '&type_company=1' : '' }}{{ request('type_event') ? '&type_event=1' : '' }}{{ request('type_article') ? '&type_article=1' : '' }}{{ request('type_job') ? '&type_job=1' : '' }}">
                   Next
                </a>
            </li>
        </ul>
    </nav>
@endif
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')
</body>
</html>