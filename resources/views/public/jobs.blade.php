<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Careers | Find Your Next Opportunity</title>
    @include('partials.links')
    
    <!-- Include a modern font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Modern Search Form */
        .search-form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 3;
        }

        .search-input-group {
            background: white;
            border-radius: 16px;
            padding: 8px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .search-input-group:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .search-input-group .input-group-text {
            background: transparent;
            border: none;
            color: #64748b;
        }

        .search-input-group .form-control {
            border: none;
            padding-left: 0;
        }

        .search-input-group .form-control:focus {
            box-shadow: none;
        }

        /* Modern Job Cards */
        .job-card {
            background: white;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .job-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .job-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
            border-color: transparent;
        }

        .job-card:hover::before {
            opacity: 1;
        }

        .job-company-logo {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid #f1f5f9;
            flex-shrink: 0;
        }
        
        .job-company-logo img {
            width: 70%;
            height: 70%;
            object-fit: contain;
        }

        .job-type-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(99, 102, 241, 0.1);
            color: var(--accent-color);
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .salary-tag {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .skill-tag {
            background: rgba(99, 102, 241, 0.08);
            color: var(--accent-color);
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-right: 8px;
            margin-bottom: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.3s ease;
        }

        .skill-tag:hover {
            background: rgba(99, 102, 241, 0.15);
            transform: scale(1.05);
        }

        .apply-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        /* Modern Pagination */
        .pagination .page-link {
            border: none;
            margin: 0 4px;
            border-radius: 12px;
            color: #64748b;
            padding: 10px 18px;
            transition: all 0.3s ease;
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-gradient);
            color: white;
        }

        .pagination .page-link:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--accent-color);
        }

        /* Stats Section */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 3rem 0;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            text-align: center;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .search-form-container {
                padding: 20px;
            }
            
            .job-card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

    @include('partials.navbar')

    <!-- Modern Hero Section -->
    <section class="jobs-hero p-5" style="background: rgb(196 207 228)">
        <div class="container hero-content">
            <div class="row justify-content-center text-center">
            <div class="hero-content" data-aos="zoom-out" data-aos-duration="1200">
                <h1 class="hero-title">Find Your Dream Job</h1>
                <p class="hero-subtitle">Connect with innovative companies and shape the future of technology</p>

                <form class="search-container mx-auto row" action="{{ route('jobs') }}" method="GET">
                    <div class="d-flex align-items-center flex-grow-1 border-end border-secondary border-opacity-25 col-lg-3">
                        <i class="fas fa-search ms-3 text-muted"></i>
                        <input name="s" class="search-input" placeholder="Job title, skills, or company" value="{{ request('s') }}">
                    </div>
                    <div class="d-flex align-items-center flex-grow-1 border-end border-secondary border-opacity-25 d-none d-md-flex col-lg-3">
                        <i class="fas fa-layer-group ms-3 text-muted"></i>
                        <select name="type" class="search-input">
                            <option value="">All Types</option>
                            <option value="Full-time" {{ request('type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="Part-time" {{ request('type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="Contract" {{ request('type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Freelance" {{ request('type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                            <option value="Internship" {{ request('type') == 'Internship' ? 'selected' : '' }}>Internship</option>
                        </select>                    </div>
                    <div class="d-flex align-items-center flex-grow-1 d-none d-md-flex col-lg-3">
                        <i class="fas fa-map-marker-alt ms-3 text-muted"></i>
                        <select name="location" id="" class="search-input">
                            <option value="">All Locations</option>
                            <option value="Lagos" {{ request('location') == 'Lagos' ? 'selected' : '' }}>Lagos</option>
                            <option value="Abuja" {{ request('location') == 'Abuja' ? 'selected' : '' }}>Abuja</option>
                            <option value="Port Harcourt" {{ request('location') == 'Port Harcourt' ? 'selected' : '' }}>Port Harcourt</option>
                            <option value="Kano" {{ request('location') == 'Kano' ? 'selected' : '' }}>Kano</option>
                            <option value="Ibadan" {{ request('location') == 'Ibadan' ? 'selected' : '' }}>Ibadan</option>
                        </select>
                    </div>
                    <button type="submit" class="search-btn"><i class="fas fa-arrow-right col-lg-3"></i></button>
                </form>
            </div>
            </div>

                <!-- Quick Stats -->
    <section class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $jobs->total() }}+</div>
                <div class="text-muted">Active Positions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">50+</div>
                <div class="text-muted">Listed Companies</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">24h</div>
                <div class="text-muted">Average Response Time</div>
            </div>
        </div>
    </section>

        </div>
    </section>


    <!-- Job List -->
    <section class="container p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Latest Opportunities</h2>
                <p class="text-muted">{{ $jobs->total() }} positions matching your criteria</p>
            </div>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle px-4 py-2 rounded-pill" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-sort me-2"></i>Sort by: Best Match
                </button>
                <ul class="dropdown-menu shadow border-0 rounded-3">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-fire me-2"></i>Best Match</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-clock me-2"></i>Newest</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-money-bill-wave me-2"></i>Salary High to Low</a></li>
                </ul>
            </div>
        </div>

        <div class="row g-4">
            @foreach($jobs as $job)
            <div class="col-md-6 col-lg-4">
                <div class="job-card p-4">
                    @if($job->job_type)
                    <span class="job-type-badge">{{ $job->job_type }}</span><br><br>
                    @endif
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="job-company-logo me-3">
                            @if(optional($job->company)->logo_url)
                                <img src="{{ asset($job->company->logo_url) }}" alt="{{ optional($job->company)->name }}">
                            @else
                                <div class="fw-bold text-muted fs-4">{{ substr(optional($job->company)->name ?? 'C', 0, 1) }}</div>
                            @endif
                        </div>
                        
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">
                                <a href="{{ route('job.show', $job->slug) }}" class="text-dark text-decoration-none stretched-link">{{ $job->title }}</a>
                            </h5>
                            <div class="d-flex align-items-center">
                                <small class="text-muted">{{ optional($job->company)->name ?? 'Confidential' }}</small>
                                @if($job->is_remote)
                                <span class="badge bg-success bg-opacity-10 text-success ms-2">Remote</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="text-muted small d-flex align-items-center">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $job->is_remote ? 'Remote' : ($job->location ?? optional($job->company)->address ?? optional(optional($job->company)->city)->name ?? 'Anywhere') }}
                        </span>
                        @if($job->salary_range)
                        <span class="salary-tag">
                            <i class="fas fa-money-bill-wave me-1"></i>{{ $job->salary_range }}
                        </span>
                        @endif
                    </div>

                    <p class="text-muted mb-4" style="font-size: 0.9rem; line-height: 1.5;">
                        {{ Str::limit(strip_tags($job->description), 120) }}
                    </p>

                    <div class="mt-auto">
                        <div class="mb-3">
                            @foreach($job->tools->take(4) as $tool)
                                <span class="skill-tag">
                                    <i class="{{$tool->icon_class}} me-1"></i>{{ $tool->name }}
                                </span>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <small class="text-muted d-flex align-items-center">
                                <i class="fas fa-clock me-1"></i>{{ $job->created_at->diffForHumans() }}
                            </small>
                            <a href="{{ route('job.show', $job->slug) }}" class="apply-btn btn-sm">
                                Apply Now <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Modern Pagination -->
        <div class="mt-5 d-flex justify-content-center">
            {{ $jobs->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="p-5 rounded-4" style="background: var(--primary-gradient); color: white;">
                        <h2 class="fw-bold mb-3">Ready to Land Your Dream Job?</h2>
                        <p class="mb-4 opacity-90">Create your profile and let recruiters find you</p>
                        <a href="#" class="btn btn-light btn-lg px-5 rounded-pill fw-bold">
                            <i class="fas fa-user-plus me-2"></i>Create Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <script>
        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate job cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.job-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>