<!-- Modern Header -->
<div class="navbar-container no-print">
    <div class="container">
        <nav class="navbar navbar-expand-lg" style="height: 6em;">
            <div class="col-2 d-flex align-items-center">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('assets/images/techstream-logo.png') }}" class="img-fluid" alt="TechMedia Logo">
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="collapse navbar-collapse desktop-nav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" id="home" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" id="about-us" href="{{ url('/about') }}">About</a></li>

                    <!-- States Dropdown (Full Width) -->
                    <li class="nav-item dropdown position-static dropdown-hover">
                        <a class="nav-link {{ request()->is('states*', 'all-states') ? 'active' : '' }}" id="states" href="#" aria-haspopup="true" aria-expanded="false">
                            States
                            <i class="fas fa-chevron-down ms-1 dropdown-arrow" style="font-size: 0.8rem;"></i>
                        </a>
                        <div class="dropdown-menu mega-dropdown" style="width:100%;min-width:600px;">
                            <div class="dropdown-animation-wrapper">
                                <div class="container">
                                    <div class="row p-4">
                                        <div class="row col-md-12">
                                            @foreach($headerStates as $state)
                                            <div class="col-md-3 mb-3">
                                                <a class="dropdown-item d-flex justify-content-between align-items-center state-item {{ request()->is('states/' . $state->slug) ? 'active' : '' }}" href="{{ url('/states/' . $state->slug) }}">
                                                    <span>{{ $state->name }}</span>
                                                    <span class="badge bg-light text-dark badge-pill">{{ $state->company_count }}</span>
                                                </a>
                                            </div>
                                            @endforeach
                                            <div class="col-md-3 mb-3">
                                                <a class="dropdown-item btn btn-outline-primary w-100 fw-bold view-all-btn {{ request()->routeIs('all-states') ? 'active' : '' }}" href="{{ url('/all-states') }}">View All States</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Categories Dropdown (Full Width) -->
                    <li class="nav-item dropdown position-static dropdown-hover">
                        <a class="nav-link {{ request()->is('categories*') ? 'active' : '' }}" id="categories" href="#" aria-haspopup="true" aria-expanded="false">
                            Services
                            <i class="fas fa-chevron-down ms-1 dropdown-arrow" style="font-size: 0.8rem;"></i>
                        </a>
                        <div class="dropdown-menu mega-dropdown">
                            <div class="dropdown-animation-wrapper">
                                <div class="container">
                                    <div class="row p-4">
                                        <div class="row col-md-12">
                                            @foreach($parentCategories as $parent)
                                            <div class="col-md-3 mb-4">
                                                <h6 class="category-heading mb-2 text-uppercase fw-bold">
                                                    <i class="{{ $parent->icon_class }} me-2"></i>{{ $parent->name }}
                                                </h6>
                                                @if(isset($parent->children) && $parent->children->isNotEmpty())
                                                @foreach($parent->children as $child)
                                                <a class="dropdown-item category-item {{ request()->is('categories/' . $child->slug) ? 'active' : '' }}" href="{{ url('/categories/' . $child->slug) }}">
                                                    {{ $child->name }}
                                                </a>
                                                @endforeach
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Events Dropdown (Full Width) -->
                    <li class="nav-item dropdown position-static dropdown-hover">
                        <a class="nav-link {{ request()->is('events*', 'event/*') ? 'active' : '' }}" id="events" href="#" aria-haspopup="true" aria-expanded="false">
                            Events
                            <i class="fas fa-chevron-down ms-1 dropdown-arrow" style="font-size: 0.8rem;"></i>
                        </a>
                        <div class="dropdown-menu mega-dropdown">
                            <div class="dropdown-animation-wrapper">
                                <div class="container">
                                    <div class="row p-4">
                                        <h6 class="category-heading mb-2 text-uppercase fw-bold">
                                            <i class="fa-solid fa-calendar-days me-2"></i>Events
                                        </h6>
                                        <div class="row col-md-10">
                                            @foreach($eventCategories as $event)
                                            <div class="col-md-3 mb-3">
                                                <a class="dropdown-item d-flex justify-content-between align-items-center state-item {{ request()->is('events/' . $event->slug) ? 'active' : '' }}" href="{{ url('/events/' . $event->slug) }}">
                                                    <span> <i class="{{ $event->icon_class }} me-2"></i>{{ $event->name }}</span>
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="col-md-2 border-start ps-4 d-none d-md-block">
                                            <div class="text-center p-2">
                                                <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                                                <p class="small text-muted">Find networking opportunities and industry events.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item"><a class="nav-link {{ request()->is('news*', 'article/*') ? 'active' : '' }}" id="news" href="{{ url('/news') }}">Updates</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('jobs*') ? 'active' : '' }}" id="jobs" href="{{ url('/jobs') }}">Jobs</a></li>
                </ul>
                <div class="d-flex mt-3 mt-lg-0">
                    @if(Auth::check())
                        @if(Auth::user()->role == 'user')
                            <a href="{{ url('/user/dashboard') }}"><button class="btn-get-listed">Dashboard</button></a>
                        @elseif(Auth::user()->role == 'admin')
                            <a href="{{ url('/admin/dashboard') }}"><button class="btn-get-listed">Admin Panel</button></a>
                        @elseif(Auth::user()->role == 'Company')
                            <a href="{{ url('/Company/dashboard') }}"><button class="btn-get-listed">Company Panel</button></a>
                        @endif
                    @else
                    <a href="{{ url('/login') }}"><button class="btn-get-listed">login</button></a>
                    @endif
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle navbar-toggler border-0" type="button">
                <i class="fas fa-bars text-primary-accent" style="font-size: 1.5rem;"></i>
            </button>
        </nav>
    </div>
