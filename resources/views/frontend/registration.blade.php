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
        @include('frontend.include.breadcrum')

        <section class="signup-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="signup-card" data-aos="fade-up">
                            <h2 class="title">Create Your Account</h2>
                            <p class="subtitle">Join thousands of businesses using our platform</p>

                            <!-- Signup Form -->
                            <form id="signupForm" action="{{ route('frontend.register.validate_check') }}" method="POST">
                                <input type="hidden" name="package_id" value="{{ $package_id }}">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">
                                        Username
                                        <span class="hint">This will be your subdomain</span>
                                    </label>
                                    <input type="text" name="username" id="username" class="form-control" required>
                                    <p id="err_username" class="mb-0 text-danger em"></p>
                                    <div class="subdomain-preview">
                                        <i class="fas fa-globe preview-icon"></i>
                                        <span class="preview-url">
                                            <span class="subdomain">yourname</span>.{{ env('WEBSITE_HOST') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required>
                                    <p id="err_email" class="mb-0 text-danger em"></p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    <p id="err_password" class="mb-0 text-danger em"></p>

                                    <div class="password-requirements">
                                        <div class="requirement-item" data-requirement="length">
                                            <i class="fas fa-circle"></i> At least 8 characters
                                        </div>
                                        <div class="requirement-item" data-requirement="uppercase">
                                            <i class="fas fa-circle"></i> One uppercase letter
                                        </div>
                                        <div class="requirement-item" data-requirement="lowercase">
                                            <i class="fas fa-circle"></i> One lowercase letter
                                        </div>
                                        <div class="requirement-item" data-requirement="number">
                                            <i class="fas fa-circle"></i> One number
                                        </div>
                                        <div class="requirement-item" data-requirement="special">
                                            <i class="fas fa-circle"></i> One special character
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                    <p id="err_password_confirmation" class="mb-0 text-danger em"></p>
                                </div>

                                <button type="submit" id="signupBtn" class="btn btn-signup">Create Account</button>
                            </form>


                            <div class="login-link">
                                Already have an account? <a href="login.html">Log in</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
    @section('script')
        <script src="{{ asset('assets/front/js/registration-check.js') }}"></script>
    @endsection
