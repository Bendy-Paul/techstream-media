<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
</head>

<body>
    @include('admin.partials.header')
    @include('admin.partials.sidebar')

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Add New Event</h2>
                <p class="text-muted mb-0">Create a conference, meetup, or hackathon.</p>
            </div>
            <a href="events.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Back</a>
        </div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    @csrf
                    <!-- Basic Info -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">Event Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Event Title</label>
                                <input type="text" name="title" class="form-control form-control-lg" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Event Type</label>
                                    <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                                        @foreach ($categories as $cat)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{$cat->id}}" id="cat_{{$cat->id}}">
                                            <label class="form-check-label" for="cat_{{$cat->id}}">
                                                {{$cat->name}}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Organizer</label>
                                    <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                                        @foreach ($companies as $company)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="organizers[]" value="{{ $company->id }}" id="org_{{ $company->id }}">
                                                <label class="form-check-label" for="org_{{ $company->id }}">
                                                    {{ htmlspecialchars($company->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Partners</label>
                                    <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                                        @foreach ($companies as $company)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="partners[]" value="{{ $company->id }}" id="partner_{{ $company->id }}">
                                                <label class="form-check-label" for="partner_{{ $company->id }}">
                                                    {{ htmlspecialchars($company->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Description</label>
                                <textarea name="description" class="form-control" rows="5" required></textarea>
                            </div>

                            <!-- Tags -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Tags (#)</label>
                                <input type="text" name="new_tags" class="form-control mb-2" placeholder="Add new tags (comma separated)">
                                <div class="d-flex flex-wrap gap-2 border p-2 rounded" style="max-height: 100px; overflow-y: auto;">
                                    @foreach ($tags as $tag)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}">
                                            <label class="form-check-label small" for="tag_{{ $tag->id }}">#{{ htmlspecialchars($tag->name) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Speakers (Dynamic) -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">Event Speakers</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSpeaker()">+ Add Speaker</button>
                        </div>
                        <div class="card-body" id="speakers_container">
                            <!-- Speaker Row Template (Hidden) -->
                            <!-- Rows will be added here via JS -->
                        </div>
                    </div>

                    <!-- Gallery -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">Event Gallery</h6>
                        </div>
                        <div class="card-body">
                            <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
                            <div class="form-text">Upload multiple images of past events.</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <!-- Date & Location -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">Logistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Start</label>
                                <input type="datetime-local" name="start_datetime" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">End</label>
                                <input type="datetime-local" name="end_datetime" class="form-control" required>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_virtual" id="virtualCheck" onchange="toggleLocation()">
                                <label class="form-check-label" for="virtualCheck">Virtual Event</label>
                            </div>
                            <div id="locationFields">
                                <div class="mb-3">
                                    <input type="text" name="location_name" class="form-control" placeholder="Venue Name">
                                </div>
                                <div class="mb-3">
                                    <select name="city_id" class="form-select">
                                        <option value="">Select City...</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ htmlspecialchars($city->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Socials & Community -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">Community & Socials</h6>
                        </div>
                        <div class="card-body">
                            <h6 class="small fw-bold text-muted mt-2">Social Media</h6>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                                <input type="text" name="social[linkedin]" class="form-control" placeholder="LinkedIn URL">
                            </div>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                <input type="text" name="social[twitter]" class="form-control" placeholder="X/Twitter URL">
                            </div>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                                <input type="text" name="social[facebook]" class="form-control" placeholder="Facebook URL">
                            </div>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                <input type="text" name="social[instagram]" class="form-control" placeholder="Instagram URL">
                            </div>

                            <h6 class="small fw-bold text-muted mt-3">Community Links</h6>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                <input type="text" name="community[whatsapp]" class="form-control" placeholder="WhatsApp Group">
                            </div>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text"><i class="fab fa-telegram"></i></span>
                                <input type="text" name="community[telegram]" class="form-control" placeholder="Telegram Channel">
                            </div>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="text" name="community[newsletter]" class="form-control" placeholder="Newsletter URL">
                            </div>
                        </div>
                    </div>

                    <!-- Ticketing -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">Tickets & Banner</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Price (NGN)</label>
                                <input type="number" name="price" class="form-control" value="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Ticket Link</label>
                                <input type="url" name="ticket_url" class="form-control" placeholder="https://">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Banner Image</label>
                                <input type="file" name="banner_image_upload" class="form-control form-control-sm">
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1">
                                <label class="form-check-label">Feature Event</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold btn-lg">Publish Event</button>
                </div>
            </div>
        </form>
    </main>

    <script>
        let speakerCount = 0;

        function addSpeaker() {
            const container = document.getElementById('speakers_container');
            const html = `
            <div class="border rounded p-3 mb-3 position-relative speaker-row" id="speaker_${speakerCount}">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" onclick="removeSpeaker(${speakerCount})"></button>
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px; width: 80px;">
                            <i class="fas fa-user text-muted fa-2x"></i>
                        </div>
                        <input type="file" name="speaker_images[${speakerCount}]" class="form-control form-control-sm mt-2">
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="speakers[${speakerCount}][name]" class="form-control form-control-sm mb-2" placeholder="Speaker Name">
                        <input type="text" name="speakers[${speakerCount}][position]" class="form-control form-control-sm" placeholder="Job Title / Company">
                    </div>
                </div>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', html);
            speakerCount++;
        }

        function removeSpeaker(id) {
            document.getElementById('speaker_' + id).remove();
        }

        function toggleLocation() {
            const isVirtual = document.getElementById('virtualCheck').checked;
            const locFields = document.getElementById('locationFields');
            locFields.style.opacity = isVirtual ? '0.5' : '1';
            locFields.style.pointerEvents = isVirtual ? 'none' : 'auto';
        }

        // Add first speaker by default
        addSpeaker();
    </script>
</body>

</html>