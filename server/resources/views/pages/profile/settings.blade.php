@extends('layouts.profile')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
<link rel="stylesheet" href="{{ asset('css/settings.css') }}">

@endpush
@section('title', 'Following - User Settings')

@section('main_content')

<div class="settings-container">
            <div class="settings-header">
                <h1 class="settings-title">Profile Settings</h1>
                <p class="settings-subtitle">Manage your profile information and account settings</p>
            </div>
            
            <!-- Profile Information Section -->
            <div class="settings-card">
                <div class="card-header">
                    <i class="fas fa-user"></i>
                    <h2 class="card-title">Personal Information</h2>
                </div>
                
                <div class="profile-picture-section">
                    <div class="profile-picture"></div>
                    <div class="upload-btn">
                        <button class="btn btn-outline">
                            <i class="fas fa-upload"></i> Upload New Photo
                        </button>
                        <input type="file" accept="image/*">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" value="Alex Morgan" placeholder="Enter your full name">
                        </div>
                        
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" value="alex_morgan" placeholder="Enter your username">
                        </div>
                    </div>
                    
                    <div class="form-col">
                        <div class="form-group">
                            <label for="education">Education</label>
                            <input type="text" id="education" value="Master in Computer Science" placeholder="Enter your education">
                        </div>
                        
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="age" value="28" min="18" max="100" placeholder="Enter your age">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Gender</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="male" name="gender" value="male" checked>
                            <label for="male">Male</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="female" name="gender" value="female">
                            <label for="female">Female</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="other" name="gender" value="other">
                            <label for="other">Other</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="prefer-not" name="gender" value="prefer-not">
                            <label for="prefer-not">Prefer not to say</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" rows="4" placeholder="Tell us about yourself...">Senior developer with 8+ years of experience in web technologies. Passionate about creating elegant solutions to complex problems.</textarea>
                </div>
                
                <div class="action-buttons">
                    <button class="btn btn-outline">Cancel</button>
                    <button class="btn btn-primary">Save Changes</button>
                </div>
            </div>
            
            <!-- Interests Section -->
            <div class="settings-card">
                <div class="card-header">
                    <i class="fas fa-heart"></i>
                    <h2 class="card-title">Interests</h2>
                </div>
                
                <div class="form-group">
                    <label for="interests">Add Interests</label>
                    <input type="text" id="interests" placeholder="Type an interest and press enter">
                    <p class="password-info">Add topics you're interested in to get personalized content</p>
                </div>
                
                <div class="interests-container">
                    <div class="interest-tag">
                        Web Development <i class="fas fa-times"></i>
                    </div>
                    <div class="interest-tag">
                        JavaScript <i class="fas fa-times"></i>
                    </div>
                    <div class="interest-tag">
                        React.js <i class="fas fa-times"></i>
                    </div>
                    <div class="interest-tag">
                        UI/UX Design <i class="fas fa-times"></i>
                    </div>
                    <div class="interest-tag">
                        Cloud Computing <i class="fas fa-times"></i>
                    </div>
                    <div class="interest-tag">
                        Artificial Intelligence <i class="fas fa-times"></i>
                    </div>
                </div>
                
                <div class="action-buttons" style="margin-top: 30px;">
                    <button class="btn btn-outline">Reset Interests</button>
                    <button class="btn btn-primary">Update Interests</button>
                </div>
            </div>
            
            <!-- Change Password Section -->
            <div class="settings-card">
                <div class="card-header">
                    <i class="fas fa-lock"></i>
                    <h2 class="card-title">Change Password</h2>
                </div>
                
                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" placeholder="Enter your current password">
                </div>
                
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" placeholder="Enter your new password">
                    <div class="password-strength">
                        <div class="password-strength-meter strength-medium"></div>
                    </div>
                    <p class="password-info">Use 8 or more characters with a mix of letters, numbers & symbols</p>
                </div>
                
                <div class="form-group">
                    <label for="confirm-password">Confirm New Password</label>
                    <input type="password" id="confirm-password" placeholder="Confirm your new password">
                </div>
                
                <div class="action-buttons">
                    <button class="btn btn-outline">Cancel</button>
                    <button class="btn btn-primary">Change Password</button>
                </div>
            </div>
        </div>
        <script>
        const interestsInput = document.getElementById('interests');
        const interestsContainer = document.querySelector('.interests-container');
        
        interestsInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim() !== '') {
                const interest = this.value.trim();
                
                const tag = document.createElement('div');
                tag.className = 'interest-tag';
                tag.innerHTML = `${interest} <i class="fas fa-times"></i>`;
                
                interestsContainer.appendChild(tag);
                this.value = '';
                
                // Add remove functionality
                tag.querySelector('i').addEventListener('click', function() {
                    tag.remove();
                });
            }
        });
        
        // Add existing remove functionality
        document.querySelectorAll('.interest-tag i').forEach(icon => {
            icon.addEventListener('click', function() {
                this.parentElement.remove();
            });
        });
        
        // Password strength simulation
        const passwordInput = document.getElementById('new-password');
        const strengthMeter = document.querySelector('.password-strength-meter');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            
            if (password.length === 0) {
                strengthMeter.className = 'password-strength-meter';
                strengthMeter.style.width = '0';
                return;
            }
            
            if (password.length < 6) {
                strengthMeter.className = 'password-strength-meter strength-weak';
            } else if (password.length < 10) {
                strengthMeter.className = 'password-strength-meter strength-medium';
            } else {
                strengthMeter.className = 'password-strength-meter strength-strong';
            }
        });
    </script>
@endsection 