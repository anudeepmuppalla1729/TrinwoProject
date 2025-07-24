<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Inqube - Q&amp;A and Blogging</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style type="text/tailwindcss">
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div>
                <img alt="Inqube logo" class="h-10"
                    src="{{ asset('/assets/logo.png') }}" />
            </div>
            <nav class="hidden md:flex items-center space-x-8">
                <a class="text-gray-600 hover:text-blue-600" href="{{ route('login') }}">Home</a>
                <a class="text-gray-600 hover:text-blue-600" href="{{ route('questions') }}">Questions</a>
                <a class="text-gray-600 hover:text-blue-600" href="{{ route('dashboard') }}">Dashboard</a>
            </nav>
            <div class="hidden md:flex items-center space-x-4">
                <a class="text-gray-600 hover:text-blue-600" href="{{ route('login') }}">Login</a>
                <a class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition duration-300"
                    href="{{ route('register') }}">Sign Up</a>
            </div>
            <div class="md:hidden">
                <button class="text-gray-600 focus:outline-none" onclick="toggleMobileMenu()">
                    <span class="material-icons">
                        menu
                    </span>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden fixed inset-0 z-50 bg-white p-5" style="top: 70px;">
                <div class="flex flex-col space-y-4">
                    <a class="text-gray-600 hover:text-blue-600 py-2 border-b border-gray-100" href="{{ route('login') }}">Home</a>
                    <a class="text-gray-600 hover:text-blue-600 py-2 border-b border-gray-100" href="{{ route('questions') }}">Questions</a>
                    <a class="text-gray-600 hover:text-blue-600 py-2 border-b border-gray-100" href="{{ auth()->check() ? route('dashboard') : route('login') }}">Dashboard</a>
                    <a class="text-gray-600 hover:text-blue-600 py-2 border-b border-gray-100" href="{{ route('login') }}">Login</a>
                    <a class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition duration-300 text-center mt-2" href="{{ route('register') }}">Sign Up</a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <section class="bg-white">
            <div class="container mx-auto px-6  pt-[10vh] pb-20 flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4 leading-tight">Find Answers, Share Your
                        Insights.</h1>
                    <p class="text-gray-600 text-lg mb-8">Join a community of curious minds. Ask questions, get expert
                        answers, and share your own insights through our collaborative Q&amp;A platform and blog.</p>
                    <div class="flex space-x-4">
                        <a class="bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition duration-300 text-lg"
                            href="{{ auth()->check() ? route('questions.create') : route('login') }}">Ask a Question</a>
                        <a class="bg-gray-200 text-gray-800 px-6 py-3 rounded-full hover:bg-gray-300 transition duration-300 text-lg"
                            href="{{ auth()->check() ? route('posts.create') : route('login') }}">Share an Insight</a>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <img alt="Illustration of people collaborating on ideas" class="w-full max-w-md"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBGJ45TGad1b2WL5HFd0mdFualxo6otk4ZVyljpJaQxdukny1NZd5kNDiOh1KEIKr9XHNWsdG4A5lpZpGRrNmhPB856r9No9m7rHPTtv-Vn3L9Uu2IM58RGOiHLANzizcg8s2zBeVv_i15kjAbPyzL-CcwTRPKaSd6RImxhTIZvVtTiB5Klls1jj79Z-BVAcJdi5ReIyld132FnH8F6uYZxgMu1DmZPGUN57F9UnC_fjcAPV5QscAjpKDGLT44Il61USqTuU83_Ge0" />
                </div>
            </div>
        </section>
        <section class="py-20">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">How It Works</h2>
                <div class="grid md:grid-cols-3 gap-12">
                    <div class="text-center">
                        <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons text-blue-600 text-4xl">
                                question_answer
                            </span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Ask Anything</h3>
                        <p class="text-gray-600">Post your questions and get answers from a vibrant community of users.
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="bg-orange-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons text-orange-500 text-4xl">
                                lightbulb
                            </span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Share Your Insights</h3>
                        <p class="text-gray-600">Help others by answering questions and writing insightful posts on
                            topics you're passionate about.</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons text-green-500 text-4xl">
                                group
                            </span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Build Your Community</h3>
                        <p class="text-gray-600">Connect with like-minded individuals, follow topics, and build your
                            reputation as a knowledgeable contributor.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="bg-gray-800 text-white">
        <div class="container mx-auto px-6 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h4 class="font-bold text-lg mb-4">Inqube</h4>
                    <p class="text-gray-400">Your space to ask, answer, and explore.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a class="text-gray-400 hover:text-white" href="{{ route('login') }}">Home</a></li>
                        <li><a class="text-gray-400 hover:text-white" href="{{ route('questions') }}">Questions</a></li>
                        <li><a class="text-gray-400 hover:text-white" href="{{ route('posts.index') }}">Insights</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a class="text-gray-400 hover:text-white" href="{{ auth()->check() ? route('dashboard') : route('login') }}">Dashboard</a></li>
                        <li><a class="text-gray-400 hover:text-white" href="{{ auth()->check() ? route('profile.edit') : route('login') }}">Profile</a></li>
                        <li><a class="text-gray-400 hover:text-white" href="{{ auth()->check() ? route('profile.settings') : route('login') }}">Settings</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a class="text-gray-400 hover:text-white" href="#"><svg aria-hidden="true" class="w-6 h-6"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path clip-rule="evenodd"
                                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                    fill-rule="evenodd"></path>
                            </svg></a>
                        <a class="text-gray-400 hover:text-white" href="#"><svg aria-hidden="true" class="w-6 h-6"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84">
                                </path>
                            </svg></a>
                        <a class="text-gray-400 hover:text-white" href="#"><svg aria-hidden="true" class="w-6 h-6"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path clip-rule="evenodd"
                                    d="M12 2C6.477 2 2 6.477 2 12.019c0 4.438 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.009-.868-.014-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.031-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.338 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.001 10.001 0 0022 12.019C22 6.477 17.523 2 12 2z"
                                    fill-rule="evenodd"></path>
                            </svg></a>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8 text-center text-gray-400 text-sm">
                © 2025 Inqube. All Rights Reserved.
            </div>
        </div>
    </footer>
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
            } else {
                mobileMenu.classList.add('hidden');
            }
        }
    </script>
</body>

</html>