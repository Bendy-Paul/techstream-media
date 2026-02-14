// assets/js/states-companies.js

document.addEventListener("DOMContentLoaded", function () {
    let url_path = window.TECHMEDIA_URL_PATH || "";
    
    // Add smooth scrolling to states
    document.querySelectorAll(".state-chip").forEach((chip) => {
        chip.addEventListener("click", function (e) {
            if (this.getAttribute("href") === "#") {
                e.preventDefault();
            }
        });
    });

    // Pagination state
    let currentPage = 1;
    let totalPages = 1;
    let isLoading = false;

    // Fetch and render companies
    async function fetchCompanies(page = 1, force = false) {
        // Prevent multiple simultaneous requests
        if (isLoading && !force) return;
        
        const grid = document.getElementById("companies-grid");
        const count = document.getElementById("companies-count");
        const pagination = document.getElementById("companies-pagination");
        const stateSlug = document.body.getAttribute("data-state-slug") || "";
        const stateId = document.body.getAttribute("data-state-id") || "";
        const stateName = document.body.getAttribute("data-state-name") || "";
        
        const selectedCategories = Array.from(
            document.querySelectorAll(".category-checkbox:checked"),
        ).map((cb) => cb.value);

        // Show loading state
        isLoading = true;
        if (grid) {
            grid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Loading companies...</p>
                </div>`;
        }
        if (pagination) {
            pagination.innerHTML = "";
        }

        const params = new URLSearchParams();
        params.append("slug", stateSlug);
        params.append("location", stateId);
        selectedCategories.forEach((cat) => params.append("categories[]", cat));
        params.append("per_page", 10);
        params.append("page", page);

        try {
            const response = await fetch(
                "/category-filter?" + params.toString(),
                {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            // Update counts if element exists
            if (count) {
                count.textContent = `${data.total} compan${data.total === 1 ? 'y' : 'ies'}`;
            }
            
            totalPages = data.last_page || data.total_pages || 1;
            currentPage = data.current_page || data.page || 1;
            
            let hasCompanies = data.companies && data.companies.length > 0;
            
            if (grid) {
                if (hasCompanies) {
                    let html = "";
                    data.companies.forEach((company) => {
                        html += `
                            <div class="col-md-6 mb-4">
                                <div class="company-card-modern h-100">
                                    <div class="card-header">
                                        <div class="company-logo">
                                            ${company.logo_url ? `<img src="${url_path}/${company.logo_url}" alt="${company.name}">` : `<i class='fas fa-building placeholder'></i>`}
                                        </div>
                                        <div class="company-info">
                                            <h3 class="company-name">${company.name}</h3>
                                            ${company.tagline ? `<p class="company-tagline">${company.tagline}</p>` : ""}
                                            <div class="company-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                ${stateName}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <a href="${url_path}/company/${company.slug}" class="view-profile-btn">
                                            View Company Profile <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>`;
                    });
                    grid.innerHTML = html;
                } else {
                    grid.innerHTML = `
                        <div class="col-12">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h3>No companies found</h3>
                                <p>Try selecting different categories or browse another state to discover more companies.</p>
                                <a href="${url_path}/states/${stateSlug}" class="view-profile-btn" style="max-width: 200px; margin: 0 auto;">
                                    Reset Filters
                                </a>
                            </div>
                        </div>`;
                }
            }
            
            renderPagination(hasCompanies);
            
        } catch (error) {
            console.error("Error fetching companies:", error);
            if (grid) {
                grid.innerHTML = `
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h3>Error loading companies</h3>
                            <p>Please try again later.</p>
                            <button onclick="window.fetchCompanies(1, true)" class="view-profile-btn" style="max-width: 200px; margin: 0 auto;">
                                Retry
                            </button>
                        </div>
                    </div>`;
            }
            renderPagination(false);
        } finally {
            isLoading = false;
        }
    }

    function renderPagination(hasCompanies) {
        const pagContainer = document.getElementById("companies-pagination");
        if (!pagContainer) return;
        
        // Clear if no companies or single page
        if (!hasCompanies || totalPages <= 1) {
            pagContainer.innerHTML = "";
            return;
        }
        
        let html = '<nav class="pagination-nav m-auto text-center"><ul class="pagination justify-content-center">';
        
        // Previous button - Fixed disabled state
        const prevDisabled = currentPage <= 1;
        html += `<li class="page-item${prevDisabled ? " disabled" : ""}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}" ${prevDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>
                        &laquo; Previous
                    </a>
                 </li>`;

        // Calculate page range for display
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, currentPage + 2);
        
        // Adjust if we're near the start
        if (currentPage <= 3) {
            endPage = Math.min(5, totalPages);
        }
        
        // Adjust if we're near the end
        if (currentPage >= totalPages - 2) {
            startPage = Math.max(1, totalPages - 4);
        }
        
        // First page and ellipsis
        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
            if (startPage > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }
        
        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === currentPage;
            html += `<li class="page-item${isActive ? " active" : ""}">
                        <a class="page-link" href="#" data-page="${i}">
                            ${i} ${isActive ? '<span class="sr-only">(current)</span>' : ''}
                        </a>
                     </li>`;
        }
        
        // Last page and ellipsis
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
        }
        
        // Next button - Fixed disabled state
        const nextDisabled = currentPage >= totalPages;
        html += `<li class="page-item${nextDisabled ? " disabled" : ""}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}" ${nextDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>
                        Next &raquo;
                    </a>
                 </li>`;

        html += "</ul></nav>";
        pagContainer.innerHTML = html;
        
        // Add event listeners to pagination links
        pagContainer.querySelectorAll(".page-link:not(.disabled)").forEach((link) => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const page = parseInt(this.getAttribute("data-page"));
                if (!isNaN(page) && page >= 1 && page <= totalPages && page !== currentPage) {
                    fetchCompanies(page);
                    
                    // Scroll to top of companies section smoothly
                    const companiesSection = document.getElementById("companies-grid");
                    if (companiesSection) {
                        companiesSection.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start' 
                        });
                    }
                }
            });
        });
    }

    // Update companies when filters change
    document.querySelectorAll(".category-checkbox").forEach((cb) => {
        cb.addEventListener("change", () => {
            currentPage = 1; // Reset to first page on filter change
            fetchCompanies(1);
        });
    });

    // Expose for manual reload with force option
    window.fetchCompanies = (page = 1, force = false) => fetchCompanies(page, force);

    // Initial fetch
    if (document.getElementById("companies-grid")) {
        fetchCompanies(1);
    }
});