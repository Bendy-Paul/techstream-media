<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Tech Media Directory</title>
    @include('partials.links')

    <style>
    /* Hero Section */
        .about-hero {
            background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .about-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 15% 30%, rgba(37, 99, 235, 0.1) 0%, transparent 45%),
                radial-gradient(circle at 85% 70%, rgba(59, 130, 246, 0.05) 0%, transparent 45%);
            z-index: 1;
        }
        .about-hero-content {
            position: relative;
            z-index: 2;
        }

        /* Mission & Vision */
        .mission-card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(37, 99, 235, 0.1);
            height: 100%;
            transition: 0.3s;
        }
        .mission-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }
        .mission-icon {
            width: 70px;
            height: 70px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            color: var(--primary-accent);
            font-size: 1.8rem;
        }

        /* Team Section */
        .team-card {
            background: var(--bg-card);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(37, 99, 235, 0.1);
            transition: 0.3s;
        }
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }
        .team-img {
            height: 250px;
            background-color: #f1f5f9;
            width: 100%;
            object-fit: cover;
        }
        .team-body {
            padding: 25px;
        }
        .team-role {
            color: var(--primary-accent);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .social-links {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .social-link {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(37, 99, 235, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            text-decoration: none;
            transition: 0.3s;
        }
        .social-link:hover {
            background: var(--primary-accent);
            color: #fff;
        }


        /* Responsive */
        @media (max-width: 991px) {
            .about-hero {
                padding: 100px 0 60px;
            }
        }
    </style>
</head>
<body>
    @include('partials.navbar')
    <script>
        document.getElementById('about-us').classList.add('active');
        document.getElementById('about-us-responsive').classList.add('active');
    </script>


    <!-- Hero Section -->
    <section class="about-hero" data-aos="fade-up">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 about-hero-content" data-aos="fade-right" data-aos-delay="100">
                    <h5 class="text-primary-accent text-uppercase ls-2 fw-bold mb-3">About Us</h5>
                    <h1 class="display-4 fw-bold mb-4">Connecting Nigeria's Tech Ecosystem</h1>
                    <p class="lead text-muted mb-4">We're building the definitive platform that bridges the gap between innovators, investors, and opportunities in Africa's largest economy.</p>
                    <a href="#our-story" class="btn btn-primary btn-lg rounded-pill px-4 py-2">Our Story</a>
                </div>
                <div class="col-lg-6 text-center" data-aos="zoom-in" data-aos-delay="200">
                    <img src="<?= htmlspecialchars ('/assets/images/ab00.png') ?>?4" alt="Tech Community" class="img-fluid rounded-4 shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="section-padding" id="our-story" data-aos="fade-up" data-aos-delay="100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right" data-aos-delay="150">
                    <h5 class="text-primary-accent text-uppercase ls-2 fw-bold mb-3">Our Story</h5>
                    <h2 class="fw-bold mb-4">From Idea to Impact</h2>
                    <p class="text-muted mb-4">Founded in 2022, Tech Media Directory emerged from a simple observation: Nigeria's booming tech scene lacked a centralized platform to connect its diverse players.</p>
                    <p class="text-muted mb-4">What started as a small directory of Lagos-based startups has grown into the nation's most comprehensive tech ecosystem map, featuring thousands of companies, events, and opportunities.</p>
                    <p class="text-muted mb-4">Today, we're proud to serve as the bridge between established corporations, innovative startups, talented professionals, and forward-thinking investors.</p>
                    <a href="#" class="btn btn-outline-primary rounded-pill px-4 py-2">Join Our Journey</a>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mission-card" data-aos="fade-up" data-aos-delay="150">
                                <div class="mission-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <h4 class="mb-3">Our Mission</h4>
                                <p class="text-muted">To accelerate Nigeria's digital transformation by connecting every player in the tech ecosystem through a unified, accessible platform.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mission-card" data-aos="fade-up" data-aos-delay="250">
                                <div class="mission-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <h4 class="mb-3">Our Vision</h4>
                                <p class="text-muted">To become Africa's most trusted tech ecosystem directory, driving innovation, investment, and collaboration across the continent.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <!-- <section class="section-padding" style="background-color: #f8fafc;" data-aos="fade-up" data-aos-delay="100">
        <div class="container">
            <div class="text-center mb-5" data-aos="zoom-in" data-aos-delay="150">
                <h5 class="text-primary-accent text-uppercase">Our Team</h5>
                <h2 class="fw-bold">Meet The Minds Behind The Mission</h2>
                <p class="text-muted">A diverse team passionate about technology and Nigeria's potential</p>
            </div>
            <div class="row g-4 justify-content-center">
                memeber
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="team-card" data-aos="fade-up" data-aos-delay="200">
                        <img src="https://placehold.co/400x300/f1f5f9/2563eb?text=Team+Member" class="team-img" alt="Team Member">
                        <div class="team-body">
                            <h4 class="fw-bold mb-1">Adebayo Adekunle</h4>
                            <div class="team-role">CEO & Founder</div>
                            <p class="text-muted small">Former tech journalist with 10+ years covering Africa's digital transformation.</p>
                            <div class="social-links">
                                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Stats Section -->
    <section class="section-padding stats-section" data-aos="fade-up" data-aos-delay="100">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4" data-aos="flip-up" data-aos-delay="150">
                    <div class="stats-number">100+</div>
                    <div class="stats-label">Listed Companies</div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="flip-up" data-aos-delay="250">
                    <div class="stats-number">50+</div>
                    <div class="stats-label">Upcoming Events</div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="flip-up" data-aos-delay="350">
                    <div class="stats-number">500+</div>
                    <div class="stats-label">Monthly Users</div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="flip-up" data-aos-delay="450">
                    <div class="stats-number">36</div>
                    <div class="stats-label">States Covered</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding" data-aos="fade-up" data-aos-delay="150">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8" data-aos="zoom-in" data-aos-delay="200">
                    <h2 class="fw-bold mb-4">Ready to be part of Nigeria's tech revolution?</h2>
                    <p class="text-muted mb-4">Join thousands of companies already connecting with opportunities through our platform.</p>
                    <a href="get-listed.php" class="btn btn-primary btn-lg rounded-pill px-5 py-3">Get Listed Today</a>
                </div>
            </div>
        </div>
    </section>

@include('partials.footer')
</body>
</html>