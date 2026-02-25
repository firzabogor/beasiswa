<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SDMO - Sistem Konsultasi')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>

    @include('partials.navbar')

    <div class="container">
        @include('partials.sidebar')

        <main>
            @yield('content')
        </main>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/script.js') }}"></script>
    @yield('scripts')
</body>
</html>
