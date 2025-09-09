<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Service Center') }} - Company Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('company.dashboard') }}" class="text-xl font-bold text-gray-800">
                                {{ config('app.name', 'Service Center') }}
                            </a>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <!-- Navigation Links -->
                            <a href="{{ route('company.dashboard') }}" class="border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('company.dashboard') ? 'border-blue-500' : 'border-transparent hover:border-gray-300 hover:text-gray-700' }}">
                                Dashboard
                            </a>
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="text-gray-700 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('company.brands.*') ? 'border-blue-500' : 'border-transparent hover:border-gray-300' }}">
                                    <span>Catalog</span>
                                    <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute z-10 -ml-4 mt-3 transform px-2 w-screen max-w-md sm:px-0 lg:ml-0 lg:left-1/2 lg:-translate-x-1/2">
                                    <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden">
                                        <div class="relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8">
                                            <a href="{{ route('company.brands.index') }}" class="-m-3 p-3 flex items-start rounded-lg hover:bg-gray-50 {{ request()->routeIs('company.brands.*') ? 'bg-gray-50' : '' }}">
                                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-blue-500 text-white sm:h-12 sm:w-12">
                                                    <i class="fas fa-tag"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-base font-medium text-gray-900">Brands</p>
                                                    <p class="mt-1 text-sm text-gray-500">Manage your product brands</p>
                                                </div>
                                            </a>
                                            <a href="{{ route('company.products.index') }}" class="-m-3 p-3 flex items-start rounded-lg hover:bg-gray-50 {{ request()->routeIs('company.products.*') ? 'bg-gray-50' : '' }}">
                                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-green-500 text-white sm:h-12 sm:w-12">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-base font-medium text-gray-900">Products</p>
                                                    <p class="mt-1 text-sm text-gray-500">Manage your products</p>
                                                </div>
                                            </a>
                                            <a href="{{ route('company.fault-types.index') }}" class="-m-3 p-3 flex items-start rounded-lg hover:bg-gray-50 {{ request()->routeIs('company.fault-types.*') ? 'bg-gray-50' : '' }}">
                                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-purple-500 text-white sm:h-12 sm:w-12">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-base font-medium text-gray-900">Fault Types</p>
                                                    <p class="mt-1 text-sm text-gray-500">Manage fault types</p>
                                                </div>
                                            </a>
                                            <a href="{{ route('company.areas.index') }}" class="-m-3 p-3 flex items-start rounded-lg hover:bg-gray-50 {{ request()->routeIs('company.areas.*') ? 'bg-gray-50' : '' }}">
                                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-yellow-500 text-white sm:h-12 sm:w-12">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-base font-medium text-gray-900">Areas</p>
                                                    <p class="mt-1 text-sm text-gray-500">Manage service areas</p>
                                                </div>
                                            </a>
                                            <a href="{{ route('company.items.index') }}" class="-m-3 p-3 flex items-start rounded-lg hover:bg-gray-50 {{ request()->routeIs('company.items.*') ? 'bg-gray-50' : '' }}">
                                                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-indigo-500 text-white sm:h-12 sm:w-12">
                                                    <i class="fas fa-boxes"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-base font-medium text-gray-900">Items</p>
                                                    <p class="mt-1 text-sm text-gray-500">Manage inventory items</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- Profile dropdown -->
                        <div x-data="{ open: false }" class="ml-3 relative">
                            <div>
                                <button @click="open = !open" type="button" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-blue-500 text-white flex items-center justify-center">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                    <svg class="ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="user-menu-item-2">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex items-center sm:hidden">
                        <!-- Mobile menu button -->
                        <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <!-- Icon when menu is closed -->
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <!-- Icon when menu is open -->
                            <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div class="sm:hidden" id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('company.dashboard') }}" class="bg-blue-50 border-blue-500 text-blue-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('company.dashboard') ? 'bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('company.brands.index') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('company.brands.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}">
                        Brands
                    </a>
                    <a href="{{ route('company.products.index') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('company.products.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}">
                        Products
                    </a>
                    <a href="{{ route('company.fault-types.index') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('company.fault-types.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}">
                        Fault Types
                    </a>
                    <a href="{{ route('company.areas.index') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('company.areas.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}">
                        Areas
                    </a>
                    <a href="{{ route('company.items.index') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('company.items.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : '' }}">
                        Items
                    </a>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-blue-500 text-white flex items-center justify-center">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Your Profile</a>
                        <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                    this.setAttribute('aria-expanded', !expanded);
                    mobileMenu.classList.toggle('hidden');
                    
                    // Toggle icons
                    const icons = this.querySelectorAll('svg');
                    icons.forEach(icon => icon.classList.toggle('hidden'));
                });
            }
        });
        
        // Alpine.js initialization
        document.addEventListener('alpine:init', () => {
            // Alpine.js components can be initialized here
        });
    </script>
</body>
</html>
