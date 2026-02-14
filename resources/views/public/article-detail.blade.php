<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$article->title}} | Tech Media Directory</title>
    @include('partials.links')

    <style>
        /* Article Hero */
        .article-hero {
            position: relative;
            width: 100%;
            height: 26rem;
            display: flex;
            align-items: center;
            /* Vertically center text */
            justify-content: center;
            /* Horizontally center text */
            overflow: hidden;
            /* Crucial: hides the messy edges caused by the blur */
        }

        /* The Blurred Image Layer */
        .article-hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            /* Replace with your image URL */
            background-size: cover;
            background-position: center;

            /* 1. The Blur Effect */
            filter: blur(15px);

            /* 2. Scale Fix: When you blur, edges get white/faded. 
               Scaling up slightly pushes those bad edges off-screen. */
            transform: scale(1.1);

            z-index: -2;
            /* Places it at the very back */
        }

        /* The Dark Overlay Layer */
        .article-hero::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            /* Black overlay with 40% opacity */
            background-color: rgba(0, 0, 0, 0.4);

            z-index: -1;
            /* Places it above the image but behind the text */
        }

        .article-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            color: white;
            flex-wrap: wrap;
        }

        .article-category {
            background: rgba(37, 99, 235, 0.1);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }

        .article-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 25px 0;
        }

        .article-tag {
            background: rgba(37, 99, 235, 0.05);
            color: var(--text-muted);
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            text-decoration: none;
            transition: 0.3s;
        }

        .article-tag:hover {
            background: var(--primary-accent);
            color: white;
        }

        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
        }

        /* Ensure images inside content are responsive */
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            /* margin: 20px 0; */
        }

        .article-content h2 {
            margin-top: 40px;
            margin-bottom: 20px;
            color: var(--text-main);
            font-weight: 700;
        }

        .article-content p {
            margin-bottom: 20px;
        }

        .article-content ul,
        .article-content ol {
            margin-bottom: 20px;
            padding-left: 20px;
        }

        .article-content li {
            margin-bottom: 10px;
        }

        .article-image-featured {
            border-radius: 16px;
            overflow: hidden;
            /* margin: 30px 0; */
            box-shadow: var(--shadow-light);
        }

        .author-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(37, 99, 235, 0.1);
            margin: 40px 0;
        }

        .author-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary-accent);
            margin-right: 20px;
            overflow: hidden;
        }

        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Sidebar */
        .sidebar-widget {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(37, 99, 235, 0.1);
            margin-bottom: 30px;
        }

        .related-article {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            text-decoration: none;
            color: inherit;
            transition: 0.3s;
        }

        .related-article:hover h6 {
            color: var(--primary-accent);
        }

        .related-article:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .related-img {
            width: 80px;
            height: 60px;
            border-radius: 8px;
            background: #f1f5f9;
            margin-right: 15px;
            flex-shrink: 0;
            object-fit: cover;
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

        /* 1. Base Table Styling */
        table {
            border-collapse: collapse;
            /* Removes space between borders */
            margin: 25px 0;
            /* Adds spacing around the table */
            font-size: 0.9em;
            /* Standard table font size */
            font-family: sans-serif;
            /* Clean font */
            min-width: 400px;
            /* Ensures the table doesn't look too squashed */
            width: 100%;
        }

        /* 2. Header Styling */
        table thead tr {
            background-color: #009879;
            /* Change this to your brand color */
            color: #ffffff;
            text-align: left;
        }

        /* 3. Cell Styling (Header & Body) */
        table th,
        table td {
            padding: 12px 15px;
            /* Adds breathing room inside cells */
        }

        /* 4. Row Styling & Borders */
        table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        /* 5. Zebra Striping (Alternating Row Colors) */
        table tbody tr:nth-of-type(even) {
            background-color: #e8edf8c7;
        }

        /* 6. Active Row / Last Row Styling */
        table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
            /* Matches header color */
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
            /* Highlight row on mouse over */
            cursor: pointer;
            /* Optional: indicates interactivity */
        }

        @media (max-width: 768px) {
            .article-hero {
                padding: 80px 0 30px;
            }
        }
    </style>
</head>

<body>
@include('partials.navbar')
    <!-- Article Hero -->
    <section class="article-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <span class="article-category">Tech News</span>
                    <h1 class="display-5 fw-bold mb-4 text-light">{{$article->title}}</h1>

                    <div class="article-meta">
                        <span><i class="fas fa-user me-1"></i> By {{ $article->author_name ?? 'techStream Media' }}</span>
                        <span><i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($article->published_at)->format('F j, Y') }}</span>
                        <span><i class="fas fa-clock me-1"></i> {{ ceil(str_word_count(strip_tags($article->content)) / 200) }} min read</span>
                        <span><i class="fas fa-eye me-1"></i> {{ $article->views }} views</span>
                    </div>

                    <div class="article-tags">

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div class="row">
            <!-- Article Content -->
            <div class="col-lg-8">
                <article class="article-content" data-aos="fade-up">
                    @if (!empty($article->featured_image_url))
                    <div class="article-image-featured mb-4">
                        <img src="{{ $article->featured_image_url }}" alt="Featured Image" class="img-fluid w-100" />
                    </div>
                    @endif

                    <!-- Output the HTML content directly from the DB (Ensure input was sanitized on save if allowing HTML) -->
                    <div class="p-2">
                        {!! $article->content !!}
                    </div>

                </article>

                <!-- Author Bio -->
                <div class="author-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="d-flex">
                        <div class="author-avatar">

                        </div>
                        <div>
                            <h4 class="fw-bold mb-2">{{$article->author_name ?? 'techStream Media'}}</h4>
                            <p class="text-muted mb-2">Content Creator & Tech Enthusiast</p>
                            <p class="small text-muted">Passionate about uncovering the latest trends in the African tech ecosystem.</p>
                        </div>
                    </div>
                </div>

                <!-- Social Share -->
                <div class="d-flex justify-content-between align-items-center py-4 border-top border-bottom" data-aos="fade-up" data-aos-delay="200">
                    <div>
                        <h5 class="fw-bold mb-2">Share this article</h5>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="social-btn"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied!'); return false;" class="social-btn"><i class="fas fa-link"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Related Articles -->
                <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="200">
                    <h5 class="fw-bold mb-4">Related Articles</h5>


                </div>

                <!-- Ad Container -->
                <div class="ad-container" data-aos="fade-left" data-aos-delay="100" style="position: sticky; top:100px">
                    <div class="card border-0 shadow-sm p-4 text-center bg-light">
                        <h5 class="text-muted mb-2">Advertisement</h5>
                        <p class="text-muted small mb-3">Looking to reach thousands of tech professionals?</p>
                        <a href="media-kit.html" class="btn btn-sm btn-outline-primary">Advertise With Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@include('partials.footer')
</body>
</html>