<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->name }} | Company Profile</title>
    @include('partials.links')


    <style>
        :root {
            --bg-light: #f8fafc;
            --bg-card: #ffffff;
            --primary-accent: #2563eb;
            --secondary-accent: #3b82f6;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            background-color: var(--bg-light);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Grotesk', sans-serif;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 120px 0 120px;
            color: white;
            border-bottom: 3px solid var(--primary-accent);
        }
        
        .company-logo {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--primary-accent);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .company-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
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
        
        .ad-container {
            background: #f1f5f9;
            border: 1px dashed #94a3b8;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 20px 0;
        }
        
        .category-badge {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-accent);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-right: 8px;
            margin-bottom: 8px;
            display: inline-block;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    @include('partials.navbar')

{{-- Company Header --}}
<section class="profile-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2 text-center text-md-start">
                <div class="company-logo" data-aos="zoom-in">
                    @if(!empty($company->logo_url))
                        <img src="{{ asset($company->logo_url) }}" alt="{{ $company->name }}">
                    @else
                        <i class="fas fa-code"></i>
                    @endif
                </div>
            </div>

            <div class="col-md-7" data-aos="fade-right">
                <h1 class="fw-bold mb-2">{{ $company->name }}</h1>
                <p class="lead mb-3 text-white">{{ $company->tagline }}</p>

                <div class="d-flex flex-wrap">
                    @foreach($categories as $category)
                        <span class="category-badge">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>

            <div class="col-md-3 text-center text-md-end" data-aos="fade-left">
                @if(!empty($company->website_url))
                    <a href="{{ $company->website_url }}" target="_blank" class="btn btn-outline-primary mb-2">
                        Visit Website
                    </a>
                @endif

                <p class="small">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    {{ $company->city_name }}, Nigeria
                </p>
            </div>
        </div>
    </div>
</section>

<div class="container my-5">
    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">

            {{-- About Company --}}
            <div class="info-card mb-4" data-aos="fade-up">
                <h3 class="fw-bold mb-4">About Our Company</h3>
                <div class="article-content">
                    <p>{!! ($company->description) !!}</p>
                </div>
            </div>

            {{-- Projects --}}
            @if($projects->count())
                <div class="info-card mb-4" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="fw-bold mb-4">Projects</h3>
                    <div class="row">
                        @foreach($projects as $project)
                            <div class="col-md-6 mb-4">
                                <div class="p-3 border rounded h-100">
                                    <h5 class="fw-bold">{{ $project->title }}</h5>
                                    <p class="text-muted small">{{ $project->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Reviews --}}
            <div class="info-card mb-4" data-aos="fade-up">
                @include('partials.reviews', ['item' => $company])
            </div>

            {{-- Ad --}}
            <div class="ad-container mb-4" style="position: sticky; top: 80px;">
                <h5 class="text-muted">Advertisement</h5>
                <p class="text-muted">Looking for mobile app development?</p>
                <a href="#" class="btn btn-sm btn-outline-primary">Check Out AppCraft Studios</a>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">

            {{-- Save Company --}}
            <div class="info-card mb-4 text-center">
                @include('partials.save-button', ['item' => $company])
                <p class="small text-muted mt-2">Save this company to your dashboard</p>
            </div>

            {{-- Contact Details --}}
            <div class="info-card mb-4" data-aos="fade-left">
                <h4 class="fw-bold mb-4">Contact Details</h4>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Website</h6>
                    <a href="{{ $company->website_url }}" target="_blank">
                        {{ $company->website_url }}
                    </a>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Phone</h6>
                    <p>{{ $company->phone }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Email</h6>
                    <p>{{ $company->email }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Team Size</h6>
                    <p>{{ $company->team_size ?? 'N/A' }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Year Started</h6>
                    <p>{{ $company->year_founded ?? 'N/A' }}</p>
                </div>
            </div>

            {{-- Profile Completeness --}}
            @php
                $completeness = 50;
                if($company->logo_url) $completeness += 10;
                if($company->description) $completeness += 10;
                if($company->phone) $completeness += 10;
                if($company->email) $completeness += 10;
                if($projects->count()) $completeness += 10;
            @endphp

            <div class="info-card mb-4">
                <h3 class="fw-bold mb-4">Profile Completeness</h3>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-success"
                         style="width: {{ $completeness }}%">
                        {{ $completeness }}%
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">
                    Complete your profile to gain more trust.
                </small>
            </div>

            {{-- Location --}}
            <div class="info-card mb-4">
                <h4 class="fw-bold mb-4">Location</h4>
                <div class="bg-light rounded-3 p-3">
                    <p><i class="fas fa-map-marker-alt text-primary me-2"></i>
                        {!! nl2br(e($company->address)) !!}
                    </p>
                    <p>{{ $company->city_name }}</p>
                    <p>Nigeria</p>
                </div>
            </div>

            {{-- Ad --}}
            <div class="ad-container">
                <h5 class="text-muted">Advertisement</h5>
                <p class="text-muted">Need digital marketing services?</p>
                <a href="#" class="btn btn-sm btn-outline-primary">Visit Digital Growth NG</a>
            </div>

        </div>
    </div>
</div>

    <!-- Footer -->
    @include('partials.scripts-reviews-save')


</body>
</html>