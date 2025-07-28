<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Inqube</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-only.png') }}">
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
  
                        <p><a href="#" class="form-link ms-auto" id="forgotPasswordLink">Forgot Password?</a></p>

                    <div class="form-group">
                        <button class="btn-signup" type="submit">Login</button>
                    </div>
    
                    <p class="form-footer">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="form-link">Sign up</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
  <!-- JS -->
  <script src="{{ asset('js/login.js') }}"></script>
<!-- Forgot Password Modal -->
<style>
#forgotPasswordModal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.4);
    align-items: center;
    justify-content: center;
}
#forgotPasswordModal .modal-content {
    background: #fff;
    border-radius: 12px;
    padding: 2rem;
    min-width: 320px;
    max-width: 400px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    position: relative;
    margin: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
}
#forgotPasswordModal input.form-input {
    width: 100%;
    margin-bottom: 1rem;
    padding: 0.7rem 1rem;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 1rem;
    box-sizing: border-box;
}
#forgotPasswordModal label {
    width: 100%;
    text-align: left;
    margin-bottom: 0.3rem;
}
#forgotPasswordModal button.btn-signup {
    width: 100%;
    margin-bottom: 1rem;
}
</style>
<div id="forgotPasswordModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
    <div class="modal-content">
        <button type="button" id="closeForgotModal" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:#e74c3c; cursor:pointer;">&times;</button>
        <h3 style="color:#2a3c62; margin-bottom:1rem;">Reset Password</h3>
        <form id="forgotPasswordForm" style="width:100%;">
            <div id="forgotStepEmail">
                <label for="forgotEmail" style="font-weight:600; color:#333;">Email</label>
                <input type="email" id="forgotEmail" name="email" class="form-input" placeholder="Enter your email" required>
                <button type="button" id="sendOtpBtn" class="btn-signup">Send OTP</button>
            </div>
            <div id="forgotStepOtp" style="display:none;">
                <label for="forgotOtp" style="font-weight:600; color:#333;">Enter OTP</label>
                <input type="text" id="forgotOtp" name="otp" class="form-input" placeholder="Enter OTP" required>
                <button type="button" id="verifyOtpBtn" class="btn-signup">Verify OTP</button>
            </div>
            <div id="forgotStepReset" style="display:none;">
                <label for="forgotNewPassword" style="font-weight:600; color:#333;">New Password</label>
                <input type="password" id="forgotNewPassword" name="new_password" class="form-input" placeholder="Enter new password" required>
                <div id="passwordStrength" style="margin-bottom:0.5rem; font-size:0.95rem;"></div>
                <label for="forgotConfirmPassword" style="font-weight:600; color:#333;">Confirm Password</label>
                <input type="password" id="forgotConfirmPassword" name="confirm_password" class="form-input" placeholder="Confirm new password" required>
                <button type="button" id="resetPasswordBtn" class="btn-signup">Reset Password</button>
            </div>
            <div id="forgotError" style="color:#e74c3c; margin-top:0.7rem; display:none;"></div>
            <div id="forgotSuccess" style="color:#28a745; margin-top:0.7rem; display:none;"></div>
        </form>
    </div>
</div>
</body>
</html>