</div>

<style>
    /* Enhanced Dropdown Styling */
    .dropdown-hover {
        position: static;
    }

    .mega-dropdown {
        left: 0 !important;
        right: 0 !important;
        margin-top: 0 !important;
        padding: 0 !important;
        border: none !important;
        border-radius: 0 0 20px 20px !important;
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        box-shadow:
            0 20px 40px rgba(0, 0, 0, 0.1),
            0 8px 24px rgba(0, 0, 0, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.2) !important;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        max-height: 0;
        overflow: hidden;
    }

    /* Create invisible overlay to prevent hover gap */
    .dropdown-hover::after {
        content: '';
        position: absolute;
        top: 10%;
        left: 0;
        width: 100%;
        height: 100px;
        /* Increased gap bridge */
        background: transparent;
        z-index: 1000;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .dropdown-hover:hover::after {
        opacity: 1;
    }

    .dropdown-hover:hover .mega-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        max-height: 600px;
        overflow: visible;
        z-index: 1050;
    }

    /* Dropdown content animation wrapper */
    .dropdown-animation-wrapper {
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease 0.1s;
    }

    .dropdown-hover:hover .dropdown-animation-wrapper {
        opacity: 1;
        transform: translateY(0);
    }

    /* Enhanced nav link hover effect */
    .nav-link {
        position: relative;
        padding: 2.3rem 1rem !important;
        transition: all 0.3s ease;
        border-radius: 8px;
        margin: 0 0.25rem;
    }

    .nav-link:hover {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
        transform: translateY(-2px);
    }


    .dropdown-arrow {
        transition: transform 0.3s ease;
    }

    .dropdown-hover:hover .dropdown-arrow {
        transform: rotate(180deg);
    }

    /* Enhanced dropdown items */
    .state-item,
    header .category-item {
        padding: 0.75rem 1rem !important;
        border-radius: 10px !important;
        margin: 0.25rem 0;
        transition: all 0.2s ease !important;
        border-left: 3px solid transparent !important;
        position: relative;
        overflow: hidden;
    }

    .state-item:hover,
    .category-item:hover {
        background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%) !important;
        transform: translateX(8px) !important;
        border-left: 3px solid #2563eb !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1) !important;
    }

    .state-item:hover .badge {
        background: #2563eb !important;
        color: white !important;
        transform: scale(1.1);
    }

    .badge-pill {
        transition: all 0.2s ease;
    }

    /* Category headings */
    .category-heading {
        color: #1e40af;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(37, 99, 235, 0.2);
    }

    /* View all button */
    .view-all-btn {
        padding: 0.75rem 1.5rem !important;
        border-radius: 12px !important;
        border-width: 2px !important;
        transition: all 0.3s ease !important;
        margin-top: 1rem;
    }

    .view-all-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2) !important;
        background: #2563eb !important;
        color: white !important;
    }

    /* Dropdown hover delay for better UX */
    .dropdown-hover .mega-dropdown {
        transition-delay: 0.1s;
    }

    .dropdown-hover:hover .mega-dropdown {
        transition-delay: 0s;
    }

    /* Active state for nav items */
    .nav-link.active {
        color: #2563eb !important;
        font-weight: 600;
    }


    /* Dropdown on hover for desktop - Enhanced */
    @media (min-width: 992px) {
        .dropdown-hover:hover>.mega-dropdown {
            display: block;
            animation: slideDown 0.3s ease forwards;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }

    /* Get Listed Button Enhancement */
    .btn-get-listed {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.75rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .btn-get-listed:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35);
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    }

    /* Logo hover effect */
    .navbar-brand img {
        transition: transform 0.3s ease;
    }

    .navbar-brand:hover img {
        transform: scale(1.05);
    }

    /* Mobile menu toggle */
    .mobile-menu-toggle {
        transition: all 0.3s ease;
        padding: 0.5rem;
        border-radius: 8px;
    }

    .mobile-menu-toggle:hover {
        background: rgba(37, 99, 235, 0.1);
    }

    /* Mobile dropdown content */
    .mobile-dropdown-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .mobile-dropdown.active .mobile-dropdown-content {
        max-height: 500px;
    }
