@include('admin.partials.header')
@include('admin.partials.sidebar')

<main class="main-content">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Locations Management</h2>
            <p class="text-muted mb-0">Manage countries, states, and cities for your directory.</p>
        </div>
        <!-- Mobile Sidebar Toggle -->
        <button class="btn btn-outline-primary d-lg-none" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
            <?php 
                if ($_GET['status'] == 'success') echo "Action completed successfully!";
                else echo "An error occurred. Please try again.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="locationTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="countries-tab" data-bs-toggle="tab" data-bs-target="#countries" type="button" role="tab">Countries</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="states-tab" data-bs-toggle="tab" data-bs-target="#states" type="button" role="tab">States</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="cities-tab" data-bs-toggle="tab" data-bs-target="#cities" type="button" role="tab">Cities</button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="locationTabsContent">
        
        <!-- COUNTRIES TAB -->
        <div class="tab-pane fade show active" id="countries" role="tabpanel">
            <div class="row">
                <!-- Add Country Form -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">Add New Country</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.locations.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="country">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Country Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Nigeria" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Sort Name (Code)</label>
                                    <input type="text" name="country_shortcode" class="form-control" placeholder="e.g. NG" maxlength="3" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Phone Code</label>
                                    <input type="number" name="country_phonecode" class="form-control" placeholder="e.g. 234" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Add Country</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Countries List -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">All Countries</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="locationsTable" class="table table-hover align-middle w-100">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Name</th>
                                        <th>Code</th>
                                        <th>Phone</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($countries as $country): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?php echo htmlspecialchars($country['name']); ?></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($country['sortname']); ?></span></td>
                                        <td>+<?php echo htmlspecialchars($country['phonecode']); ?></td>
                                        <td class="text-end pe-4">
                                            <form action="{{ route('admin.locations.destroy')}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="type" value="country">
                                                <input type="hidden" name="id" value="<?php echo $country['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-light text-danger" onclick="return confirm('Are you sure? This will delete ALL states and cities in this country!');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATES TAB -->
        <div class="tab-pane fade" id="states" role="tabpanel">
            <div class="row">
                <!-- Add State Form -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">Add New State</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.locations.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="state">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Select Country</label>
                                    <select name="country_id" class="form-select" required>
                                        <option value="" disabled selected>Choose...</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">State Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Lagos" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">State Code</label>
                                    <input type="text" name="statecode" class="form-control" placeholder="e.g. LG" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Add State</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- States List -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">All States</h6>
                        </div>
                        <div class="table-responsive" style="max-height: 500px;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light sticky-top" style="z-index: 1;">
                                    <tr>
                                        <th class="ps-4">State Name</th>
                                        <th>Country</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($states as $state)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $state->name }}</td>
                                        <td>{{ $state->country->name }}</td>
                                        <td class="text-end pe-4">
                                            <form action="{{ route('admin.locations.destroy') }}" method="post" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $state->id }}">
                                                <input type="hidden" name="type" value="state">
                                                <button type="submit"class="btn btn-sm btn-light text-danger" onclick="return confirm('Delete this state and all its cities?');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CITIES TAB -->
        <div class="tab-pane fade" id="cities" role="tabpanel">
            <div class="row">
                <!-- Add City Form -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">Add New City</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.locations.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="city">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Select State</label>
                                    <select name="state_id" class="form-select" required>
                                        <option value="" disabled selected>Choose...</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">
                                                {{ $state->name }} ({{ $state->country->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">City Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Ikeja" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">City Code</label>
                                    <input type="text" name="city_code" class="form-control" placeholder="e.g. IK" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">Add City</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cities List -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">All Cities</h6>
                        </div>
                        <div class="table-responsive" style="max-height: 500px;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light sticky-top" style="z-index: 1;">
                                    <tr>
                                        <th class="ps-4">City Name</th>
                                        <th>State</th>
                                        <th>Country</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cities as $city)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ ($city->name) }}</td>
                                        <td>{{ $city->state->name }}</td>
                                        <td><small class="text-muted">{{ $city->country->name }}</small></td>
                                        <td class="text-end pe-4">
                                            <form action="{{ route('admin.locations.destroy') }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="type" value="city">
                                                <input type="hidden" name="id" value="{{ $city->id }}">
                                                <button type="submit" class="btn btn-sm btn-light text-danger" onclick="return confirm('Delete this city?');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</main>
</div> <!-- Close .admin-wrapper -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#locationsTable').DataTable({
            responsive: true,
            lengthChange: true,
            pageLength: 10
        });
    });
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>
</body>
</html>