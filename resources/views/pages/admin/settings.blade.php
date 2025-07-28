@extends('layouts.admin')

@section('title', 'Settings | Inqube')

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-cog"></i> System Settings</h1>
    <p class="page-subtitle">Configure forum preferences and options</p>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-sliders-h"></i> General Settings</h2>
    </div>
    
    <div class="card-body" style="padding: 30px;">
        <form id="settings-form" class="admin-form" action="/admin/api/settings" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Forum Name</label>
                    <input type="text" name="forum_name" class="form-control" value="{{ $settings->forum_name ?? 'Inqube' }}" required>
                </div>
                <div class="form-group">
                    <label>Forum Description</label>
                    <input type="text" name="forum_description" class="form-control" value="{{ $settings->forum_description ?? 'A community for developers to share knowledge' }}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Welcome Message</label>
                <textarea name="welcome_message" class="form-control" rows="3" required>{{ $settings->welcome_message ?? 'Welcome to our developer community! Feel free to ask questions and share your knowledge.' }}</textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Default User Role</label>
                    <select name="default_user_role" class="form-control" required>
                        <option value="member" {{ ($settings->default_user_role ?? 'member') == 'member' ? 'selected' : '' }}>Member</option>
                        <option value="moderator" {{ ($settings->default_user_role ?? 'member') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                        <option value="admin" {{ ($settings->default_user_role ?? 'member') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Registration</label>
                    <select name="registration_type" class="form-control" required>
                        <option value="open" {{ ($settings->registration_type ?? 'open') == 'open' ? 'selected' : '' }}>Open to everyone</option>
                        <option value="invitation" {{ ($settings->registration_type ?? 'open') == 'invitation' ? 'selected' : '' }}>Invitation only</option>
                        <option value="approval" {{ ($settings->registration_type ?? 'open') == 'approval' ? 'selected' : '' }}>Admin approval required</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="enable_email_notifications" {{ ($settings->enable_email_notifications ?? true) ? 'checked' : '' }}>
                    Enable email notifications
                </label>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="require_email_verification" {{ ($settings->require_email_verification ?? true) ? 'checked' : '' }}>
                    Require email verification
                </label>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-shield-alt"></i> Security Settings</h2>
    </div>
    
    <div class="card-body" style="padding: 30px;">
        <form id="security-form" class="admin-form" action="/admin/api/settings/security" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Session Timeout (minutes)</label>
                    <input type="number" name="session_timeout" class="form-control" value="{{ $settings->session_timeout ?? 120 }}" min="15" max="1440">
                </div>
                <div class="form-group">
                    <label>Max Login Attempts</label>
                    <input type="number" name="max_login_attempts" class="form-control" value="{{ $settings->max_login_attempts ?? 5 }}" min="3" max="10">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Lockout Duration (minutes)</label>
                    <input type="number" name="lockout_duration" class="form-control" value="{{ $settings->lockout_duration ?? 15 }}" min="5" max="60">
                </div>
                <div class="form-group">
                    <label>Password Minimum Length</label>
                    <input type="number" name="password_min_length" class="form-control" value="{{ $settings->password_min_length ?? 8 }}" min="6" max="20">
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="require_strong_password" {{ ($settings->require_strong_password ?? true) ? 'checked' : '' }}>
                    Require strong passwords (uppercase, lowercase, numbers, symbols)
                </label>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="enable_two_factor" {{ ($settings->enable_two_factor ?? false) ? 'checked' : '' }}>
                    Enable two-factor authentication
                </label>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save Security Settings</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-palette"></i> Appearance Settings</h2>
    </div>
    
    <div class="card-body" style="padding: 30px;">
        <form id="appearance-form" class="admin-form" action="/admin/api/settings/appearance" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Primary Color</label>
                    <input type="color" name="primary_color" class="form-control" value="{{ $settings->primary_color ?? '#4361ee' }}" style="width: 100px; height: 40px;">
                </div>
                <div class="form-group">
                    <label>Secondary Color</label>
                    <input type="color" name="secondary_color" class="form-control" value="{{ $settings->secondary_color ?? '#3f37c9' }}" style="width: 100px; height: 40px;">
                </div>
            </div>
            
            <div class="form-group">
                <label>Logo URL</label>
                <input type="url" name="logo_url" class="form-control" value="{{ $settings->logo_url ?? '' }}" placeholder="https://example.com/logo.png">
            </div>
            
            <div class="form-group">
                <label>Favicon URL</label>
                <input type="url" name="favicon_url" class="form-control" value="{{ $settings->favicon_url ?? '' }}" placeholder="https://example.com/favicon.ico">
            </div>
            
            <div class="form-group">
                <label>Custom CSS</label>
                <textarea name="custom_css" class="form-control" rows="6" placeholder="/* Add your custom CSS here */">{{ $settings->custom_css ?? '' }}</textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save Appearance Settings</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-database"></i> System Information</h2>
    </div>
    
    <div class="card-body" style="padding: 30px;">
        <div class="form-row">
            <div class="form-group">
                <label>Laravel Version</label>
                <input type="text" class="form-control" value="{{ app()->version() }}" readonly>
            </div>
            <div class="form-group">
                <label>PHP Version</label>
                <input type="text" class="form-control" value="{{ phpversion() }}" readonly>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Database</label>
                <input type="text" class="form-control" value="{{ config('database.default') }}" readonly>
            </div>
            <div class="form-group">
                <label>Environment</label>
                <input type="text" class="form-control" value="{{ config('app.env') }}" readonly>
            </div>
        </div>
        
        <div class="form-group">
            <button type="button" class="btn btn-outline" onclick="clearCache()">
                <i class="fas fa-broom"></i> Clear Cache
            </button>
            <button type="button" class="btn btn-outline" onclick="optimizeSystem()">
                <i class="fas fa-rocket"></i> Optimize System
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Settings specific JavaScript
class SettingsManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Settings form submission
        document.getElementById('settings-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveSettings(e.target);
        });

        // Security form submission
        document.getElementById('security-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveSecuritySettings(e.target);
        });

        // Appearance form submission
        document.getElementById('appearance-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveAppearanceSettings(e.target);
        });
    }

    async saveSettings(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        submitButton.disabled = true;
        submitButton.textContent = 'Saving...';

        try {
            const response = await fetch('/admin/api/settings', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const result = await response.json();
                window.adminDashboard.showNotification(result.message || 'Settings saved successfully', 'success');
            } else {
                const error = await response.json();
                window.adminDashboard.showNotification(error.message || 'Error saving settings', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }

    async saveSecuritySettings(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        submitButton.disabled = true;
        submitButton.textContent = 'Saving...';

        try {
            const response = await fetch('/admin/api/settings/security', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const result = await response.json();
                window.adminDashboard.showNotification(result.message || 'Security settings saved successfully', 'success');
            } else {
                const error = await response.json();
                window.adminDashboard.showNotification(error.message || 'Error saving security settings', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }

    async saveAppearanceSettings(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        submitButton.disabled = true;
        submitButton.textContent = 'Saving...';

        try {
            const response = await fetch('/admin/api/settings/appearance', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const result = await response.json();
                window.adminDashboard.showNotification(result.message || 'Appearance settings saved successfully', 'success');
            } else {
                const error = await response.json();
                window.adminDashboard.showNotification(error.message || 'Error saving appearance settings', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }

    async clearCache() {
        if (!confirm('Are you sure you want to clear the cache? This may temporarily slow down the application.')) return;

        try {
            const response = await fetch('/admin/api/system/clear-cache', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const result = await response.json();
                window.adminDashboard.showNotification(result.message || 'Cache cleared successfully', 'success');
            } else {
                const error = await response.json();
                window.adminDashboard.showNotification(error.message || 'Error clearing cache', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        }
    }

    async optimizeSystem() {
        if (!confirm('Are you sure you want to optimize the system? This may take a few moments.')) return;

        try {
            const response = await fetch('/admin/api/system/optimize', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const result = await response.json();
                window.adminDashboard.showNotification(result.message || 'System optimized successfully', 'success');
            } else {
                const error = await response.json();
                window.adminDashboard.showNotification(error.message || 'Error optimizing system', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.adminDashboard.showNotification('Network error', 'error');
        }
    }
}

// Initialize settings manager
let settingsManager;

document.addEventListener('DOMContentLoaded', () => {
    settingsManager = new SettingsManager();
});

// Global functions for onclick handlers
function clearCache() {
    settingsManager.clearCache();
}

function optimizeSystem() {
    settingsManager.optimizeSystem();
}
</script>
@endpush 