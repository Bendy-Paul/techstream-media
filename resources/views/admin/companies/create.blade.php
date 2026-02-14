

@include('admin.partials.header')
@include('admin.partials.sidebar')
<main class="main-content">
    <!-- Header -->

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>


<!-- <main class="main-content"> -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-1">Add New Company</h2>
        <a href="companies.php" class="btn btn-outline-secondary">Back</a>
    </div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
               @csrf
                <!-- Basic Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Company Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Company Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Tagline</label>
                            <input type="text" name="tagline" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Description</label>
                            <textarea name="description" class="form-control" id="ckeditor-editor" rows="15"></textarea>
                            <div class="form-text">You can use rich text formatting.</div>
                            <!-- CKEditor 5 WYSIWYG Editor -->
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    ClassicEditor
                                        .create(document.querySelector('#ckeditor-editor'))
                                        .catch(error => {
                                            console.error(error);
                                        });
                                });
                            </script>
                        </div>
                    </div>
                </div>

                <!-- Tech Stack (New) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Tech Stack & Tools</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap g-2" style="max-height: 200px; overflow-y: auto;">
                            <?php foreach ($stacks as $stack): ?>
                                <div class="tool-check">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="stack_ids[]" value="<?= $stack['id'] ?>" id="stack_<?= $stack['id'] ?>">
                                        <label class="form-check-label small" for="stack_<?= $stack['id'] ?>">
                                            <i class="<?= $stack['icon_class'] ?> me-1 text-muted"></i> <?= htmlspecialchars($stack['name']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Branch Locations (New) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Branch Locations</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addBranch()">+ Add Branch</button>
                    </div>
                    <div class="card-body" id="branches_container">
                        <!-- Dynamic rows here -->
                    </div>
                </div>

                <!-- Projects Section (Existing) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Portfolio Projects</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addProject()">+ Add Project</button>
                    </div>
                    <div class="card-body" id="projects_container"></div>
                </div>

                <!-- Gallery Section (New) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Company Gallery</h6>
                    </div>
                    <div class="card-body">
                        <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
                        <div class="form-text">Upload office photos or team pictures (Multiple allowed).</div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-4">
                <!-- HQ Location -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Headquarters</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small text-muted">City</label>
                            <select name="city_id" class="form-select">
                                <option value="">Select...</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?= $city['id'] ?>"><?= htmlspecialchars($city['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted">Address</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Categories</label>
                            <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                                <?php foreach ($categories as $cat): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" id="cat_<?= $cat['id'] ?>">
                                        <label class="form-check-label" for="cat_<?= $cat['id'] ?>">
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Branding -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Contact & Info</h6>
                    </div>
                    <div class="card-body">
                        <input type="email" name="email" class="form-control mb-2" placeholder="Email">
                        <input type="text" name="phone" class="form-control mb-2" placeholder="Phone">
                        <input type="url" name="website_url" class="form-control mb-2" placeholder="Website">
                        <div class="row g-2 mb-2">
                            <div class="col-6"><input type="number" name="year_founded" class="form-control" placeholder="Year Founded"></div>
                            <div class="col-6">
                                <select name="team_size" class="form-select">
                                    <option value="">Size...</option>
                                    <option value="1-10">1-10</option>
                                    <option value="11-50">11-50</option>
                                    <option value="51-200">51-200</option>
                                    <option value="200+">200+</option>
                                </select>
                            </div>
                        </div>
                        <input type="number" name="starting_cost" class="form-control mb-3" placeholder="Starting Price">

                        <label class="small text-muted">Logo</label>
                        <input type="file" name="logo" class="form-control mb-2">
                        <label class="small text-muted">Cover</label>
                        <input type="file" name="cover" class="form-control">
                    </div>
                </div>

                <!-- Social Media Links (New) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Social Links</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                <input type="text" name="social[twitter]" class="form-control" placeholder="Twitter Handle">
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                <input type="text" name="social[linkedin]" class="form-control" placeholder="LinkedIn URL">
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                <input type="text" name="social[facebook]" class="form-control" placeholder="Facebook URL">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Stats (New) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Profile Stats (Manual)</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label small text-muted mb-0">Response Rate (%)</label>
                            <input type="number" name="stats[response_rate]" class="form-control form-control-sm" value="90">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-muted mb-0">Completeness (%)</label>
                            <input type="number" name="stats[completeness]" class="form-control form-control-sm" value="80">
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <select name="subscription_tier" class="form-select mb-3">
                            <option value="free">Free</option>
                            <option value="silver">Silver</option>
                            <option value="gold">Gold</option>
                        </select>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_verified" value="1">
                            <label class="form-check-label">Verified</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1">
                            <label class="form-check-label">Featured</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Create Listing</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<script>
    // Include Cities Data for JS
    const citiesData = <?php echo json_encode($cities); ?>;

    // Project Logic (Keep existing)
    let projectCount = 0;

    function addProject() {
        const html = `
            <div class="border rounded p-2 mb-2 position-relative" id="proj_${projectCount}">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-1" onclick="document.getElementById('proj_${projectCount}').remove()"></button>
                <input type="text" name="projects[${projectCount}][title]" class="form-control form-control-sm mb-1" placeholder="Title">
                <textarea name="projects[${projectCount}][description]" class="form-control form-control-sm" rows="1" placeholder="Desc"></textarea>
            </div>`;
        document.getElementById('projects_container').insertAdjacentHTML('beforeend', html);
        projectCount++;
    }

    // Branch Logic
    let branchCount = 0;

    function addBranch() {
        let options = '<option value="">Select City...</option>';
        citiesData.forEach(c => {
            options += `<option value="${c.id}">${c.name}</option>`;
        });

        const html = `
            <div class="border rounded p-2 mb-2 position-relative bg-light" id="branch_${branchCount}">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-1" onclick="document.getElementById('branch_${branchCount}').remove()"></button>
                <select name="branches[${branchCount}][city_id]" class="form-select form-select-sm mb-1">${options}</select>
                <input type="text" name="branches[${branchCount}][address]" class="form-control form-control-sm" placeholder="Branch Address">
            </div>`;
        document.getElementById('branches_container').insertAdjacentHTML('beforeend', html);
        branchCount++;
    }

    // Init
    addProject();
    addBranch();
</script>