<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - User Dashboard</title>

    @include('partials.links')

    <style>
        .user-sidebar {
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #e2e8f0;
        }
        .user-sidebar .nav-link {
            color: #64748b;
            padding: 1rem 1rem !important;
            margin: 1rem 1rem !important;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
        }
        .user-sidebar .nav-link:hover, 
        .user-sidebar .nav-link.active {
            color: var(--primary-color, #2563eb);
            background: #f8fafc;
            border-right: 3px solid var(--primary-color, #2563eb);
        }
        .user-content {
            padding: 2rem;
        }
    </style>
</head>
<body class="bg-light">

    @include('partials.navbar')

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 user-sidebar d-none d-md-block">
                <div class="py-4">
                    <div class="px-4 mb-4">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Menu</small>
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                            <i class="fas fa-th-large"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('user.saved-items') ? 'active' : '' }}" href="{{ route('user.saved-items') }}">
                            <i class="fas fa-bookmark"></i> Saved Items
                        </a>
                        <a class="nav-link {{ request()->routeIs('user.resumes.*') ? 'active' : '' }}" href="{{ route('user.resumes.index') }}">
                            <i class="fas fa-file-alt"></i> Resumes
                        </a>
                        <a class="nav-link {{ request()->routeIs('user.applications.index') ? 'active' : '' }}" href="{{ route('user.applications.index') }}">
                            <i class="fas fa-briefcase"></i> Applications
                        </a>
                        <div class="my-2 border-top"></div>
                        <small class="text-uppercase text-muted fw-bold px-4 mb-2 d-block" style="font-size: 0.75rem; letter-spacing: 1px;">Organizer</small>
                        <a class="nav-link {{ request()->routeIs('user.organizer.*') ? 'active' : '' }}" href="{{ route('user.organizer.index') }}">
                            <i class="fas fa-calendar-check"></i> Manage Events
                        </a>
                        <div class="my-2 border-top"></div>
                        <a class="nav-link {{ request()->routeIs('user.settings') ? 'active' : '' }}" href="{{ route('user.settings') }}">
                            <i class="fas fa-cog"></i> Settings
                        </a>
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
            <div class="col-md-9 col-lg-10 user-content">
                @yield('content')
            </div>
        </div>
    </div>

    @include('partials.footer')

</body>
</html>
