<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Media Directory | Nigeria's Tech Ecosystem</title>
    @include('partials.links')
<style>

        /* Map Hover Effects */
        .nigeria-svg path { transition: fill 0.3s ease; cursor: pointer; }
        .nigeria-svg path:hover { fill: #afa5a5 !important; }
    </style>

    @foreach($mapStats as $state)
        @php
            $count = $state->company_count;
            $colorVar = 'var(--primary-accent-0)'; // Default
            
            if($count >= $highestCount) { $colorVar = 'var(--primary-accent)'; }
            elseif ($count >= $highestCount * 0.5) { $colorVar = 'var(--primary-accent-75)'; }
            elseif ($count >= $highestCount * 0.35) { $colorVar = 'var(--primary-accent-50)'; }
            elseif ($count >= $highestCount * 0.25) { $colorVar = 'var(--primary-accent-25)'; }
        @endphp

        <style>
            #NG-{{ strtoupper(substr($state->state_code, 0, 2)) }} {
                fill: {{ $colorVar }};
            }
        </style>
    @endforeach
</head>

<body>
    @include('partials.navbar')

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-bg"></div>
            <div class="grid-overlay"></div>

            <div class="hero-content" data-aos="zoom-out" data-aos-duration="1200">
                <h1 class="hero-title">Discover Nigeria's <br><span class="text-primary-accent">Tech Frontier</span></h1>
                <p class="hero-subtitle">The definitive directory for innovators, startups, and tech events reshaping Africa's largest economy.</p>

                <form class="search-container mx-auto row" action="search-results" method="GET">
                    <div class="d-flex align-items-center flex-grow-1 border-end border-secondary border-opacity-25 col-lg-3">
                        <i class="fas fa-search ms-3 text-muted"></i>
                        <input type="text" name="s" class="search-input" placeholder="Search companies or events...">
                    </div>
                    <div class="d-flex align-items-center flex-grow-1 border-end border-secondary border-opacity-25 d-none d-md-flex col-lg-3">
                        <i class="fas fa-layer-group ms-3 text-muted"></i>
                        <input type="text" name="type" class="search-input" placeholder="Service (e.g. Fintech)...">
                    </div>
                    <div class="d-flex align-items-center flex-grow-1 d-none d-md-flex col-lg-3">
                        <i class="fas fa-map-marker-alt ms-3 text-muted"></i>
                        <input type="text" class="search-input" placeholder="Location...">
                    </div>
                    <button type="submit" class="search-btn"><i class="fas fa-arrow-right col-lg-3"></i></button>
                </form>

                <div class="mt-4 text-muted small">
                    <span class="me-2">Trending:</span>
                    <span class="badge bg-light border border-secondary text-secondary me-1">Fintech</span>
                    <span class="badge bg-light border border-secondary text-secondary me-1">AI</span>
                    <span class="badge bg-light border border-secondary text-secondary">AgriTech</span>
                </div>
            </div>
        </section>

        <!-- Interactive Map Section -->
        <section class="section-padding map-section">
            <div class="container">
                <div class="text-center mb-5" data-aos="fade-down">
                    <h2 class="fw-bold">Tech Hubs Hotspots</h2>
                    <p class="text-muted">Interactive density map of registered companies across Nigeria</p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10 position-relative">
                        <div id="map-tooltip" class="map-tooltip"></div>
                        <!-- Simplified Stylized SVG of Nigeria -->

                        <div style="margin: auto; display: flex; justify-content: center;">
                            @include('public.nigeria-map-svg')
                        </div>


                        <div class="text-end mt-2">
                            <small class="text-primary-accent"><i class="fas fa-circle"></i> High Density</small>
                            <small class="text-muted ms-3"><i class="fas fa-circle"></i> Low Density</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

            <!-- Four Pillar Services -->
        <section class="section-padding">
            <div class="container">
                <div class="text-center mb-5">
                    <h5 class="text-primary-accent text-uppercase">Our Categories</h5>
                    <h2 class="fw-bold">Explore the Ecosystem</h2>
                </div>
                <div class="row g-4">
                    <!-- Pillar 1 -->
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="pillar-card">
                            <div class="pillar-icon">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h4 class="mb-3">Software &<br>Development</h4>
                            <p class="text-muted small">The backbone of innovation. Find developers, SaaS products, and app agencies.</p>
                            <ul class="sub-services d-block mt-3">
                                <li>Web Development</li>
                                <li>Mobile Applications</li>
                                <li>Enterprise Software</li>
                            </ul>
                        </div>
                    </div>
                    <!-- Pillar 2 -->
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="pillar-card">
                            <div class="pillar-icon">
                                <i class="fas fa-microchip"></i>
                            </div>
                            <h4 class="mb-3">Hardware &<br>Infrastructure</h4>
                            <p class="text-muted small">Physical tech driving the nation. ISPs, Robotics, and IoT solutions.</p>
                            <ul class="sub-services d-block mt-3">
                                <li>Networking</li>
                                <li>Consumer Electronics</li>
                                <li>Robotics & IoT</li>
                            </ul>
                        </div>
                    </div>
                    <!-- Pillar 3 -->
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="pillar-card">
                            <div class="pillar-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h4 class="mb-3">Data Science &<br>AI Solutions</h4>
                            <p class="text-muted small">The future of intelligence. Big data firms, AI research, and analytics.</p>
                            <ul class="sub-services d-block mt-3">
                                <li>Machine Learning</li>
                                <li>Data Analysis</li>
                                <li>Business Intelligence</li>
                            </ul>
                        </div>
                    </div>
                    <!-- Pillar 4 -->
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="pillar-card">
                            <div class="pillar-icon">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <h4 class="mb-3">Digital Marketing<br>& Branding</h4>
                            <p class="text-muted small">Growth engines. SEO, Content marketing, and digital strategy agencies.</p>
                            <ul class="sub-services d-block mt-3">
                                <li>SEO & SEM</li>
                                <li>Social Media Mgmt</li>
                                <li>Brand Strategy</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    <section class="section-padding bg-white py-5">
        <div class="container">
            <h2 class="fw-bold mb-4">Recently Listed</h2>
            
            <div class="owl-carousel owl-theme" id="listings-carousel">
                @foreach($recentListings as $comp)
                    <div class="item">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body text-center">
                                @if($comp->logo)
                                    <img src="{{ asset($comp->logo) }}" alt="{{ $comp->name }}" style="height: 80px; object-fit: contain;" class="mb-3">
                                @else
                                    <div class="mb-3"><i class="fas fa-building fa-3x text-muted"></i></div>
                                @endif
                                <h5 class="fw-bold">{{ $comp->name }}</h5>
                                <p class="text-muted small"><i class="fas fa-map-marker-alt"></i> {{ $comp->city_name ?? 'Nigeria' }}</p>
                                <a href="/company/{{ $comp->slug }}" class="btn btn-outline-primary btn-sm">View Profile</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding py-5">
        <div class="container">
            <h2 class="fw-bold mb-5 text-center">Tech Insights</h2>
            <div class="row">
                @if($featuredNews)
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 bg-light">
                        <img src="{{ $featuredNews->image_url ?? 'https://placehold.co/600x400' }}" class="card-img-top rounded-4">
                        <div class="card-body px-0">
                            <span class="badge bg-primary mb-2">FEATURED</span>
                            <h3>{{ $featuredNews->title }}</h3>
                            <p class="text-muted">{{ Str::limit(strip_tags($featuredNews->content), 100) }}</p>
                            <a href="#" class="fw-bold text-decoration-none">Read Article &rarr;</a>
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-lg-6">
                    @foreach($regularNews as $post)
                        @if($featuredNews && $post->id === $featuredNews->id) @continue @endif
                        <div class="d-flex mb-4 align-items-center">
                            <img src="{{ $post->image_url ?? 'https://placehold.co/100' }}" class="rounded me-3" style="width:80px; height:80px; object-fit:cover;">
                            <div>
                                <small class="text-muted">{{ $post->created_at->format('M d, Y') }}</small>
                                <h6 class="fw-bold mb-0"><a href="article/{{$post->slug}}" class="text-dark text-decoration-none">{{ $post->title }}</a></h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    
        <!-- FAQ Section -->
        <section class="section-padding bg-glass">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h2 class="text-center mb-5 fw-bold">Frequently Asked Questions</h2>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                        How do I list my company?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted ps-4">
                                        Click the "Get Listed" button in the navigation bar. Choose a subscription tier (Free, Silver, or Gold), fill out your company details, and verify your email.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                        Is it free to browse events?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted ps-4">
                                        Yes! Users can browse companies, events, and articles completely for free. Some premium events may require paid registration through the organizer.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                        How does the ad placement work?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted ps-4">
                                        We offer strategic banner placements and "Featured" listings. Contact our sales team via the Contact Us page for a media kit.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- CTA Section -->
        <section class="py-5 text-center">
            <div class="container">
                <div class="p-5 rounded-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent));">
                    <!-- Decorative Circles -->
                    <div class="position-absolute top-0 start-0 translate-middle rounded-circle bg-white opacity-10" style="width: 200px; height: 200px;"></div>
                    <div class="position-absolute bottom-0 end-0 translate-middle rounded-circle bg-white opacity-10" style="width: 300px; height: 300px;"></div>

                    <div class="position-relative z-1">
                        <h2 class="fw-bold mb-3 text-white">Ready to boost your visibility?</h2>
                        <p class="mb-4 text-white-50 fs-5">Join thousands of tech companies connecting with clients today.</p>
                        <button class="btn btn-light btn-lg rounded-pill fw-bold text-primary shadow-lg px-5">Get Listed Now</button>
                        <button class="btn btn-outline-light btn-lg rounded-pill ms-2 px-4">Contact Sales</button>
                    </div>
                </div>
            </div>
        </section>
        
