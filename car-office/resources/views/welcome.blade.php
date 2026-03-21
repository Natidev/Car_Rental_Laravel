<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Car Rental Office Management System - Centralize your branches, agreements, fleet, and legal approvals">

        <title>{{ config('app.name', 'Car Office') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                 tailwind.config = {
                    darkMode: 'class',
                    theme: {
                        extend: {
                            fontFamily: {
                                sans: ['Outfit', 'sans-serif'],
                            },
                            colors: {
                                primary: {
                                    50: '#f0f9ff',
                                    100: '#e0f2fe',
                                    200: '#bae6fd',
                                    300: '#7dd3fc',
                                    400: '#38bdf8',
                                    500: '#0ea5e9',
                                    600: '#0284c7',
                                    700: '#0369a1',
                                    800: '#075985',
                                    900: '#0c4a6e',
                                }
                            },
                            animation: {
                                'float': 'float 6s ease-in-out infinite',
                                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                            },
                            keyframes: {
                                float: {
                                    '0%, 100%': { transform: 'translateY(0)' },
                                    '50%': { transform: 'translateY(-20px)' },
                                }
                            }
                        }
                    }
                }
            </script>
            <style>
                body { font-family: 'Outfit', sans-serif; }
                .glass {
                    background: rgba(255, 255, 255, 0.7);
                    backdrop-filter: blur(20px);
                    -webkit-backdrop-filter: blur(20px);
                }
                .dark .glass {
                    background: rgba(15, 15, 15, 0.8);
                }
            </style>
        @endif
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">

        <!-- Navigation -->
        <header class="fixed top-0 left-0 right-0 z-50">
            <div class="glass border-b border-gray-200/50 dark:border-gray-700/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent">
                                {{ config('app.name', 'Car Rental Office') }}
                            </span>
                        </div>
                        <nav class="flex items-center gap-3">
                            @auth
                                <a href="{{ url('/admin') }}" class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 hover:scale-105 transition-all duration-300">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ url('/admin/login') }}" class="px-5 py-2.5 rounded-lg text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                    Sign In
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ url('/admin') }}" class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 hover:scale-105 transition-all duration-300">
                                        Get Started
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <main class="pt-24 pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Hero Card -->
                <div class="relative mb-16">
                    <div class="relative glass rounded-3xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="grid lg:grid-cols-2 gap-8 p-8 lg:p-12 items-center">
                            <div class="space-y-6">
                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 text-sm font-medium">
                                    <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                                    Enterprise Solution
                                </div>
                                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight">
                                    <span class="gradient-text">Car Rental</span>
                                    <br />
                                    <span class="text-gray-900 dark:text-white">Office Manager</span>
                                </h1>
                                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-xl">
                                    Streamline your operations with centralized branch management, 
                                    agreement workflows, fleet tracking, and real-time reporting—all 
                                    in one powerful platform.
                                </p>
                                <div class="flex flex-wrap gap-4">
                                    @auth
                                        <a href="{{ url('/admin/login') }} class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold shadow-lg shadow-primary-500/30 hover:shadow-xl hover:scale-105 transition-all duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Go to Dashboard
                                        </a>
                                    @else
                                        <a href="{{ url('/admin/login') }} " class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold shadow-lg shadow-primary-500/30 hover:shadow-xl hover:scale-105 transition-all duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                            Sign In Now
                                        </a>
                                        <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold hover:border-primary-500 hover:text-primary-600 transition-colors">
                                            Learn More
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-16 justify-center">
                    <!-- Feature 1 -->
                    <div class="glass rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:border-primary-300 dark:hover:border-primary-700 hover:shadow-xl hover:shadow-primary-500/10 transition-all duration-300 group">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4 shadow-lg group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Branch Management</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Track branch lifecycle and utility service health from one control panel.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="glass rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:border-accent-300 dark:hover:border-accent-700 hover:shadow-xl hover:shadow-accent-500/10 transition-all duration-300 group">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-orange-600 flex items-center justify-center mb-4 shadow-lg group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Agreement Workflow</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Draft, review, approve, reject, and activate agreements with legal compliance.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="glass rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:border-green-300 dark:hover:border-green-700 hover:shadow-xl hover:shadow-green-500/10 transition-all duration-300 group">
                        <div class="w-14 h-14 rounded-2xl  flex items-center justify-center mb-4 shadow-lg group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Vehicle Maintenance</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Automated service requests and maintenance schedules keep your fleet ready.</p>
                    </div>

                </div>

    
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-8 border-t border-gray-200/50 dark:border-gray-700/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} {{ config('app.name', 'Car Rental Office') }}. Built with Laravel & Filament.
                </p>
            </div>
        </footer>
    </body>
</html>
