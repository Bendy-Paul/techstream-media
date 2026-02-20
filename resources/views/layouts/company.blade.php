<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Company Portal</title>

    @include('partials.links')

    <style>
        .company-sidebar {
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #e2e8f0;
        }
        .company-sidebar .nav-link {
            color: #64748b;
            padding: 1rem 1rem !important;
            margin: 1rem 1rem !important;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
        }
        .company-sidebar .nav-link:hover, 
        .company-sidebar .nav-link.active {
            color: var(--primary-color, #2563eb);
            background: #f8fafc;
            border-right: 3px solid var(--primary-color, #2563eb);
        }
        .company-sidebar .nav-link.pe-none {
            opacity: 0.6;
        }
        .company-content {
            padding: 2rem;
        }
    </style>
</head>
<body class="bg-light">

    @include('partials.navbar')

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 company-sidebar d-none d-md-block">
                <div class="py-4">
                    <div class="px-4 mb-4">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Company Menu</small>
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('company.dashboard') ? 'active' : '' }}" href="{{ route('company.dashboard') }}">
                            <i class="fas fa-th-large"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('company.profile.*') ? 'active' : '' }}" href="{{ route('company.profile.edit') }}">
                            <i class="fas fa-building"></i> Company Profile
                        </a>
                        
                        <div class="my-2 border-top"></div>
                        <small class="text-uppercase text-muted fw-bold px-4 mb-2 d-block" style="font-size: 0.75rem; letter-spacing: 1px;">Management</small>
                        
                        @php
                            $company = auth()->user()->company;
                            $isVerified = $company && $company->is_verified;
                        @endphp

                        @if($isVerified)
                            <a class="nav-link {{ request()->routeIs('company.jobs.*') ? 'active' : '' }}" href="{{ route('company.jobs.index') }}">
                                <i class="fas fa-briefcase"></i> Manage Jobs
                            </a>
                            <a class="nav-link {{ request()->routeIs('company.articles.*') ? 'active' : '' }}" href="{{ route('company.articles.index') }}">
                                <i class="fas fa-newspaper"></i> Manage Articles
                            </a>
                            <a class="nav-link {{ request()->routeIs('company.events.*') ? 'active' : '' }}" href="{{ route('company.events.index') }}">
                                <i class="fas fa-calendar-alt"></i> Manage Events
                            </a>
                        @else
                            <a class="nav-link text-muted pe-none" href="#" tabindex="-1" aria-disabled="true" title="Verification required. Please update your profile.">
                                <i class="fas fa-briefcase"></i> Manage Jobs <i class="fas fa-lock ms-auto text-danger"></i>
                            </a>
                            <a class="nav-link text-muted pe-none" href="#" tabindex="-1" aria-disabled="true" title="Verification required. Please update your profile.">
                                <i class="fas fa-newspaper"></i> Manage Articles <i class="fas fa-lock ms-auto text-danger"></i>
                            </a>
                            <a class="nav-link text-muted pe-none" href="#" tabindex="-1" aria-disabled="true" title="Verification required. Please update your profile.">
                                <i class="fas fa-calendar-alt"></i> Manage Events <i class="fas fa-lock ms-auto text-danger"></i>
                            </a>
                        @endif

                        <div class="my-2 border-top"></div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-4 px-4">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-9 col-lg-10 company-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    @include('partials.footer')

</body>
</html>
