<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->name }} | Premium Profile</title>
    @include('partials.links')



    <style>
        .premium-badge {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .profile-header {
            position: relative;
            width: 100%;
            height: 28rem;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: white;
        }

        .profile-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset($company->logo_url) }}');
            background-size: cover;
            background-position: center;
            filter: blur(5px);
            transform: scale(1.1);
            z-index: -2;
        }

        .profile-header::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(52, 74, 98, 0.4);
            z-index: -1;
        }

        .company-logo {
            width: 140px;
            height: 140px;
            border-radius: 25px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--primary-accent);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .company-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .verified-badge {
            position: absolute;
            bottom: -10px;
            right: 0px;
            background: var(--premium-gold);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
            border: 3px solid white;
        }

        .info-card {
            background: var(--bg-card);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.1);
            padding: 25px;
            transition: transform 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
        }

        .category-badge {
            background: rgba(37, 99, 235, 0.1);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-right: 8px;
            margin-bottom: 8px;
            display: inline-block;
        }

        .chart-container,
        canvas {
            position: relative;
            margin: auto;
            text-align: center;
            height: 300px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(37, 99, 235, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: var(--primary-accent);
            color: white;
            transform: translateY(-3px);
        }

        .gallery-item {
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 3rem;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .project-card {
            border-radius: 12px;
            overflow: hidden;
            background: #f8fafc;
            transition: transform 0.3s ease;
        }

        .project-card:hover {
            transform: translateY(-5px);
        }

        .article-card {
            border-left: 4px solid var(--primary-accent);
            padding-left: 20px;
        }

        .video-container {
            height: 400px;
            border-radius: 16px;
            overflow: hidden;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    @include('partials.navbar')

    <!-- Company Header -->
    <section class="profile-header">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start">
                    <div class="company-logo" data-aos="zoom-in">
                        @if($company->logo_url)
                            <img src="{{ asset($company->logo_url) }}" alt="{{ $company->name }}">
                        @else
                            <i class="fas fa-building"></i>
                        @endif

                        @if($company->is_verified)
                            <div class="verified-badge">
                                <i class="fas fa-check"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-7" data-aos="fade-right">
                    <h1 class="fw-bold mb-2">{{ $company->name }}</h1>
                    <p class="lead mb-3">{{ $company->tagline }}</p>
                    <div class="d-flex flex-wrap mb-3">
                        @foreach($categories as $category)
                            <span class="category-badge">{{ $category->name }}</span>
                        @endforeach
                    </div>
                    <div class="d-flex gap-3">
                        @if(!empty($socials['twitter']))
                            <a href="{{ $socials['twitter'] }}" class="social-icon"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if(!empty($socials['linkedin']))
                            <a href="{{ $socials['linkedin'] }}" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                        @if(!empty($socials['facebook']))
                            <a href="{{ $socials['facebook'] }}" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(!empty($socials['instagram']))
                            <a href="{{ $socials['instagram'] }}" class="social-icon"><i class="fab fa-instagram"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 text-center text-md-end" data-aos="fade-left">
                    @if($company->website_url)
                        <a href="{{ $company->website_url }}" target="_blank" class="btn btn-light mb-2">Visit Website</a>
                    @endif
                    <p class="mb-0"><i class="fas fa-map-marker-alt me-1"></i> {{ $company->city->name ?? '' }}, {{ $company->country->name ?? 'Nigeria' }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Company Overview -->
                <div class="info-card mb-4" data-aos="fade-up">
                    <div class="article-content">
                        <p>{!! $company->description !!}</p>
                    </div>
                </div>

                <!-- Competitive Analytics -->
                <div class="info-card mb-4" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="fw-bold mb-4">Performance Analytics</h3>
                    @php
                        $response_rate = isset($stats['response_rate']) ? (float)$stats['response_rate'] : null;
                        $completeness = isset($stats['completeness']) ? (float)$stats['completeness'] : null;
                        $views = $company->views ?? null;
                        $projects_count = $projects->count();
                    @endphp
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="info-card text-center">
                                <h5 class="mb-2">Response Rate</h5>
                                <div style="font-size:2.2rem;font-weight:700; color:#2563eb;">
                                    {{ $response_rate !== null ? $response_rate . '%' : 'N/A' }}
                                </div>
                                <div class="small text-muted">How quickly this company responds to inquiries.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="info-card text-center">
                                <h5 class="mb-2">Profile Completeness</h5>
                                <div style="font-size:2.2rem;font-weight:700; color:#10b981;">
                                    {{ $completeness !== null ? $completeness . '%' : 'N/A' }}
                                </div>
                                <div class="small text-muted">How complete the company profile is.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="info-card text-center">
                                <h5 class="mb-2">Profile Views</h5>
                                <div style="font-size:2.2rem;font-weight:700; color:#f59e0b;">
                                    {{ $views !== null ? number_format($views) : 'N/A' }}
                                </div>
                                <div class="small text-muted">Total times this profile has been viewed.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="info-card text-center">
                                <h5 class="mb-2">Projects Listed</h5>
                                <div style="font-size:2.2rem;font-weight:700; color:#3b82f6;">
                                    {{ $projects_count }}
                                </div>
                                <div class="small text-muted">Number of projects in portfolio.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Projects -->
                @if($projects->count() > 0)
                    <div class="info-card mb-4" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="fw-bold mb-4">Recent Projects</h3>
                        <div class="row">
                            @foreach($projects as $project)
                                <div class="col-md-6 mb-4">
                                    <div class="project-card p-3 h-100">
                                        <h5 class="fw-bold">{{ $project->title }}</h5>
                                        <p class="text-muted">{{ $project->description }}</p>
                                        @if($project->image_url)
                                            <img src="{{ asset($project->image_url) }}" class="img-fluid rounded mt-2" alt="Project Image">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Company Articles -->
                @if($company_news->count() > 0)
                    <div class="info-card mb-4" data-aos="fade-up" data-aos-delay="300">
                        <h3 class="fw-bold mb-4">Company News & Insights</h3>
                        @foreach($company_news as $news)
                            <div class="article-card mb-4">
                                <h5 class="fw-bold">
                                    <a href="{{ route('article.show', $news->slug) }}" class="text-dark text-decoration-none">
                                        {{ $news->title }}
                                    </a>
                                </h5>
                                <p class="text-muted">{{ Str::limit(strip_tags($news->content), 150) }}...</p>
                                <span class="text-muted small">Published: {{ $news->published_at->format('F j, Y') }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Reviews -->
                <div class="info-card mb-4" data-aos="fade-up">
                    @include('partials.reviews', ['item' => $company])
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                
                <!-- Save Company -->
                <div class="info-card mb-4 text-center">
                   @include('partials.save-button', ['item' => $company])
                   <p class="small text-muted mt-2">Save this company to your dashboard</p>
                </div>

                <!-- Contact Details -->
                <div class="info-card mb-4" data-aos="fade-left">
                    <h4 class="fw-bold mb-4">Contact Details</h4>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Website</h6>
                        <a href="{{ $company->website_url }}" target="_blank" class="text-decoration-none text-truncate d-block">{{ $company->website_url }}</a>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Phone</h6>
                        <p class="mb-0">{{ $company->phone }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Email</h6>
                        <p class="mb-0">{{ $company->email }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Team Size</h6>
                        <p class="mb-0">{{ $company->team_size ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Year Started</h6>
                        <p class="mb-0">
                            @if($company->year_founded)
                                {{ $company->year_founded }} ({{ $companyAge }} yrs old)
                                @if($avgPeerAge !== null)
                                    <br><span class="small text-muted">Category avg: {{ $avgPeerAge }} yrs</span>
                                @endif
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Starting Cost Per Project</h6>
                        <p class="mb-0">
                            @if($company->starting_cost)
                                {{ $company->currency }} {{ number_format($company->starting_cost) }}
                            @else
                                Contact for Quote
                            @endif
                        </p>
                    </div>
                </div>

                @if($company->stacks->count() > 0)
                    <div class="info-card mb-4" data-aos="fade-up" data-aos-delay="120">
                        <h3 class="fw-bold mb-4">Tech Stack & Tools</h3>
                        <div class="row g-3 align-items-center">
                            @foreach($company->stacks as $stack)
                                <div class="col-lg-4 mb-2">
                                    <div class="text-center gap-2">
                                        <span style="font-size:1.7rem;">
                                            <i class="{{ $stack->icon_class ?? 'fas fa-code' }} text-primary"></i>
                                        </span><br>
                                        <span class="fw-semibold small">{{ $stack->name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Profile Strength -->
                <div class="info-card mb-4" data-aos="fade-left" data-aos-delay="100">
                    <h4 class="fw-bold mb-4">Profile Strength</h4>
                    <div class="mb-2">
                        <div class="progress" style="height: 28px; background: #e5e7eb;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $profile_strength }}%; font-weight:600; font-size:1.1rem;" 
                                aria-valuenow="{{ $profile_strength }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $profile_strength }}%
                            </div>
                        </div>
                    </div>
                    <div class="small text-muted">
                        @if($avgStrength !== null)
                            <span>Your profile is <b>{{ $profile_strength }}%</b> complete. Category average: <b>{{ $avgStrength }}%</b>.</span>
                        @else
                            <span>Your profile is <b>{{ $profile_strength }}%</b> complete.</span>
                        @endif
                    </div>
                </div>

                <!-- Gallery -->
                @if($gallery->count() > 0)
                    <div class="info-card mb-4" data-aos="fade-left" data-aos-delay="200">
                        <h4 class="fw-bold mb-4">Gallery</h4>
                        <div class="row g-2">
                            @foreach($gallery as $img)
                                <div class="col-6">
                                    <div class="gallery-item">
                                        <img src="{{ asset($img->image_url) }}" alt="Gallery" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Location Map -->
                <div class="info-card" data-aos="fade-left" data-aos-delay="300">
                    <h4 class="fw-bold mb-4">Office Locations</h4>
                    <div class="bg-light rounded-3 p-3 mb-3">
                        <p class="fw-bold mb-2">Headquarters</p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i> {!! nl2br(e($company->address)) !!}</p>
                        <p class="mb-0">{{ $company->city->name ?? '' }}</p>
                    </div>
                    @if($branches->count() > 0)
                        <div class="mb-3">
                            <p class="fw-bold mb-2">Branches</p>
                            @foreach($branches as $branch)
                                <div class="border rounded p-2 mb-2 bg-white">
                                    <span class="fw-semibold">
                                        <i class="fas fa-map-marker-alt text-secondary me-1"></i> {{ $branch->city->name ?? '' }}
                                    </span><br>
                                    <span class="small text-muted">{!! nl2br(e($branch->address)) !!}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <a href="#" class="btn btn-outline-primary w-100">
                        <i class="fas fa-map me-2"></i> View Offices on Map
                    </a>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // No charts needed for metrics cards
    </script>
    @include('partials.scripts-reviews-save')
</body>
</html>