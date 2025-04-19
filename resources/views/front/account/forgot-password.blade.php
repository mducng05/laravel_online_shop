<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Your Account</title>

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
    <style>
        /* Style for success message */
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        /* Style for error message */
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
<div class="container" id="container">
    <div class="form-container login-container">
        <form action="{{ route('front.processForgotPassword') }}" method="post">
            @csrf
            <h1>Forgot Password</h1>
            <br>
             <!-- Display success or error message -->
             @if(session('success'))
             <div class="alert alert-success">
                 <small>{{ session('success') }}</small>
             </div>
         @elseif(session('error'))
             <div class="alert alert-danger">
                 <small>{{ session('error') }}</small>
             </div>
         @endif
            <div class="form-control2">
                <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}">
                <span></span>
                @error('email')
                    <small class="invalid-feedback">{{ $message }}</small>
                @enderror
            </div>
            <br>
            <button type="submit" class="btn btn-dark btn-block btn-lg" value="Login">Submit</button>
            <a href="{{ route('account.auth') }}" class="forgot-link">Login</a>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start your journey with us.</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>