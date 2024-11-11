<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ session('theme') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Font Awesome and Alpine.js -->
    <script src="https://kit.fontawesome.com/f9e2e15b32.js" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Main App Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Pushed Styles -->
    @stack('styles')

</head>
<body>
    <!-- Header Component -->
    <x-header/>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Module Scripts
    <script type="module">
        @stack('modulescripts')
    </script> -->

    <!-- Pushed Scripts (for pages like the calendar) -->
    @stack('scripts')

</body>
</html>
