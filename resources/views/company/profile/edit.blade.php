@extends('layouts.company')

@section('content')
<div class="row">
    <div class="col-md-10 col-lg-8">
        <h2 class="h4 mb-4">Company Profile</h2>

        <form method="POST" action="{{ route('company.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Basic Information</h5>

                    <div class="mb-3">
                        <label class="form-label" for="name">Company Name*</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $company->name ?? '') }}" required>
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="description">About Us / Description*</label>
                        <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description', $company->description ?? '') }}</textarea>
                        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="tagline">Tagline</label>
                        <input type="text" name="tagline" id="tagline" class="form-control" value="{{ old('tagline', $company->tagline ?? '') }}">
                        @error('tagline') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Contact Details</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="email">Public Email*</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $company->email ?? auth()->user()->email) }}" required>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $company->phone ?? '') }}">
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="website">Website Link</label>
                            <input type="url" name="website" id="website" class="form-control" value="{{ old('website', $company->website ?? '') }}">
                            @error('website') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="city_id">City Base</label>
                            <select name="city_id" id="city_id" class="form-select">
                                <option value="">Select a City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ (old('city_id', $company->city_id ?? '') == $city->id) ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="address">Full Address</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $company->address ?? '') }}">
                        @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Branding (Logos)</h5>

                    <div class="mb-3">
                        <label class="form-label" for="logo_url">Company Logo</label>
                        <input type="file" name="logo_url" id="logo_url" class="form-control" accept="image/*">
                        @if(isset($company->logo_url))
                            <div class="mt-2"><img src="{{ asset($company->logo_url) }}" alt="Logo" width="80" class="img-thumbnail"></div>
                        @endif
                        @error('logo_url') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="cover_image_url">Cover Image</label>
                        <input type="file" name="cover_image_url" id="cover_image_url" class="form-control" accept="image/*">
                        @if(isset($company->cover_image_url))
                            <div class="mt-2"><img src="{{ asset($company->cover_image_url) }}" alt="Cover" width="150" class="img-thumbnail"></div>
                        @endif
                        @error('cover_image_url') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-primary px-4 fw-bold">Save Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
