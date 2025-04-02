@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 3rem;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4" style="background-color: #FFF5F7; border: 2px dashed #FFB6C1 !important;">
                <div class="card-header text-center" style="background-color: #FFD1DC; color: #9E4244; font-weight: bold; font-size: 1.5rem; border-bottom: 2px dashed #FFB6C1;">
                    <i class="bi bi-envelope-heart"></i> {{ __('Verify Your Plushie Account') }} <i class="bi bi-envelope-heart"></i>
                </div>

                <div class="card-body" style="padding: 2rem 1.5rem;">
                    <div class="text-center mb-4">
                        <img src="https://via.placeholder.com/120" alt="Cute Bear" class="img-fluid" style="max-width: 120px; border-radius: 50%; border: 3px dashed #FFB6C1;">
                    </div>
                    
                    @if (session('resent'))
                        <div class="alert" role="alert" style="background-color: #E8F8EE; color: #2E856E; border: 2px dashed #A3E4C1; border-radius: 15px; padding: 1rem;">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif
                    
                    <div class="text-center mb-4" style="color: #D25A7E; font-size: 1.1rem; line-height: 1.6;">
                        <p><i class="bi bi-stars me-2"></i>{{ __('Before proceeding to your plushie adventure, please check your email for a verification link.') }}</p>
                        <p>{{ __('This helps us ensure your cuddles are safe and secure!') }}</p>
                    </div>
                    
                    <div class="text-center" style="color: #D25A7E; font-size: 1rem;">
                        {{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline" style="color: #FF80AA; font-weight: bold; text-decoration: underline;">{{ __('click here for a new magic link') }}</button>.
                        </form>
                    </div>
                </div>
                
                <div class="card-footer text-center" style="background-color: #FFE4EE; border-top: 2px dashed #FFB6C1; padding: 1rem;">
                    <a href="{{ route('home') }}" class="btn" style="background-color: #FF80AA; color: white; border-radius: 20px; padding: 0.6rem 1.2rem; font-weight: bold; box-shadow: 0 4px 8px rgba(255, 182, 193, 0.5);">
                        <i class="bi bi-house-heart"></i> {{ __('Back to Homepage') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection