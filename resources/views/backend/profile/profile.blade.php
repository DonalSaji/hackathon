@extends('backend.body.master')
@section('title', 'My Profile')
@section('content')

    <style>
        /* Wrapper for the profile image */
        .profile-image-wrapper {
            position: relative;
            display: inline-block;
            width: fit-content;
            cursor: pointer;
        }

        /* Overlay for input file */
        .profile-image-wrapper .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 50%;
        }

        .profile-image-wrapper:hover .overlay {
            opacity: 1;
        }

        .upload-text {
            pointer-events: none;
        }

        .device-card {
            transition: all 0.3s ease;
            border: 1px solid #e3eaef;
        }

        .device-card:hover {
            box-shadow: 0 0.15rem 0.75rem 0.125rem rgba(154, 161, 171, 0.1);
            transform: translateY(-2px);
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Profile</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card text-center">
                <div class="card-body">
                    <div class="profile-image-wrapper">
                        <img id="profileImage"
                            src="{{ $user->profile && $user->profile->avatar ? route('files.view', $user->profile->avatar) : asset('assets/images/users/avatar.jpg') }}"
                            class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                        <div class="overlay">
                            <input type="file" id="avatarInput" class="form-control-file" accept=".jpg, .jpeg, .png"
                                style="opacity:0; position:absolute; width:100%; height:100%; cursor:pointer;"
                                title=" " />
                            <span class="upload-text">Replace</span>
                        </div>
                    </div>

                    <h4 class="mb-0 mt-2">{{ auth()->user()->name }}</h4>

                    <div class="text-start mt-3">
                        <h4 class="font-13 text-uppercase">About Me :</h4>
                        <p class="text-muted font-13 mb-3">{{ $user->profile->bio ?? null }}</p>
                        <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span
                                class="ms-2">{{ $user->name }}</span></p>
                        <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ms-2">+91
                                {{ $user->phone ?? null }}</span></p>
                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span
                                class="ms-2">{{ $user->email }}</span></p>
                        <p class="text-muted mb-1 font-13"><strong>Location :</strong> <span
                                class="ms-2">{{ ($user->profile ? ($user->profile->city ? $user->profile->city . ', ' : null) : null) . ($user->profile->state ?? null) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="post">
                        @csrf
                        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Personal Info</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter name" value="{{ $user->name }}">
                                @error('name')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="dob">Date of Birth</label>
                                <input type="text" id="dob" name="dob" class="form-control"
                                    value="{{ $user->profile->dob ?? null }}">
                                @error('dob')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="userbio" class="form-label">Bio</label>
                                <textarea class="form-control" id="userbio" name="bio" rows="3" placeholder="Write something...">{{ $user->profile->bio ?? null }}</textarea>
                                @error('bio')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="street_address" class="form-label">Street Address</label>
                                <input type="text" class="form-control" id="street_address" name="street_address"
                                    placeholder="Enter Street Address"
                                    value="{{ $user->profile->street_address ?? null }}">
                                @error('street_address')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    placeholder="Your City" value="{{ $user->profile->city ?? null }}">
                                @error('city')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state"
                                    placeholder="Your State" value="{{ $user->profile->state ?? null }}">
                                @error('state')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="pincode" class="form-label">Pincode</label>
                                <input type="text" class="form-control" id="pincode" name="pincode"
                                    placeholder="Your Pincode" value="{{ $user->profile->pincode ?? null }}">
                                @error('pincode')
                                    <div class="text-danger mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end"><button type="submit" class="btn btn-success mt-2"><i
                                    class="mdi mdi-content-save"></i> Save</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        <script src="{{ asset('assets/js/webauthn-db.js') }}" type="module"></script>
        <script src="{{ asset('assets/js/biometric-register.js') }}" type="module"></script>
        <script type="module" src="{{ asset('assets/js/biometric-profile.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $("#dob").flatpickr({
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                defaultDate: "{{ $user->profile->dob ?? 'today' }}",
                maxDate: "today"
            });

            document.getElementById('avatarInput').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;
                const formData = new FormData();
                formData.append('avatar', file);
                fetch('/upload-avatar', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) return response.json().then(err => {
                            throw err;
                        });
                        return response.json();
                    })
                    .then(data => {
                        $.NotificationApp.send("Success", "Avatar uploaded successfully!", "top-right",
                            "rgba(0,0,0,0.2)", "success");
                        document.getElementById('profileImage').src = data.avatar_url;
                        try {
                            document.getElementById('userProfileImage').src = data.avatar_url;
                        } catch (e) {}
                    })
                    .catch(error => {
                        const msg = (error && error.errors && error.errors.avatar && error.errors.avatar[0]) ? error
                            .errors.avatar[0] : (error.message || 'An unexpected error occurred.');
                        $.NotificationApp.send("Error", msg, "top-right", "rgba(0,0,0,0.2)", "error");
                    });
            });
        </script>
    @endpush

@endsection
