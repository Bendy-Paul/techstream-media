// assets/js/companies-filter.js

document.addEventListener('DOMContentLoaded', function () {
    const urlPath = 'http://127.0.0.1:8000'; // Adjust this to your base URL
    var verifiedBadge = '';
    const filterForm = document.getElementById('filter-form');
    const companiesContainer = document.getElementById('companies-list');
    const paginationContainer = document.getElementById('pagination');

    function showSkeletonLoader(count = 6) {
        companiesContainer.innerHTML = '';
        for (let i = 0; i < count; i++) {
            const skeleton = document.createElement('div');
            skeleton.className = 'col-lg-4 col-md-6';
            skeleton.innerHTML = `
                <div class="company-card skeleton-loader">
                    <div class="company-body">
                        <div class="company-logo-sm skeleton-logo"></div>
                        <div class="skeleton-line skeleton-title mb-2"></div>
                        <div class="skeleton-line skeleton-text mb-2"></div>
                        <div class="skeleton-line skeleton-badge mb-2"></div>
                        <div class="skeleton-line skeleton-text mb-2"></div>
                    </div>
                </div>
            `;
            companiesContainer.appendChild(skeleton);
        }
    }

    function fetchCompanies(page = 1) {
        showSkeletonLoader();
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value) params.append(key, value);
        }
        params.append('page', page);
        fetch('/category-filter?' + params.toString())
            .then(res => res.json())
            .then(data => {
                renderCompanies(data.companies);
                renderPagination(data.page, data.total_pages);
            });
    }

    function renderCompanies(companies) {
        companiesContainer.innerHTML = '';
        if (!companies.length) {
            companiesContainer.innerHTML = '<div class="alert alert-warning">No companies found.</div>';
            return;
        }
        companies.forEach(company => {
            const div = document.createElement('div');
            if (`${company.is_verified}` == 1) {
                verifiedBadge = '<span class="verified-badge"><i class="fas fa-check-circle"></i> Verified</span>';
            }else{
                verifiedBadge = '<span class="verified-badge bg-light text-black"><i class="fas fa-times-circle"></i> Unverified</span>';
            }
            div.className = 'col-lg-4 col-md-6 mb-4';
            div.innerHTML = `
                    <div class="company-card">
                        <div class="company-body">
                            <div class="company-logo-sm">
                                <img src="${urlPath}/${company.logo_url}" alt="${company.name}" style="height: 100%; width: auto;">
                            </div>
                            <h5 class="fw-bold mb-2">${company.name}</h5>
                            <div class="align-items-center mb-2">
                                ${verifiedBadge}<br>
                                <span class="badge bg-light text-dark border ms-2"><?= htmlspecialchars($listed_company['location'] ?? 'Lagos') ?></span>
                            </div>
                            <p class="text-muted small mb-3">${company.tagline || ''}</p>
                            <div class="d-flex justify-content-between text-muted small">
                                <span><i class="fas fa-users me-1"></i> ${company.team_size} employees</span>
                                <span><i class="fas fa-star me-1 text-warning"></i> 4.5</span>
                            </div>
                            <a href="${urlPath}/company/${company.slug}"><button class="btn-view-profile">View Profile</button></a>
                        </div>
                    </div>
            `;
            companiesContainer.appendChild(div);
        });
    }

    function renderPagination(current, total) {
        paginationContainer.innerHTML = '';
        if (total <= 1) return;
        for (let i = 1; i <= total; i++) {
            const btn = document.createElement('a');
            btn.className = 'btn btn-sm m-1 ' + (i === current ? 'btn-primary' : 'btn-outline-primary');
            btn.textContent = i;
            btn.href = '#companies-list-filter';
            btn.onclick = () => fetchCompanies(i);
            paginationContainer.appendChild(btn);
        }
    }

    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            fetchCompanies(1);
        });
    }

    // Initial load
    fetchCompanies(1);
});
