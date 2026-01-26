@extends('frontend.layout')
@section('pageHeading')
    {{ __('Home') }}
@endsection
@section('metaKeywords')
    {{ !empty($seoInfo) ? $seoInfo->meta_keyword_home : '' }}
@endsection

@section('metaDescription')
    {{ !empty($seoInfo) ? $seoInfo->meta_description_home : '' }}
@endsection
@section('content')
    <section class="auth-section">
        <div class="container-fluid p-0">
            <div class="row g-0 min-vh-100">
                <!-- Left side: Image -->
                <div class="col-lg-6 d-none d-lg-block auth-image">
                    <div class="auth-image-inner">
                        <div class="overlay"></div>
                        <div class="auth-content  ">
                            <div class="mb-4">
                                <a href="{{ route('frontend.index') }}">
                                    <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="Logo" height="50"
                                        class="mb-4">
                                </a>
                            </div>
                            <h1 class="display-4 fw-bold mb-4 text-white">Welcome Back</h1>
                            <p class="lead mb-4 text-white">Transform your business ideas into success with AI-driven
                                validation
                                and insights.</p>
                            <div class="auth-features text-white">
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Smart Analytics Dashboard</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Real-time Market Insights</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>AI-Powered Validation</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right side: Login Form -->
                <div class="col-lg-6">
                    <div class="auth-form-wrapper">
                        <div class="auth-form-inner">
                            <!-- Logo for mobile -->
                            <div class="text-center d-lg-none mb-4">
                                <a href="index.html" class="navbar-brand">
                                    <img src="assets/images/logo.png" alt="Logo" height="40">
                                </a>
                            </div>

                            <div class="auth-form-header">
                                <h2>Sign in to your account</h2>
                                <p class=" ">Enter your credentials to access the dashboard</p>
                            </div>

                            <form class="auth-form" action="{{ route('user.login_submit') }}" method="POST" id="loginForm">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="emailInput" name="email"
                                        placeholder="name@example.com or username" required>
                                    <label for="emailInput">Email or Username</label>
                                    <small class="text-danger em" id="err_email"></small>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="passwordInput" name="password"
                                        placeholder="Password" required>
                                    <label for="passwordInput">Password</label>
                                    <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <small class="text-danger em" id="err_password"></small>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember"
                                            value="1">
                                        <label class="form-check-label" for="rememberMe">Remember me</label>
                                    </div>
                                    <a href="#" class="forgot-link">Forgot password?</a>
                                </div>

                                <div class="d-grid gap-2 mb-4">
                                    <button type="submit" id="loginBtn" class="btn btn-primary btn-lg">Sign in</button>
                                </div>

                                <div class="auth-divider"><span>Or continue with</span></div>

                                <div class="social-auth-buttons">
                                    <button type="button" class="btn btn-outline-light btn-social">
                                        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg"
                                            alt="Google" width="20">
                                        <span>Google</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-light btn-social"
                                        aria-label="Sign in with Facebook">
                                        <i class="fab fa-facebook-f"
                                            style="font-size:18px; width:20px; text-align:center; display:inline-block;"></i>
                                        <span>Facebook</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
