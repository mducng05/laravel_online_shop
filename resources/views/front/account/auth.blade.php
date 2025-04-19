<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>

    <!-- Đưa link CSS vào -->
    <link rel="stylesheet" href="{{ asset('front-assets/css/main.css') }}">
    {{-- <script src="{{ asset('front-assets/js/main.js') }}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="{{ asset('front-assets/js/jquery.min.js') }}"></script>    --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container" id="container">
    <div class="form-container register-container">
        <form action="{{ route('account.processRegister') }}" method="post" id="registrationForm">
            <h1></h1>
            <h2>Register Now</h2>
            <div class="form-control2">
                <input class="invalid-feedback" type="text" class="form-control" placeholder="Name" id="name" name="name">
                <small id="username-error"></small>
                    <span></span>
            </div>
            <div class="form-control2">
                <input class="invalid-feedback" type="text" class="form-control" placeholder="Email" id="email" name="email">
                <small id="email-error"></small>
                    <span></span>
            </div>
            <div class="form-control2">
                <input class="invalid-feedback" type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                <small id="email-error"></small>
                    <span></span>
            </div>
            <div class="form-control2">
                <input class="invalid-feedback" type="password" class="form-control" placeholder="Password" id="password" name="password">
                <small id="password-error"></small>
                    <span></span>
            </div>
            <div class="form-control2">
                <input class="invalid-feedback" type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">
                <small id="password-error"></small>
                    <span></span>
            </div>
            <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
            <span>or use your account</span>
                <div class="social-container">
                    <a href="#" class="social"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fa-brands fa-google"></i></a>
                    <a href="#" class="social"><i class="fa-brands fa-tiktok"></i></a>
                </div>
        </form>
        <div class="text-center small">Already have an account? <a href="#" id="switchToLogin">Login Now</a></div>
    </div>

    <div class="form-container login-container">
        <form action="{{ route('account.authenticate') }}" method="post" id="loginForm">
            @csrf
            <h1>Login to Your Account</h1>
            <div class="form-control2">
                <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}">
                <span></span>
                @error('email')
                    <small class="invalid-feedback">{{ $message }}</small>
                @enderror
                @if(session('error'))
                    <small class="invalid-feedback">{{ session('error') }}</small>
                @endif
            </div>

            <div class="form-control2">
                <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password">
                @error('password')
                    <small class="invalid-feedback">{{ $message }}</small>
                @enderror
                <span></span>
            </div>
            <br><div class="form-group small">
                <a href="{{ route('front.forgotPassword') }}" class="forgot-link">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-dark btn-block btn-lg" value="Login">Login</button>
            <span>or use your account</span>
                <div class="social-container">
                    <a href="#" class="social"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fa-brands fa-google"></i></a>
                    <a href="#" class="social"><i class="fa-brands fa-tiktok"></i></a>
                </div>
        </form>
        <div class="text-center small">Don't have an account? <a href="#" id="switchToRegister">Sign up</a></div>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>To keep connected, please login with your personal info.</p>
                <button class="ghost" id="login" >Login
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start your journey with us.</p>
                <button class="ghost" id="register">Register
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@section('customJs')
<script type="text/javascript">
    const registerButton = document.getElementById("register");
    const loginButton = document.getElementById("login");
    const container = document.getElementById("container");

    const switchToLogin = document.getElementById("switchToLogin");
    const switchToRegister = document.getElementById("switchToRegister");

    registerButton.addEventListener("click", () => {
        container.classList.add("right-panel-active");
    });

    loginButton.addEventListener("click", () => {
        container.classList.remove("right-panel-active");
    });

    switchToLogin.addEventListener("click", (e) => {
        e.preventDefault();
        container.classList.remove("right-panel-active");
    });

    switchToRegister.addEventListener("click", (e) => {
        e.preventDefault();
        container.classList.add("right-panel-active");
    });

    $("#registrationForm").submit(function(event) {
        event.preventDefault();
        $("button[type='submit']").prop('disabled', false);

        $.ajax({
            url: '{{ route("account.processRegister") }}',
            type: 'post',
            data: $(this).serialize(),
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
            $("button[type='submit']").prop('disabled', false);
            if (response.status === false) {
                // Hiển thị lỗi từ server
                Object.keys(response.errors).forEach(function(field) {
                    $("#" + field).siblings("small").addClass('invalid-feedback').html(response.errors[field]);
                    $("#" + field).addClass('is-invalid');
                });
            } else {
                    alert('You have been registerd successfully.');  // Hiển thị thông báo đăng ký thành công
                    window.location.href = "{{ route('account.auth') }}";
                }
            },
            error: function() {
                console.log("Có lỗi xảy ra!");
            }
        });
    });
</script>