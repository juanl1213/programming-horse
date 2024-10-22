<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en" class="{{ auth()->user()?->dark_mode ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    

        <style>
            /* Light mode (default) */
            body {
                background-color: white;
                color: black;
            }

            /* Dark mode */
            .dark_mode body {
                background-color: #333;
                color: white;
            }
        </style>
</head>
<body>
    @yield('content')
</body>
</html>