<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- <link rel="stylesheet" href="assets/plugins/all.min.css"> -->
    <!-- bootstrap css-->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
    <!-- admin-login css-->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-login.css') }}">
</head>

<body>
    <main>
        <div class="login-image">
            <img src="{{ asset('assets/admin/img/login-bg.jpg') }}" alt="">
        </div>
        <div class="login-form">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('frontend.index') }}" target="_blank" class="text-center mb-4">
                        <img src="{{ asset('assets/img/' . $websiteInfo->website_logo) }}" alt="website-image">
                    </a>
                    @if (session()->has('alert'))
                        <div class="alert alert-danger fade show" role="alert">
                            <strong>{{ session('alert') }}</strong>
                        </div>
                    @endif
                    <form action="{{ route('admin.auth') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Username <span class="text-danger">**</span></label>
                            <input type="text" class="form-control {{customValid('username',$errors)}}" name="username" value="{{old('username')}}"  placeholder="Enter Username">
                            @if ($errors->has('username'))
                                <p class="text-danger text-left">{{ $errors->first('username') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Password <span class="text-danger">**</span></label>
                            <input type="password" class="form-control {{customValid('password',$errors)}}" name="password" placeholder="Enter Password">
                            @if ($errors->has('password'))
                                <p class="text-danger text-left">{{ $errors->first('password') }}</p>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-success w-100 p-2 mt-2 mb-3">Login </button>
                    </form>
                    <a class="forget-link d-flex justify-content-center pb-3"
                        href="{{ route('admin.forget_password') }}">
                        Forget Password or Username?
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- bootstrap js-->
    <script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
