@extends('layouts.web.master')
@section('title', 'Profile')
@section('content')

<main class="spotmee-main">
    <div class="px-5">
        <section class="hero-banner" style="background-image: url('{{ asset('images/banner-img.png') }}'); min-height: 85vh; padding-bottom: 100px;">
            <div class="absolute inset-0 bg-black/10"></div>
            
            <div class="auth-container-new">
                <div class="w-full max-w-[550px]" data-aos="fade-up">
                    <div class="form-wrapper !mt-0">
                        <form id="profileForm" action="{{ route('user-profile-information.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-10 text-center">
                                <h1 class="auth-title">Update Profile</h1>
                                <p class="auth-subtitle">
                                    Update your account profile information
                                </p>
                            </div>

                            @if (session('status') === 'profile-information-updated')
                                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800" role="alert">
                                    Profile updated successfully!
                                </div>
                            @endif

                            @if ($errors->updateProfileInformation->any())
                                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg" role="alert">
                                    <ul class="mb-0 list-disc list-inside text-red-800">
                                        @foreach ($errors->updateProfileInformation->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-6">
                                <label for="name" class="auth-label">Full Name</label>
                                <input type="text" id="name" name="name" class="auth-input" 
                                    value="{{ old('name', auth()->user()->name) }}" 
                                    placeholder="Enter your full name" required>
                            </div>

                            <div class="mb-6">
                                <label for="email" class="auth-label">Email Address</label>
                                <input type="email" id="email" name="email" class="auth-input" 
                                    value="{{ old('email', auth()->user()->email) }}" 
                                    placeholder="your.email@example.com" required>
                            </div>

                            <button type="submit" class="cta-btn w-full mb-8 py-4">
                                <i class="fa-solid fa-save mr-2"></i> Update Profile
                            </button>

                            <div class="text-center">
                                <p class="auth-subtitle">
                                    <a href="{{ route('home') }}" class="auth-link">Back to Home</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

@endsection
