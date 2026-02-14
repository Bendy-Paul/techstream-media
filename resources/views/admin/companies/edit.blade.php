@include('admin.partials.header')
@include('admin.partials.sidebar')
<main class="main-content">
    <!-- Header -->

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-1">Edit Company</h2>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary">Back</a>
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

    <form action="{{ route('admin.companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-8">
                <!-- Basic Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Company Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Company Name *</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name', $company->name) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Tagline</label>
                            <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $company->tagline) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Description</label>
                            <textarea name="description" class="form-control" id="ckeditor-editor" rows="15">{{ old('description', $company->description) }}</textarea>
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

                <!-- Tech Stack -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Tech Stack & Tools</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap g-2" style="max-height: 200px; overflow-y: auto;">
                            <?php foreach ($stacks as $stack): ?>
                                <div class="tool-check">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="stack_ids[]" value="<?= $stack['id'] ?>" id="stack_<?= $stack['id'] ?>" 
                                            {{ $company->stacks->contains('id', $stack['id']) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="stack_<?= $stack['id'] ?>">
                                            <i class="<?= $stack['icon_class'] ?> me-1 text-muted"></i> <?= htmlspecialchars($stack['name']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Branch Locations -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Branch Locations</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addBranch()">+ Add Branch</button>
                    </div>
                    <div class="card-body" id="branches_container">
                        <!-- Existing Branches -->
                        @foreach($company->branches as $index => $branch)
                        <div class="border rounded p-2 mb-2 position-relative bg-light" id="branch_{{ $index }}">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-1" onclick="document.getElementById('branch_{{ $index }}').remove()"></button>
                            <select name="branches[{{ $index }}][city_id]" class="form-select form-select-sm mb-1">
                                <option value="">Select City...</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city['id'] }}" {{ $city['id'] == $branch->city_id ? 'selected' : '' }}>{{ $city['name'] }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="branches[{{ $index }}][address]" class="form-control form-control-sm" placeholder="Branch Address" value="{{ $branch->address }}">
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Projects Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Portfolio Projects</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addProject()">+ Add Project</button>
                    </div>
                    <div class="card-body" id="projects_container">
                        <!-- Existing Projects -->
                        @foreach($company->projects as $index => $project)
                        <div class="border rounded p-2 mb-2 position-relative" id="proj_{{ $index }}">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-1" onclick="document.getElementById('proj_{{ $index }}').remove()"></button>
                            <input type="text" name="projects[{{ $index }}][title]" class="form-control form-control-sm mb-1" placeholder="Title" value="{{ $project->title }}">
                            <textarea name="projects[{{ $index }}][description]" class="form-control form-control-sm" rows="1" placeholder="Desc">{{ $project->description }}</textarea>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Gallery Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Company Gallery</h6>
                    </div>
                    <div class="card-body">
                        <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
                        <div class="form-text">Upload new photos to append. (Existing photos management not included in this form)</div>
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
                                    <option value="<?= $city['id'] ?>" {{ (old('city_id', $company->city_id) == $city['id']) ? 'selected' : '' }}><?= htmlspecialchars($city['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $company->address) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Categories</label>
                            <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                                <?php foreach ($categories as $cat): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" id="cat_<?= $cat['id'] ?>"
                                            {{ in_array($cat['id'], old('categories', $company->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                        <input type="email" name="email" class="form-control mb-2" placeholder="Email" value="{{ old('email', $company->email) }}">
                        <input type="text" name="phone" class="form-control mb-2" placeholder="Phone" value="{{ old('phone', $company->phone) }}">
                        <input type="url" name="website_url" class="form-control mb-2" placeholder="Website" value="{{ old('website_url', $company->website_url) }}">
                        <div class="row g-2 mb-2">
                            <div class="col-6"><input type="number" name="year_founded" class="form-control" placeholder="Year Founded" value="{{ old('year_founded', $company->year_founded) }}"></div>
                            <div class="col-6">
                                <select name="team_size" class="form-select">
                                    <option value="">Size...</option>
                                    @foreach(['1-10', '11-50', '51-200', '200+'] as $size)
                                        <option value="{{ $size }}" {{ (old('team_size', $company->team_size) == $size) ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="number" name="starting_cost" class="form-control mb-3" placeholder="Starting Price" value="{{ old('starting_cost', $company->starting_cost) }}">

                        <label class="small text-muted">Logo</label>
                        <input type="file" name="logo" class="form-control mb-2">
                        @if($company->logo)
                            <div class="mb-2"><small>Current: <a href="{{ asset('storage/' . $company->logo) }}" target="_blank">View Logo</a></small></div>
                        @endif

                        <label class="small text-muted">Cover</label>
                        <input type="file" name="cover" class="form-control">
                        @if($company->cover)
                            <div class="mb-2"><small>Current: <a href="{{ asset('storage/' . $company->cover) }}" target="_blank">View Cover</a></small></div>
                        @endif
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Social Links</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                <input type="text" name="social[twitter]" class="form-control" placeholder="Twitter Handle" value="{{ old('social.twitter', $social['twitter'] ?? '') }}">
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                <input type="text" name="social[linkedin]" class="form-control" placeholder="LinkedIn URL" value="{{ old('social.linkedin', $social['linkedin'] ?? '') }}">
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                <input type="text" name="social[facebook]" class="form-control" placeholder="Facebook URL" value="{{ old('social.facebook', $social['facebook'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Stats (Assuming JSON column or separate table, matching create logic) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">Profile Stats (Manual)</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label small text-muted mb-0">Response Rate (%)</label>
                            <input type="number" name="stats[response_rate]" class="form-control form-control-sm" value="{{ old('stats.response_rate', $stats['response_rate'] ?? 90) }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-muted mb-0">Completeness (%)</label>
                            <input type="number" name="stats[completeness]" class="form-control form-control-sm" value="{{ old('stats.completeness', $stats['completeness'] ?? 80) }}">
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <select name="subscription_tier" class="form-select mb-3">
                            <option value="free" {{ (old('subscription_tier', $company->subscription_tier) == 'free') ? 'selected' : '' }}>Free</option>
                            <option value="silver" {{ (old('subscription_tier', $company->subscription_tier) == 'silver') ? 'selected' : '' }}>Silver</option>
                            <option value="gold" {{ (old('subscription_tier', $company->subscription_tier) == 'gold') ? 'selected' : '' }}>Gold</option>
                        </select>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_verified" value="1" {{ old('is_verified', $company->is_verified) ? 'checked' : '' }}>
                            <label class="form-check-label">Verified</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured', $company->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label">Featured</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Update Company</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<script>
    // Include Cities Data for JS
    const citiesData = <?php echo json_encode($cities); ?>;

    // Project Logic
    // Initialize count based on existing items
    let projectCount = <?php echo count($company->projects); ?>;

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
    // Initialize count based on existing items
    let branchCount = <?php echo count($company->branches); ?>;

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

    // Note: Removed initial addProject() / addBranch() calls so we don't start with empty fields if existing ones are present.
    // If you want to always ensure at least one blank one if none exist:
    if (projectCount === 0) addProject();
    if (branchCount === 0) addBranch();
</script>
