<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up - Q&A Forum</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Your custom style -->
  <link rel="stylesheet" href="{{ asset('css/signup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/signup.mobile.css') }}" media="screen and (max-width: 650px)">
    <!-- <link rel="stylesheet" href="{{ asset('css/user_information.css') }}"> -->
</head>
<body>
  <div class="signup-container">
    <!-- Left Section -->
    <div class="signup-left"></div>

    <!-- Right Section -->
    <div class="signup-right">
      <div class="signup-form-wrapper">
        <h3 class="signup-header">Create your Account</h3>
        @if ($errors->any())
            <div class="alert alert-danger" style="color: red; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.2em;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="signup-form" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Name</label>
                <div class="input-icon-group">
                    <i class="bi bi-person-circle"></i>
                    <input type="text" name="name" class="form-input" placeholder="Enter Your Name" required />
                </div>
                <div class="error-message"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-icon-group">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" class="form-input" placeholder="Enter Your Email" required />
                </div>
                <div class="error-message"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-icon-group">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password" class="form-input" placeholder="Create password" required />
                </div>
                <div class="error-message"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="input-icon-group">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm password" required />
                </div>
                <div class="error-message"></div>
            </div>
            <div class="form-group form-checkbox">
                <input class="form-check-input" type="checkbox" id="terms" name="terms" />
                <span class="icon-divider"></span>
                <label class="form-check-label" for="terms">I agree to all terms &amp; Conditions (optional)</label>
            </div>
            <div class="form-group">
                <button class="btn-signup" type="submit">Sign up</button>
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
                Already have an account?
                <a href="{{ route('login') }}" class="form-link">Sign in</a>
            </p>
        </form>
      </div>
    </div>
  </div>

  <!-- JS -->
  <!-- <script src="js/signup.js"></script> -->
</body>
</html>
