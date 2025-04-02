@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 3rem;">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-lg border-0 rounded-4" style="background-color: #FFF5F7; border: 2px dashed #FFB6C1 !important;">
            <div class="card-header text-center" style="background-color: #FFD1DC; color: #9E4244; font-weight: bold; font-size: 1.5rem; border-bottom: 2px dashed #FFB6C1;">
                <i class="bi bi-stars"></i> {{ __('Join Our Plushie Family') }} <i class="bi bi-stars"></i>
            </div>

            <div class="card-body" style="padding: 2rem 1.5rem;">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm">
                    @csrf

                    <!-- Step 1: Account Information -->
                    <div id="step1">
                        <h5 class="mb-4" style="color: #D25A7E; font-weight: bold; text-align: center;">
                            <i class="bi bi-person-heart"></i> Create Your Cuddly Account
                        </h5>
                        
                        <!-- Email -->
                        <div class="row mb-4">
                            <label for="email" class="col-md-4 col-form-label text-md-end" style="color: #D25A7E; font-weight: bold;">
                                <i class="bi bi-envelope-heart"></i> {{ __('Email') }}
                            </label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFFDFD; padding: 0.5rem 1rem;">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="row mb-4">
                            <label for="password" class="col-md-4 col-form-label text-md-end" style="color: #D25A7E; font-weight: bold;">
                                <i class="bi bi-lock-heart"></i> {{ __('Password') }}
                            </label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFFDFD; padding: 0.5rem 1rem;">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="row mb-4">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end" style="color: #D25A7E; font-weight: bold;">
                                <i class="bi bi-shield-lock-heart"></i> {{ __('Confirm Password') }}
                            </label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFFDFD; padding: 0.5rem 1rem;">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn w-100" onclick="nextStep()" style="background-color: #FF80AA; color: white; border-radius: 20px; padding: 0.6rem; font-weight: bold; box-shadow: 0 4px 8px rgba(255, 182, 193, 0.5);">
                                    Continue <i class="bi bi-arrow-right-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Personal Information -->
                    <div id="step2" style="display: none;">
                        <h5 class="mb-4" style="color: #D25A7E; font-weight: bold; text-align: center;">
                            <i class="bi bi-house-heart"></i> Tell Us About Yourself
                        </h5>
                        
                        <!-- Profile Image -->
                        <div class="row mb-4">
                            <label for="profile_image" class="col-md-4 col-form-label text-md-end" style="color: #D25A7E; font-weight: bold;">
                                <i class="bi bi-person-heart"></i> {{ __('Profile Image') }}
                            </label>
                            <div class="col-md-6">
                                <input id="profile_image" type="file" class="form-control @error('profile_image') is-invalid @enderror" name="profile_image" style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFFDFD; padding: 0.5rem 1rem;">
                                @error('profile_image')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="row mb-4">
                            <label for="title" class="col-md-4 col-form-label text-md-end" style="color: #D25A7E; font-weight: bold;">
                                <i class="bi bi-stars"></i> {{ __('Title') }}
                            </label>
                            <div class="col-md-6">
                                <select id="title" class="form-control @error('title') is-invalid @enderror" name="title" required style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFFDFD; padding: 0.5rem 1rem;">
                                    <option value="Mr." {{ old('title') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                    <option value="Mrs." {{ old('title') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                    <option value="Ms." {{ old('title') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                    <option value="Mx." {{ old('title') == 'Mx.' ? 'selected' : '' }}>Mx.</option>
                                </select>
                                @error('title')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- First Name -->
                        <div class="row mb-4">
                            <label for="fname" class="col-md-4 col-form-label text-md-end" style="color: #D25A7E; font-weight: bold;">
                                <i class="bi bi-emoji-smile"></i> {{ __('First Name') }}
                            </label>
                            <div class="col-md-6">
                                <input id="fname" type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" value="{{ old('fname') }}" required style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFFDFD; padding: 0.5rem 1rem;">
                                @error('fname')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="row mb-4">
                            <label for="lname" class="col-md-4 col-form-label text-md-end" style="color: #D25A7E; font-weight: bold;">
                                <i class="bi bi-emoji-smile"></i> {{ __('Last Name') }}
                            </label>
                            <div class="col-md-6">
                                <input id="lname" type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" value="{{ old('lname') }}" required style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFFDFD; padding: 0.5rem 1rem;">
                                @error('lname')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="row mb-4">
                            <label for="addressline" class="col-md-4 col-form-label text-md-end" style="color: #D25A7E; font-weight: bold;">
                                <i class="bi bi-house-heart"></i> {{ __('Address') }}
                            </label>
                            <div class="col-md-6">
                                <input id="addressline" type="text" class="form-control @error('addressline') is-invalid @enderror" name="addressline" value="{{ old('addressline') }}" required style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFFDFD; padding: 0.5rem 1rem;">
                                @error('addressline')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Town -->
                        <div class="row mb-4">
                            <label for="town" class="col-md-4 col-form-label text-md-end" style="color: #D25A7E; font-weight: bold;">
                                <i class="bi bi-buildings"></i> {{ __('Town/City') }}
                            </label>
                            <div class="col-md-6">
                                <input id="town" type="text" class="form-control @error('town') is-invalid @enderror" name="town" value="{{ old('town') }}" required style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFFDFD; padding: 0.5rem 1rem;">
                                @error('town')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="row mb-4">
                            <label for="phone" class="col-md-4 col-form-label text-md-end" style="color: #D25A7E; font-weight: bold;">
                                <i class="bi bi-telephone-heart"></i> {{ __('Phone Number') }}
                            </label>
                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFFDFD; padding: 0.5rem 1rem;">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4 d-flex justify-content-between">
                                <button type="button" class="btn" onclick="prevStep()" style="background-color: #FFDBE9; color: #D25A7E; border-radius: 20px; padding: 0.6rem; font-weight: bold; box-shadow: 0 4px 8px rgba(255, 182, 193, 0.3);">
                                    <i class="bi bi-arrow-left"></i> Back
                                </button>
                                <button type="submit" class="btn" style="background-color: #FF80AA; color: white; border-radius: 20px; padding: 0.6rem 1.2rem; font-weight: bold; box-shadow: 0 4px 8px rgba(255, 182, 193, 0.5);">
                                    <i class="bi bi-heart-fill"></i> {{ __('Join Now') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center" style="background-color: #FFE4EE; border-top: 2px dashed #FFB6C1; padding: 1rem;">
                <p style="color: #D25A7E; margin-bottom: 0;">Already have an account? <a href="{{ route('login') }}" style="color: #FF80AA; font-weight: bold; text-decoration: none;">Login Here</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    function nextStep() {
        // Validate step 1 fields first
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password-confirm').value;

        if (!email || !password || !passwordConfirm) {
            alert('Please fill all account information fields');
            return;
        }

        if (password !== passwordConfirm) {
            alert('Passwords do not match');
            return;
        }

        document.getElementById('step1').style.display = 'none';
        document.getElementById('step2').style.display = 'block';
    }

    function prevStep() {
        document.getElementById('step2').style.display = 'none';
        document.getElementById('step1').style.display = 'block';
    }
</script>
@endsection