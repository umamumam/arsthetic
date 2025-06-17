<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
    <meta name="keywords"
        content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
    <meta name="author" content="CodedThemes">

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/x-icon">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('mantis/assets/fonts/tabler-icons.min.css') }}">
    <!-- [Feather Icons] -->
    <link rel="stylesheet" href="{{ asset('mantis/assets/fonts/feather.css') }}">

    <!-- [Font Awesome Icons] -->
    <link rel="stylesheet" href="{{ asset('mantis/assets/fonts/fontawesome.css') }}">

    <!-- [Material Icons] -->
    <link rel="stylesheet" href="{{ asset('mantis/assets/fonts/material.css') }}">

    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('mantis/assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('mantis/assets/css/style-preset.css') }}">
</head>
<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <!-- Logo -->
                <div class="auth-header text-center">
                    <a href="#">
                        <img src="{{ asset('kopma.png') }}" alt="Logo" style="width: 200px; height: auto;">
                    </a>
                </div>

                <!-- Card Login -->
                <div class="card my-5">
                    <div class="card-body">
                        <!-- Header Login -->
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3 class="mb-0"><b>Login</b></h3>
                            <a href="#" class="link-primary">Don't have an account?</a>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-3" :status="session('status')" />

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Login ID -->
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="remember_me" name="remember">
                                    <label class="form-check-label text-muted" for="remember_me">Keep me signed in</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-secondary f-w-400 text-decoration-none">Forgot Password?</a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>

                        <!-- Login with -->
                        {{-- <div class="saprator mt-4 mb-3 text-center">
                            <span>Login with</span>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="d-grid">
                                    <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                                        <img src="{{ asset('mantis/assets/images/authentication/google.svg') }}" alt="Google">
                                        <span class="d-none d-sm-inline-block"> Google</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                    <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                                        <img src="{{ asset('mantis/assets/images/authentication/twitter.svg') }}" alt="Twitter">
                                        <span class="d-none d-sm-inline-block"> Twitter</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                    <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                                        <img src="{{ asset('mantis/assets/images/authentication/facebook.svg') }}" alt="Facebook">
                                        <span class="d-none d-sm-inline-block"> Facebook</span>
                                    </button>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>

                <!-- Footer -->
                <div class="auth-footer row">
                    <div class="col my-1">
                        <p class="m-0">Copyright © <a href="#">ARSthetic</a></p>
                    </div>
                    <div class="col-auto my-1">
                        <ul class="list-inline footer-link mb-0">
                            <li class="list-inline-item"><a href="#">Home</a></li>
                            <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                            <li class="list-inline-item"><a href="#">Contact us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="{{ asset('mantis/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('mantis/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('mantis/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('mantis/assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('mantis/assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('mantis/assets/js/plugins/feather.min.js') }}"></script>

    <script>
        layout_change('light');
    </script>
    <script>
        change_box_container('false');
    </script>
    <script>
        layout_rtl_change('false');
    </script>
    <script>
        preset_change("preset-1");
    </script>
    <script>
        font_change("Public-Sans");
    </script>
</body>

</html>
