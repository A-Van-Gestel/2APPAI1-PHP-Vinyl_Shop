<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" />
    <title>@yield('title', 'The Vinyl shop')</title>
    @include('shared.icons')
</head>
<body>
{{--  Navigation  --}}
@include('shared.navigation')
<main class="container mt-3">
    @yield('main', 'Page under construction ...')
</main>
{{--  Footer  --}}
@include('shared.footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
