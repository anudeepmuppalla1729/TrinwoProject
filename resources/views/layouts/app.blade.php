<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-authenticated" content="{{ Auth::check() ? 'true' : 'false' }}">
    <title>@yield('title', 'Q&A Forum')</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-search.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rich-text-editor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/code-highlighting.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flash.css') }}">
    <link rel="stylesheet" href="{{ asset('css/button-hover.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .modal-loading-indicator {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('partials.navbar')
    @include('partials.sidebar')
    @include('partials.modals')
    <div class="container">

        <!-- <main class="main-content"> -->
            @yield('content')
        <!-- </main> -->
    </div>
    @stack('scripts')
    <script>
        // Pass TinyMCE API key to JavaScript
        window.tinymceApiKey = '{{ config("tinymce.api_key") }}';
    </script>
    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/navbar-search.js') }}"></script>
    <script src="{{ asset('js/rich-text-editor.js') }}"></script>
    <script src="{{ asset('js/code-highlighting.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
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
</body>
</html>