@include('partials.footer')
    <script>
        $(document).ready(function() {
            // 1. Number Counter Animation (for Stats)
            let counted = false;
            $(window).scroll(function() {
                var oTop = $('#about').offset().top - window.innerHeight;
                if (counted == false && $(window).scrollTop() > oTop) {
                    $('.stats-number').each(function() {
                        var $this = $(this),
                            countTo = $this.attr('data-target');
                        $({
                            countNum: $this.text()
                        }).animate({
                            countNum: countTo
                        }, {
                            duration: 2500,
                            easing: 'swing',
                            step: function() {
                                $this.text(Math.floor(this.countNum));
                            },
                            complete: function() {
                                $this.text(this.countNum);
                                if (countTo > 1000) $this.text(Math.floor(this.countNum / 1000) + 'K+');
                            }
                        });
                    });
                    counted = true;
                }
            });

            // 4. Map Tooltip Logic
            $('.state-path').hover(function(e) {
                const name = $(this).data('name');
                const count = $(this).data('count');
                const tooltip = $('#map-tooltip');

                tooltip.html(`<strong>${name}</strong><br><span class="text-primary-accent">${count}</span>`).css('opacity', 1);
            }, function() {
                $('#map-tooltip').css('opacity', 0);
            });

            $('.state-path').mousemove(function(e) {
                $('#map-tooltip').css({
                    left: e.pageX + 15,
                    top: e.pageY + 15
                });
            });

            // 5. Owl Carousel Initialization
            $('#listings-carousel').owlCarousel({
                loop: true,
                margin: 25,
                nav: false,
                dots: true,
                autoplay: true,
                autoplayHoverPause: true,
                autoplayTimeout: 4000,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    1000: {
                        items: 4
                    }
                }
            });

        });
    </script>
</body>
</html>