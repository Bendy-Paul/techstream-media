<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech News & Insights | Tech Media Directory</title>
    @include('partials.links')
    <style>
        /* News Hero */
        .news-hero {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: white;
            padding: 50px 0 30px;
            position: relative;
            overflow: hidden;
        }

        .news-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="%23ffffff" opacity="0.05"><polygon points="1000,100 1000,0 0,100"></polygon></svg>');
            background-size: cover;
        }

        .news-hero-content {
            position: relative;
            z-index: 2;
        }

        /* News Card */
        .news-card {
            background: var(--bg-card);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(37, 99, 235, 0.1);
            transition: 0.3s;
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }

        .news-img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .news-featured-img {
            height: 400px;
            width: 100%;
            object-fit: cover;
        }

        .news-body {
            padding: 25px;
        }

        .news-category {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-accent);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 12px;
        }

        .news-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
        }

        .news-tag {
            background: rgba(37, 99, 235, 0.05);
            color: var(--text-muted);
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
        }

        .news-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Category Filter */
        .category-filter {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(37, 99, 235, 0.1);
            margin-bottom: 30px;
        }

        .filter-btn {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-accent);
            border: none;
            border-radius: 8px;
            padding: 8px 20px;
            margin: 5px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary-accent);
            color: white;
        }

        /* Sidebar */
        .sidebar-widget {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(37, 99, 235, 0.1);
            margin-bottom: 30px;
            position: sticky;
            top: 80px;
        }

        .tag-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag-item {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-accent);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            text-decoration: none;
            transition: 0.3s;
        }

        .tag-item:hover {
            background: var(--primary-accent);
            color: white;
        }
    </style>
</head>

<body>
    @include('partials.navbar')
    {{-- NEWS HERO --}}
<section class="news-hero">
    <div class="container news-hero-content">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8" data-aos="fade-up">
                <h1 class="display-4 fw-bold mb-4">Tech News & Insights</h1>
                <p class="lead mb-4">
                    Stay updated with the latest trends, innovations, and stories from Nigeria's booming tech ecosystem.
                </p>
            </div>
        </div>
    </div>
</section>

<br><br>

<div class="container">

    {{-- FEATURED ARTICLE --}}
    @if ($featured)
        @php
            $featImg = $featured->featured_image_url
                ?? 'https://placehold.co/800x400/f1f5f9/2563eb?text=Featured+Tech+News';
        @endphp

        <div class="news-card mb-5" data-aos="fade-up" data-aos-delay="100">
            <div class="position-relative">
                <img src="{{ $featImg }}" class="news-featured-img" alt="{{ $featured->title }}">
                <span class="position-absolute top-0 start-0 m-3 badge bg-primary">FEATURED</span>
            </div>

            <div class="news-body">
                <span class="news-category">Featured</span>

                <a href="{{ url('article/' . $featured->slug) }}">
                    <h2 class="fw-bold mb-3">{{ $featured->title }}</h2>
                </a>

                <p class="text-muted mb-3">
                    {{ \Illuminate\Support\Str::limit(strip_tags($featured->content), 160) }}
                </p>

                {{-- TAGS --}}
                <div class="news-tags">
                    @foreach ($featured->tags as $tag)
                        <span class="news-tag">{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- MAIN GRID --}}
    <div class="row">

        {{-- FILTER / LEFT --}}
        <div class="col-lg-10">

            <form id="news-filter-form" class="category-filter" data-aos="fade-up">
                <h5 class="fw-bold mb-3">Filter by Category</h5>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button type="button" class="filter-btn active" data-category="">All</button>

                    @foreach ($categories as $cat)
                        <button
                            type="button"
                            class="filter-btn"
                            data-category="{{ $cat->slug }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>

                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        name="search"
                        placeholder="Search articles...">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <input type="hidden" name="category" id="news-category-input" value="">

                {{-- TAG CLOUD --}}
                <div data-aos="fade-left" data-aos-delay="300">
                    <h5 class="fw-bold mb-4">Popular Tags</h5>
                    <div class="tag-cloud">
                        @foreach ($tags as $tag)
                            <a href="?s={{ urlencode($tag->name) }}" class="tag-item">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-2">
            <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="400">
                <h5 class="fw-bold mb-3">Stay Updated</h5>
                <p class="text-muted small mb-3">
                    Get the latest tech news and insights delivered to your inbox.
                </p>
                <button
                    class="btn btn-primary w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#newsletterModal">
                    Subscribe
                </button>
            </div>
        </div>
    </div>

    {{-- NEWS GRID --}}
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="row g-4" id="news-list">
                {{-- Loaded via AJAX --}}
            </div>

            <nav aria-label="Page navigation" class="mt-5">
                <div id="news-pagination" class="d-flex justify-content-center"></div>
            </nav>
        </div>
    </div>
</div>

{{-- NEWSLETTER MODAL --}}
<div class="modal fade" id="newsletterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subscribe to Newsletter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="newsletter-form">
                    @csrf
                    <div class="input-group">
                        <input
                            type="email"
                            class="form-control"
                            placeholder="Your email"
                            required>
                        <button class="btn btn-primary">
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')
<script src="{{url(asset('js/news-filter.js'))}}"></script>
<script>
    // AJAX category button logic
    document.addEventListener('DOMContentLoaded', function() {
        const catBtns = document.querySelectorAll('.filter-btn');
        const catInput = document.getElementById('news-category-input');
        catBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                catBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                catInput.value = this.getAttribute('data-category');
                document.getElementById('news-filter-form').dispatchEvent(new Event('submit'));
            });
        });
    });
</script>