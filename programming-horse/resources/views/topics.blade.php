<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Programming HORSE') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Urbanist';
        }
        .logo-image {
            height: 60px;
            width: 75px;
            border-radius: 10px;
        }
        .topic-title {
            font-size: 40px;
            text-align: center;
            margin-top: 25px;
        }
        .topic-grid {
            border-radius: 8px;
            background-color: black;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            padding: 20px;
            width: 600px;
            margin: 0 auto;
            margin-top: 60px;
            padding-top: 0;
        }
        .topic-item {
            border-radius: 10px;
            margin-top: 35px;
            font-family: 'Urbanist';
            padding-bottom: 20px;
            width: 165px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease; 
        }
        .topic-item:hover {
            transform: scale(1.1); /* Enlarge the item when hovered */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Enhanced shadow for hovering effect */
        }
        .topic-text {
            font-size: 20px;
            padding-top: 13px;
        }
        .start-button-container {
            margin-left: 830px;
            margin-top: 80px;
        }
        .start-button {
            height: 80px;
            width: 270px;
            text-align: center;
            margin: 0 auto;
            padding-left: 70px;
            background-color: black;
        }
        .page-title {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <!-- Add your logo here -->
                        <img src="{{ asset('images/horse_logo.png') }}" class="logo-image" alt="Logo">
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Main Menu') }}
                        </x-nav-link>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name ?? "No category" }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Main Menu') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name ?? "No category"  }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email ?? "No category"  }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <header class="bg-white dark:bg-gray-800 shadow">            
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight page-title">
                {{ __('Topics') }}
            </h2>
        </div>
    </header> 
    <div class="topic-grid">
        <div class="bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 topic-item" onclick="toggleColor(this)">
            <p class="topic-text">All Topics</p>
        </div>
        <div class="bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 topic-item" onclick="toggleColor(this)">
            <p class="topic-text">Data Types</p>
        </div>
        <div class="bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 topic-item" onclick="toggleColor(this)">
            <p class="topic-text">Arrays</p>
        </div>
        <div class="bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 topic-item" onclick="toggleColor(this)">
            <p class="topic-text">Functions</p>
        </div>
        <div class="bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 topic-item" onclick="toggleColor(this)">
            <p class="topic-text">Objects</p>
        </div>
        <div class="bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 topic-item" onclick="toggleColor(this)">
            <p class="topic-text">Strings</p>
        </div>
        <div class="bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 topic-item" onclick="toggleColor(this)">
            <p class="topic-text">Syntax</p>
        </div>
        <div class="bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 topic-item" onclick="toggleColor(this)">
            <p class="topic-text">Loops</p>
        </div>
        <div class="bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 topic-item" onclick="toggleColor(this)">
            <p class="topic-text">Operators</p>
        </div> 
    </div>

</div>
</body>
<script>
    function toggleColor(element) {
        // If the current background color is white, change it to grey
        if (element.style.backgroundColor === 'white' || element.style.backgroundColor === '') {
            element.style.backgroundColor = 'grey';
        } else {
            // Change it back to white when clicked again
            element.style.backgroundColor = 'white';
        }
    }
</script>
</html>