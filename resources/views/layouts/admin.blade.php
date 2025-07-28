<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Q&A Forum</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rich-text-editor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/code-highlighting.css') }}">
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PGNpcmNsZSBjeD0iNTAiIGN5PSI1MCIgcj0iNTAiIGZpbGw9IiM0MzYxZWUiLz48cGF0aCBkPSJNNTAgMjVDMzYgMjUgMjUgMzYgMjUgNTBzMTEgMjUgMjUgMjUgMjUtMTEgMjUtMjVTMzkgMjUgNTAgMjV6bTAgNDBjLTggMC0xNS03LTE1LTE1czctMTUgMTUtMTUgMTUgNyAxNSAxNS03IDE1LTE1IDE1eiIgZmlsbD0iI2ZmZiIvPjxjaXJjbGUgY3g9IjUwIiBjeT0iNTAiIHI9IjEwIiBmaWxsPSIjZmZmIi8+PC9zdmc+" alt="Admin">
            <div class="admin-info">
                <h3>{{ auth()->guard('admin')->user()->name ?? 'Admin User' }}</h3>
                <p>Super Administrator</p>
            </div>
        </div>
        
        <div class="sidebar-menu">
            <a  class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="menu-label">Management</div>
            
            <a href="{{ route('admin.reports') }}" class="menu-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="fas fa-flag"></i>
                <span>Reports</span>
            </a>
            
            <a href="{{ route('admin.users') }}" class="menu-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            
            <a href="{{ route('admin.questions') }}" class="menu-item {{ request()->routeIs('admin.questions') ? 'active' : '' }}">
                <i class="fas fa-comments"></i>
                <span>Questions</span>
            </a>
            
            <a href="{{ route('admin.answers') }}" class="menu-item {{ request()->routeIs('admin.answers') ? 'active' : '' }}">
                <i class="fas fa-pen"></i>
                <span>Answers</span>
            </a>
            
            <a href="{{ route('admin.posts') }}" class="menu-item {{ request()->routeIs('admin.posts') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i>
                <span>Posts</span>
            </a>
            
            <div class="menu-label">Settings</div>

            
            <a href="{{ route('admin.logout') }}" class="menu-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="main-content">
        @yield('content')
    </div>

    <!-- Notification Container -->
    <div id="notification-container" class="notification-container"></div>

    <script>
      window.s3BaseUrl = "{{ config('filesystems.disks.s3.url') ? rtrim(config('filesystems.disks.s3.url'), '/') . '/' : '' }}";
    </script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/rich-text-editor.js') }}"></script>
    <script src="{{ asset('js/code-highlighting.js') }}"></script>
    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('prismjs/prism.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-python.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-javascript.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-php.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-java.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-cpp.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-csharp.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-ruby.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-go.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-rust.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-swift.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-kotlin.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-typescript.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-sql.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-bash.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-powershell.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-yaml.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-json.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-xml.js') }}"></script>
    <script src="{{ asset('prismjs/components/prism-markdown.js') }}"></script>
    @stack('scripts')
</body>
</html> 