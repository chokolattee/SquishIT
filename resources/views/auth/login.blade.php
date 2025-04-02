@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 3rem;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <h1 class="fw-bold" style="color: #003d80; text-shadow: 1px 1px 3px rgba(0,0,0,0.1);">SquishIT</h1>
                <p class="text-muted">Welcome back to your plushie paradise!</p>
            </div>
            
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header text-center position-relative py-3" 
                     style="background: linear-gradient(135deg, #b3d9ff 0%, #80c4ff 100%); 
                            color: #003d80;">
                    <h4 class="fw-bold m-0">{{ __('Login') }}</h4>
                    <!-- Decorative plushie elements -->
                    <div class="position-absolute" style="top: -15px; left: 15px; transform: rotate(-10deg);">
                        <svg width="40" height="40" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="45" fill="#ffaacc" />
                            <circle cx="35" cy="40" r="5" fill="#333" />
                            <circle cx="65" cy="40" r="5" fill="#333" />
                            <path d="M 40 65 Q 50 75 60 65" stroke="#333" stroke-width="3" fill="none" />
                        </svg>
                    </div>
                    <div class="position-absolute" style="top: -5px; right: 15px; transform: rotate(10deg);">
                        <svg width="30" height="30" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="45" fill="#aaddff" />
                            <circle cx="35" cy="40" r="5" fill="#333" />
                            <circle cx="65" cy="40" r="5" fill="#333" />
                            <path d="M 35 65 Q 50 75 65 65" stroke="#333" stroke-width="3" fill="none" />
                        </svg>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label" style="color: #003d80; font-weight: 500;">
                                <i class="fas fa-envelope me-2"></i>{{ __('Email Address') }}
                            </label>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autofocus
                                   style="border-radius: 12px; padding: 12px; border: 2px solid #cfe2ff;">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label" style="color: #003d80; font-weight: 500;">
                                <i class="fas fa-lock me-2"></i>{{ __('Password') }}
                            </label>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required
                                   style="border-radius: 12px; padding: 12px; border: 2px solid #cfe2ff;">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                                  {{ old('remember') ? 'checked' : '' }}
                                  style="border: 2px solid #cfe2ff; width: 20px; height: 20px;">
                            <label class="form-check-label ms-2" for="remember" style="color: #003d80;">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn py-3" 
                                    style="background: linear-gradient(135deg, #003d80 0%, #0069d9 100%); 
                                           color: #ffffff; 
                                           border-radius: 12px;
                                           font-weight: 600;
                                           box-shadow: 0 4px 8px rgba(0, 61, 128, 0.2);
                                           transition: all 0.3s ease;">
                                <i class="fas fa-sign-in-alt me-2"></i>{{ __('Login') }}
                            </button>
                            
                            @if (Route::has('password.request'))
                                <a class="btn btn-link text-decoration-none text-center py-2" 
                                   style="color: #003d80;" 
                                   href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p style="color: #003d80;">Don't have an account? 
                                <a href="{{ route('register') }}" style="color: #0069d9; font-weight: 600; text-decoration: none;">
                                    Join the Plushie family!
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer text-center py-3" style="background-color: #f8fbff; border-top: none;">
                    <div class="d-flex justify-content-center">
                        <div style="color: #003d80; font-size: 24px;">
                            <i class="fas fa-heart mx-2" style="color: #ffaacc;"></i>
                            <i class="fas fa-star mx-2" style="color: #ffcc00;"></i>
                            <i class="fas fa-paw mx-2" style="color: #aaddff;"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted small">© 2025 SquishIT Plushie Store. All hugs reserved.</p>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8fbff;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23b3d9ff' fill-opacity='0.2'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .form-control:focus {
        border-color: #80c4ff;
        box-shadow: 0 0 0 0.25rem rgba(0, 61, 128, 0.25);
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 61, 128, 0.3);
    }
    
    /* Add some animation */
    @keyframes float {
        0% { transform: translateY(0px) rotate(-10deg); }
        50% { transform: translateY(-5px) rotate(-8deg); }
        100% { transform: translateY(0px) rotate(-10deg); }
    }
    
    .position-absolute svg {
        animation: float 3s ease-in-out infinite;
    }
    
    .position-absolute:nth-child(2) svg {
        animation-delay: 1.5s;
    }
</style>
@endsection