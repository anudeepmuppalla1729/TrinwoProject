<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Q&A Forum</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.mobile.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="signup-container">
        <div class="signup-left"></div>
        <div class="signup-right">
            <div class="signup-form-wrapper">
                <h3 class="signup-header">Login to Your Account</h3>
                @if ($errors->any())
                    <div class="alert alert-danger" style="color: red; margin-bottom: 1rem;">
                        <ul style="margin: 0; padding-left: 1.2em;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="signup-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="input-icon-group">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="email" class="form-input" placeholder="Enter Your Email" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-icon-group">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" name="password" class="form-input" placeholder="Enter your password" required />
                        </div>
                    </div>
                    <div class="form-group form-checkbox">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember" />
                        <span class="icon-divider"></span>
                        <label class="form-check-label" for="remember">Remember me</label>
                        <p><a href="#" class="form-link ms-auto">Forgot Password?</a></p>
                    </div>
                    <div class="form-group">
                        <button class="btn-signup" type="submit">Login</button>
                    </div>
                    <p class="form-divider">or continue with</p>
                    <div class="social-buttons">
                        <button type="button" class="btn-social">
                            <i class="bi bi-google"></i> Google
                        </button>
                        <button type="button" class="btn-social">
                            <i class="bi bi-facebook"></i> Facebook
                        </button>
                    </div>
                    <p class="form-footer">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="form-link">Sign up</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 