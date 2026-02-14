<aside class="sidebar d-flex flex-column" id="sidebar">
    <!-- Logo Area -->
    <div class="p-4 border-bottom">
        <a href="index.php" class="text-decoration-none d-flex align-items-center gap-2">
            <i class="fas fa-hexagon text-primary fa-lg"></i>
            <span class="fw-bold text-dark h5 mb-0" style="font-family: 'Space Grotesk'">TECH<span class="text-primary">MEDIA</span></span>
        </a>
    </div>

    <!-- Navigation -->
    <div class="flex-grow-1 overflow-auto p-3 custom-scrollbar">
        
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>

        <div class="section-title">Directory</div>
        
        <a href="{{ route('admin.companies.index') }}" class="nav-link {{ request()->routeIs('admin.companies*') ? 'active' : '' }}">
            <i class="fas fa-building"></i> Companies
        </a>
        <a href="verification-requests.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'verification-requests.php' ? 'active' : ''; ?>">
            <i class="fas fa-check-circle"></i> Verification <span class="badge bg-danger rounded-pill ms-auto">3</span>
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Reviews
        </a>

        <div class="section-title">Content</div>
        
        <a href="{{ route('admin.articles.index') }}" class="nav-link {{ request()->routeIs('admin.articles*') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i> Articles / Updates
        </a>

        <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> Events
        </a>
        
        <a href="{{ route('admin.jobs.index') }}" class="nav-link {{ request()->routeIs('admin.jobs*') ? 'active' : '' }}">
            <i class="fas fa-briefcase"></i> My Jobs
        </a>

        <div class="section-title">Settings</div>
        
        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Categories
        </a>

        <a href="{{ route('admin.tools.index') }}" class="nav-link {{ request()->routeIs('admin.tools*') ? 'active' : '' }}">
            <i class="fas fa-layer-group"></i> Tech Tools
        </a>

        <a href="{{ route('admin.locations.index') }}" class="nav-link {{ request()->routeIs('admin.locations*') ? 'active' : '' }}">
            <i class="fas fa-map-marker-alt"></i> Locations
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Users
        </a>
        <a href="{{ route('admin.profile.index') }}" class="nav-link {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
            <i class="fas fa-user"></i> Profile
        </a>
    </div>

    <!-- User Profile Snippet at Bottom -->
    <div class="p-3 border-top bg-light">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                    <i class="fas fa-user"></i>
                </div>
                <div style="line-height: 1.2;">
                    <small class="d-block fw-bold text-dark"></small>
                    <small class="text-muted" style="font-size: 0.75rem;">Super Admin</small>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="text-danger btn" title="Logout"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </div>
</aside>