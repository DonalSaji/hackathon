<!DOCTYPE html>
<html lang="en" data-layout-mode="fluid" data-topbar-color="light" data-menu-color="brand" data-sidenav-user="false"
    data-sidenav-size="sm-hover" data-layout-position="fixed">

<head>
    <meta charset="utf-8" />
    <title>Log In | Portfolio Administrator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Toast Plugin css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-toast-plugin/jquery.toast.min.css') }}">

    <!-- App css -->
    <link href="{{ asset('assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/hyper-config.js') }}"></script>

    <style>
        /* Slide Up Animation */
        @keyframes slideUp {
            from {
                transform: translateX(-50%) translateY(60px);
                opacity: 0;
            }

            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }

        /* Pulse / Heartbeat Animation */
        @keyframes pulseEffect {
            0% {
                transform: translateX(-50%) scale(1);
            }

            50% {
                transform: translateX(-50%) scale(1.03);
            }

            100% {
                transform: translateX(-50%) scale(1);
            }
        }

        /* Apply animation only when banner becomes visible */
        #pwa-install-banner.show {
            animation: slideUp 0.4s ease-out forwards, pulseEffect 1.5s ease-in-out 0.5s infinite;
        }

        /* Custom styles for login page */
        .authentication-bg {
            background-color: var(--ct-body-bg);
        }

        .login-card {
            border-radius: 0.75rem;
            border: 1px solid var(--ct-border-color);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }

        .login-card-header {
            background: linear-gradient(135deg, var(--ct-primary) 0%, #5a67d8 100%);
            border-radius: 0.75rem 0.75rem 0 0 !important;
            padding: 1.5rem !important;
        }

        .password-toggle-btn {
            border-color: var(--ct-border-color);
        }

        .password-toggle-btn:hover {
            background-color: var(--ct-light);
        }
    </style>
</head>

<body class="authentication-bg position-relative">
    <!-- Background animation -->
    <div class="position-absolute start-0 end-0 start-0 bottom-0 w-100 h-100" style="z-index: -1;">
        <svg xmlns='http://www.w3.org/2000/svg' width='100%' height='100%' viewBox='0 0 800 800'>
            <g fill-opacity='0.22'>
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.1);" cx='400' cy='400' r='600' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.2);" cx='400' cy='400' r='500' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.3);" cx='400' cy='400' r='300' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.4);" cx='400' cy='400' r='200' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.5);" cx='400' cy='400' r='100' />
            </g>
        </svg>
    </div>

    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card login-card">
                        <!-- Logo -->
                        <div class="card-header text-center login-card-header">
                            <a href="{{ route('home') }}" class="d-block">
                                <img src="{{ asset('assets/images/Portfolio_Admin.png') }}"
                                    alt="Portfolio Administrator Logo" height="45" class="mb-1">
                                <h5 class="text-white mb-0">PORTFOLIO ADMINISTRATOR</h5>
                            </a>
                        </div>

                        <div class="card-body px-4 py-0">
                            <div class="text-center mb-2">
                                <h4 class="fw-semibold">Welcome Back</h4>
                                <p class="text-muted mb-0">Please sign in to your account</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                                @csrf

                                <!-- Email Address -->
                                <div class="mb-2">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-email-outline"></i>
                                        </span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required
                                            autofocus autocomplete="username" placeholder="Enter your email">
                                        @error('email')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="mb-2">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-lock-outline"></i>
                                        </span>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required autocomplete="current-password"
                                            placeholder="Enter your password">
                                        <button class="btn btn-outline-secondary password-toggle-btn" type="button"
                                            id="togglePassword">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="remember_me"
                                                name="remember">
                                            <label class="form-check-label" for="remember_me">
                                                {{ __('Remember me') }}
                                            </label>
                                        </div>
                                        @if (Route::has('password.request'))
                                            <a class="text-primary" href="{{ route('password.request') }}">
                                                <small>{{ __('Forgot Password?') }}</small>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Login Button -->
                                <div class="d-grid mb-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="mdi mdi-login me-1"></i> {{ __('Log in') }}
                                    </button>
                                </div>
                            </form>
                        </div> <!-- end card-body -->

                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent p-2 text-center">
                            <p class="text-muted mb-0">
                                Don't have an account?
                                <a href="#" class="text-primary fw-semibold">Contact Administrator</a>
                            </p>
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <!-- Footer -->
    <footer class="footer footer-alt py-3">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="text-muted mb-0">
                        Copyright ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        All rights reserved
                        <span class="fw-bold text-warning">&nbsp; PORTFOLIO ADMINISTRATOR</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- Toast Plugin js -->
    <script src="{{ asset('assets/vendor/jquery-toast-plugin/jquery.toast.min.js') }}"></script>

    <!-- Toastr Demo js -->
    <script src="{{ asset('assets/js/pages/demo.toastr.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>


    <script>
        // Password toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    // Toggle eye icon
                    const icon = this.querySelector('i');
                    if (icon) {
                        if (type === 'password') {
                            icon.classList.remove('mdi-eye-off-outline');
                            icon.classList.add('mdi-eye-outline');
                        } else {
                            icon.classList.remove('mdi-eye-outline');
                            icon.classList.add('mdi-eye-off-outline');
                        }
                    }
                });
            }

            // Form validation
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });

            // Show notification if exists
            var responseMessage = sessionStorage.getItem('responseMessage');
            if (responseMessage) {
                $.NotificationApp.send(
                    "Success",
                    responseMessage,
                    "top-right",
                    "rgba(0,0,0,0.2)",
                    "success"
                );
                sessionStorage.removeItem('responseMessage');
            }

            // CSRF token global
            window.csrfToken = "{{ csrf_token() }}";
        });
    </script>

    @if (session('error'))
        <script>
            $(document).ready(function() {
                $.NotificationApp.send(
                    "Error",
                    "{{ session('error') }}",
                    "top-right",
                    "rgba(0,0,0,0.2)",
                    "error"
                );
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            $(document).ready(function() {
                $.NotificationApp.send(
                    "Success",
                    "{{ session('success') }}",
                    "top-right",
                    "rgba(0,0,0,0.2)",
                    "success"
                );
            });
        </script>
    @endif

    @if (session('message'))
        <script>
            $(document).ready(function() {
                $.NotificationApp.send(
                    "Success",
                    "{{ session('message') }}",
                    "top-right",
                    "rgba(0,0,0,0.2)",
                    "success"
                );
            });
        </script>
    @endif
</body>

</html>
