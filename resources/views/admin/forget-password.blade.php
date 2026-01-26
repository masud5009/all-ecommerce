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
        <div class="col-lg-8">
            <div class="login-form">
                <div class="card">
                    <div class="card-header">
                        <a href="">
                            <img src="{{ asset('assets/admin/car.png') }}" alt="website-image">
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="">
                            <input type="email" class="form-control" name="email" placeholder="Enter Your Email">
                            <button type="submit" class="form-control submit-btn">Send</button>
                        </form>
                        <a class="forget-link d-flex justify-content-center pb-3" href="{{ route('admin.login') }}">
                            >>Back
                          </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- bootstrap js-->
    <script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
