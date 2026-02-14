// assets/js/news-filter.js

document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('news-filter-form');
    const newsContainer = document.getElementById('news-list');
    const paginationContainer = document.getElementById('news-pagination');

    function showSkeletonLoader(count = 6) {
        newsContainer.innerHTML = '';
        for (let i = 0; i < count; i++) {
            const skeleton = document.createElement('div');
            skeleton.className = 'col-md-4';
            skeleton.innerHTML = `
                <div class="news-card skeleton-loader h-100">
                    <div class="news-img skeleton-logo"></div>
                    <div class="news-body">
                        <div class="skeleton-line skeleton-title mb-2"></div>
                        <div class="skeleton-line skeleton-text mb-2"></div>
                        <div class="skeleton-line skeleton-badge mb-2"></div>
                        <div class="skeleton-line skeleton-text mb-2"></div>
                    </div>
                </div>
            `;
            newsContainer.appendChild(skeleton);
        }
    }

    function fetchNews(page = 1) {
        showSkeletonLoader();
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value) params.append(key, value);
        }
        params.append('page', page);
        fetch('news-filter?' + params.toString())
            .then(res => res.json())
            .then(data => {
                renderNews(data.news);
                renderPagination(data.page, data.total_pages);
            });
    }

    function renderNews(news) {
        newsContainer.innerHTML = '';
        if (!news.length) {
            newsContainer.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted lead">No articles found.</p></div>';
            return;
        }
        news.forEach(article => {
            const div = document.createElement('div');
            div.className = 'col-md-4';
            div.innerHTML = `
                <div class="news-card h-100">
                    <img src="${article.featured_image_url ? article.featured_image_url : 'https://placehold.co/600x400/f1f5f9/2563eb?text=News'}" class="news-img" alt="${article.title}">
                    <div class="news-body">
                        <span class="news-category">${article.category_name || 'News'}</span>
                        <h5 class="fw-bold mb-2">${article.title}</h5>
                        <p>${article.content ? article.content.substring(0, 120) : ''}...</p>
                        <div class="news-meta">
                            <span><i class="fas fa-calendar me-1"></i> ${article.published_at ? new Date(article.published_at).toLocaleDateString() : ''}</span>
                        </div>
                        <a href="article/${article.slug}" class="btn btn-outline-primary btn-sm mt-2">Read More</a>
                    </div>
                </div>
            `;
            newsContainer.appendChild(div);
        });
    }

    function renderPagination(current, total) {
        paginationContainer.innerHTML = '';
        if (total <= 1) return;
        for (let i = 1; i <= total; i++) {
            const btn = document.createElement('a');
            btn.className = 'btn btn-sm m-1 ' + (i === current ? 'btn-primary' : 'btn-outline-primary');
            btn.textContent = i;
            btn.href = '#news-list-filter';
            btn.onclick = (e) => { e.preventDefault(); fetchNews(i); };
            paginationContainer.appendChild(btn);
        }
    }

    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            fetchNews(1);
        });
    }

    // Initial load
    fetchNews(1);
});
