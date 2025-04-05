@extends('layouts.base')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-pastel-pink text-center py-3 rounded-top-4">
                    <h3 class="mb-0 text-primary fw-bold">Edit Your Profile</h3>
                </div>
                <div class="card-body bg-soft-beige p-4">
                    @if (session('success'))
                    <div class="alert alert-success rounded-pill border-2 border-success">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                    @endif

                    <!-- Toggle Buttons -->
                    <div class="d-flex justify-content-center mb-4 gap-3">
                        <button class="btn btn-pastel-blue rounded-pill px-4 py-2 fw-bold" id="showProfile">
                            <i class="fas fa-user me-2"></i>Profile Info
                        </button>
                        <button class="btn btn-pastel-purple rounded-pill px-4 py-2 fw-bold" id="showSecurity">
                            <i class="fas fa-lock me-2"></i>Security Settings
                        </button>
                    </div>

                    <!-- Profile Information Form -->
                    <div id="profileSection" class="bg-white p-4 rounded-4 shadow-sm">
                        <h4 class="text-center mb-4 text-primary">Your Profile Information</h4>
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Profile Image -->
                            <div class="mb-4 text-center">
                                <div class="position-relative d-inline-block">
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image"
                                        class="rounded-circle mb-3 border border-3 border-pastel-pink shadow" width="150" height="150">
                                    <div class="upload-overlay">
                                        <label for="profile_image" class="btn btn-sm btn-pastel-pink rounded-circle position-absolute bottom-0 end-0">
                                            <i class="fas fa-camera"></i>
                                        </label>
                                        <input type="file" id="profile_image" class="form-control d-none" name="profile_image">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Title -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-primary fw-bold">Title</label>
                                    <select class="form-select border-pastel-blue rounded-pill" name="title">
                                        <option value="Mr." {{ old('title', $customer->title) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                        <option value="Mrs." {{ old('title', $customer->title) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                        <option value="Ms." {{ old('title', $customer->title) == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                        <option value="Mx." {{ old('title', $customer->title) == 'Mx.' ? 'selected' : '' }}>Mx.</option>
                                    </select>
                                </div>

                                <!-- First Name -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-primary fw-bold">First Name</label>
                                    <input type="text" class="form-control border-pastel-blue rounded-pill" name="fname" value="{{ old('fname', $customer->fname) }}" required>
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label text-primary fw-bold">Last Name</label>
                                    <input type="text" class="form-control border-pastel-blue rounded-pill" name="lname" value="{{ old('lname', $customer->lname) }}" required>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mb-3">
                                <label class="form-label text-primary fw-bold">Address</label>
                                <input type="text" class="form-control border-pastel-blue rounded-pill" name="address" value="{{ old('address', $customer->addressline) }}" required>
                            </div>

                            <div class="row">
                                <!-- Town -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-primary fw-bold">Town</label>
                                    <input type="text" class="form-control border-pastel-blue rounded-pill" name="town" value="{{ old('town', $customer->town) }}" required>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-primary fw-bold">Phone</label>
                                    <input type="text" class="form-control border-pastel-blue rounded-pill" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-pastel-green rounded-pill px-5 py-2 fw-bold">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Settings Form -->
                    <div id="securitySection" class="bg-white p-4 rounded-4 shadow-sm" style="display: none;">
                        <h4 class="text-center mb-4 text-primary">Security Settings</h4>
                        <form action="{{ route('profile.security') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label text-primary fw-bold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text border-pastel-purple rounded-start-pill bg-pastel-purple text-white">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control border-pastel-purple rounded-end-pill" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>

                            <!-- Old Password (Required for updates) -->
                            <div class="mb-3">
                                <label class="form-label text-primary fw-bold">Current Password</label>
                                <div class="input-group">
                                    <span class="input-group-text border-pastel-purple rounded-start-pill bg-pastel-purple text-white">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input type="password" class="form-control border-pastel-purple rounded-end-pill" name="current_password" required>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label class="form-label text-primary fw-bold">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text border-pastel-purple rounded-start-pill bg-pastel-purple text-white">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control border-pastel-purple rounded-end-pill" name="password">
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label class="form-label text-primary fw-bold">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text border-pastel-purple rounded-start-pill bg-pastel-purple text-white">
                                        <i class="fas fa-check-double"></i>
                                    </span>
                                    <input type="password" class="form-control border-pastel-purple rounded-end-pill" name="password_confirmation">
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-pastel-purple rounded-pill px-5 py-2 fw-bold">
                                    <i class="fas fa-shield-alt me-2"></i>Update Security
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS for Plushie Store Theme -->
<style>
    .bg-pastel-pink {
        background-color: #FFD1DC !important;
    }

    .bg-pastel-blue {
        background-color: #D1E8FF !important;
    }

    .bg-pastel-purple {
        background-color: #E0C1F4 !important;
    }

    .bg-pastel-green {
        background-color: #D1FFD6 !important;
    }

    .bg-soft-beige {
        background-color: #FFF9F0 !important;
    }

    .text-primary {
        color: #7B5EA7 !important;
    }

    .btn-pastel-blue {
        background-color: #D1E8FF;
        border-color: #B8D9FF;
        color: #4A6FA5;
    }

    .btn-pastel-purple {
        background-color: #E0C1F4;
        border-color: #D1A7F0;
        color: #7B5EA7;
    }

    .btn-pastel-green {
        background-color: #D1FFD6;
        border-color: #B8F0BF;
        color: #4A8C59;
    }

    .btn-pastel-pink {
        background-color: #FFD1DC;
        border-color: #FFB8C9;
        color: #A54A6F;
    }

    .border-pastel-blue {
        border-color: #B8D9FF !important;
    }

    .border-pastel-purple {
        border-color: #D1A7F0 !important;
    }

    .border-pastel-pink {
        border-color: #FFB8C9 !important;
    }

    .rounded-4 {
        border-radius: 1rem !important;
    }

    .card {
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(209, 167, 240, 0.25);
        border-color: #D1A7F0;
    }

    .upload-overlay {
        position: relative;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showProfile = document.getElementById("showProfile");
        const showSecurity = document.getElementById("showSecurity");
        const profileSection = document.getElementById("profileSection");
        const securitySection = document.getElementById("securitySection");

        showProfile.addEventListener("click", function() {
            profileSection.style.display = "block";
            securitySection.style.display = "none";
            showProfile.classList.remove('btn-pastel-blue');
            showProfile.classList.add('btn-primary');
            showSecurity.classList.remove('btn-primary');
            showSecurity.classList.add('btn-pastel-purple');
        });

        showSecurity.addEventListener("click", function() {
            securitySection.style.display = "block";
            profileSection.style.display = "none";
            showSecurity.classList.remove('btn-pastel-purple');
            showSecurity.classList.add('btn-primary');
            showProfile.classList.remove('btn-primary');
            showProfile.classList.add('btn-pastel-blue');
        });

        document.getElementById('profile_image').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('img.rounded-circle').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endsection