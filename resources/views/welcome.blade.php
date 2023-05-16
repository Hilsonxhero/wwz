<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <title>Laravel</title>
</head>

<body class="antialiased">
    <div class="ml-4 text-2xl py-8  text-gray-700 text-center sm:ml-0">
        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
    </div>
    <div id="app"></div>
</body>

</html>