</style>

<script>
    // Enhanced dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Handle dropdown hover with better timing
        const dropdowns = document.querySelectorAll('.dropdown-hover');

        dropdowns.forEach(dropdown => {
            let hoverTimer;
            let leaveTimer;

            dropdown.addEventListener('mouseenter', function() {
                clearTimeout(leaveTimer);
                hoverTimer = setTimeout(() => {
                    this.classList.add('show');
                    const megaDropdown = this.querySelector('.mega-dropdown');
                    if (megaDropdown) {
                        megaDropdown.classList.add('show');
                    }
                }, 150); // Reduced delay for better responsiveness
            });

            dropdown.addEventListener('mouseleave', function() {
                clearTimeout(hoverTimer);
                leaveTimer = setTimeout(() => {
                    this.classList.remove('show');
                    const megaDropdown = this.querySelector('.mega-dropdown');
                    if (megaDropdown) {
                        megaDropdown.classList.remove('show');
                    }
                }, 200); // Slight delay to allow moving to dropdown
            });
        });

        // Mobile dropdown toggle
        const mobileDropdowns = document.querySelectorAll('.mobile-dropdown > a');
        mobileDropdowns.forEach(item => {
            item.addEventListener('click', function(e) {
                if (window.innerWidth < 992) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    parent.classList.toggle('active');

                    // Close other dropdowns
                    mobileDropdowns.forEach(otherItem => {
                        if (otherItem !== this) {
                            otherItem.parentElement.classList.remove('active');
                        }
                    });
                }
            });
        });

        // Add active class to current page nav item
        const currentPage = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');

        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPage ||
                (link.getAttribute('href') !== '/' && currentPage.includes(link.getAttribute('href')))) {
                link.classList.add('active');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>