<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('dark-mode') === 'true' }" x-init="$watch('darkMode', value => localStorage.setItem('dark-mode', value))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Programming HORSE</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- Scripts -->
        @vite(['resources/css/app.css',  'resources/js/app.js', 'resources/css/dark-mode.css'])

    <style>
           

            h1 {
                font-size: 30px; 
                margin-bottom: 10px;
                font-weight: 900;
            }

            p {
                font-size: 20px;
            }


    

    </style>

    </head>
    <body style="font-family: 'Urbanist';" class="min-h-screen bg-gray-100 font-sans antialiased bg-gray-100 dark:bg-gray-700 text-black dark:text-white">
        @include('layouts.navigation')



            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
          
        </div>

    </body>

</html>
