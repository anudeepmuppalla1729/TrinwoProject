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
            
            <form action="{{ route('profile.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="settings-card">
                    <div class="card-header">
                        <i class="fas fa-user"></i>
                        <h2 class="card-title">Personal Information</h2>
                    </div>
                    <div class="profile-picture-section">
                        <div class="profile-picture">
                            @if($user->profile_pic)
                                <img id="profile-pic-preview" src="{{ Storage::url($user->profile_pic) }}" alt="Profile Picture" style="width:100px;height:100px;border-radius:50%;object-fit:cover;">
                            @else
                                <img id="profile-pic-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=100" alt="Profile Picture" style="width:100px;height:100px;border-radius:50%;object-fit:cover;">
                            @endif
                        </div>
                        <div class="upload-btn">
                            <button type="button" class="btn btn-outline" onclick="document.getElementById('profile_pic').click()">
                                <i class="fas fa-upload"></i> Upload New Photo
                            </button>
                            <input type="file" name="profile_pic" id="profile_pic" accept="image/*" style="display:none;">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Enter your full name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Enter your email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Enter your phone">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="education">Education</label>
                                <input type="text" id="education" name="studying_in" value="{{ old('studying_in', $user->studying_in) }}" placeholder="Enter your education">
                            </div>
                            <div class="form-group">
                                <label for="expert_in">Expertise</label>
                                <input type="text" id="expert_in" name="expert_in" value="{{ old('expert_in', $user->expert_in) }}" placeholder="Enter your expertise">
                            </div>
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" id="age" name="age" value="{{ old('age', $user->age) }}" min="1" max="120" placeholder="Enter your age">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" id="male" name="gender" value="Male" {{ old('gender', $user->gender) == 'Male' ? 'checked' : '' }}>
                                <label for="male">Male</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="female" name="gender" value="Female" {{ old('gender', $user->gender) == 'Female' ? 'checked' : '' }}>
                                <label for="female">Female</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="other" name="gender" value="Other" {{ old('gender', $user->gender) == 'Other' ? 'checked' : '' }}>
                                <label for="other">Other</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                    <div class="action-buttons">
                        <button type="reset" class="btn btn-outline">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
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
                        <input type="text" id="interests" name="interests" placeholder="Type an interest and press enter">
                        <p class="password-info">Add topics you're interested in to get personalized content</p>
                    </div>
                    <div class="interests-container">
                        @php
                            $interests = $user->interests;
                            if (Str::startsWith($interests, '[')) {
                                $interestsArr = json_decode($interests, true) ?: [];
                            } else {
                                $interestsArr = array_filter(array_map('trim', explode(',', $interests)));
                            }
                        @endphp
                        @foreach($interestsArr as $interest)
                            <div class="interest-tag">
                                {{ $interest }} <i class="fas fa-times"></i>
                            </div>
                        @endforeach
                    </div>
                    <div class="action-buttons" style="margin-top: 30px;">
                        <button type="submit" class="btn btn-primary">Update Interests</button>
                    </div>
                </div>
            </form>
            <!-- Change Password Section -->
            <form action="{{ route('password.update') }}" method="POST" class="settings-card">
                @csrf
                <div class="card-header">
                    <i class="fas fa-lock"></i>
                    <h2 class="card-title">Change Password</h2>
                </div>
                
                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" name="current_password" placeholder="Enter your current password" required>
                </div>
                
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="password" placeholder="Enter your new password" required>
                    <div class="password-strength">
                        <div class="password-strength-meter strength-medium"></div>
                    </div>
                    <p class="password-info">Use 8 or more characters with a mix of letters, numbers & symbols</p>
                </div>
                
                <div class="form-group">
                    <label for="confirm-password">Confirm New Password</label>
                    <input type="password" id="confirm-password" name="password_confirmation" placeholder="Confirm your new password" required>
                </div>
                
                <div class="action-buttons">
                    <button type="reset" class="btn btn-outline">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
        <script>
    document.addEventListener('DOMContentLoaded', function() {
        const interestsInput = document.getElementById('interests');
        const interestsContainer = document.querySelector('.interests-container');
        const form = interestsInput.closest('form');
        let interestsArr = [];
        // Initialize from existing tags
        interestsContainer.querySelectorAll('.interest-tag').forEach(tag => {
            interestsArr.push(tag.textContent.trim().replace(/\s*\u00d7\s*$/, ''));
        });
        // Add after interestsArr initialization
        const originalInterestsArr = [...interestsArr];
        // Remove JS for reset interests button
        // Prevent form submit on Enter in interests input
        interestsInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.value.trim() !== '') {
                    const interest = this.value.trim();
                    if (!interestsArr.includes(interest)) {
                        interestsArr.push(interest);
                        const tag = document.createElement('div');
                        tag.className = 'interest-tag';
                        tag.innerHTML = `${interest} <i class='fas fa-times'></i>`;
                        interestsContainer.appendChild(tag);
                        tag.querySelector('i').addEventListener('click', function() {
                            interestsArr = interestsArr.filter(i => i !== interest);
                            tag.remove();
                        });
                    }
                    this.value = '';
                }
            }
        });
        // Remove tag on click (for pre-existing tags)
        interestsContainer.querySelectorAll('.interest-tag i').forEach(icon => {
            icon.addEventListener('click', function() {
                const tag = this.parentElement;
                const interest = tag.textContent.trim().replace(/\s*\u00d7\s*$/, '');
                interestsArr = interestsArr.filter(i => i !== interest);
                tag.remove();
            });
        });
        // On form submit, set interests field to JSON array
        form.addEventListener('submit', function(e) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'interests';
            hidden.value = JSON.stringify(interestsArr);
            form.appendChild(hidden);
        });

    // Password strength and match validation
    const passwordInput = document.getElementById('new-password');
    const confirmInput = document.getElementById('confirm-password');
    const strengthMeter = document.querySelector('.password-strength-meter');
    const passwordForm = passwordInput.closest('form');

    function getStrength(password) {
        let score = 0;
        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        return score;
    }

    function updateStrength() {
        const val = passwordInput.value;
        const score = getStrength(val);
        strengthMeter.className = 'password-strength-meter';
        if (!val) return;
        if (score <= 2) {
            strengthMeter.classList.add('strength-weak');
            strengthMeter.style.width = '30%';
        } else if (score === 3 || score === 4) {
            strengthMeter.classList.add('strength-medium');
            strengthMeter.style.width = '60%';
        } else if (score === 5) {
            strengthMeter.classList.add('strength-strong');
            strengthMeter.style.width = '100%';
        }
    }

    passwordInput.addEventListener('input', updateStrength);

    passwordForm.addEventListener('submit', function(e) {
        // Password match check
        if (passwordInput.value !== confirmInput.value) {
            e.preventDefault();
            alert('Passwords do not match!');
            confirmInput.focus();
            return false;
        }
        // Password strength check
        if (getStrength(passwordInput.value) < 3) {
            e.preventDefault();
            alert('Password is too weak! Use at least 8 characters, with uppercase, lowercase, number, and symbol.');
            passwordInput.focus();
            return false;
        }
    });

    // Profile picture live preview
    const profilePicInput = document.getElementById('profile_pic');
    const profilePicPreview = document.getElementById('profile-pic-preview');
    if (profilePicInput && profilePicPreview) {
        profilePicInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    profilePicPreview.src = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection 