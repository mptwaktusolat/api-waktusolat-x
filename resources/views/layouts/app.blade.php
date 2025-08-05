<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Primary Meta Tags -->
    <title>Waktu Solat API</title>
    <meta name="title" content="Waktu Solat API" />
    <meta name="description"
        content="Get access to Malaysia prayer times and zones for your app or website. Easy, free and simple." />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="Waktu Solat API" />
    <meta property="og:description"
        content="Get access to Malaysia prayer times and zones for your app or website. Easy, free and simple." />
    <meta property="og:image" content="{{ asset('images/metaimage.png') }}" />

    <!-- X (Twitter) -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="Waktu Solat API" />
    <meta property="twitter:description"
        content="Get access to Malaysia prayer times and zones for your app or website. Easy, free and simple." />
    <meta property="twitter:image" content="{{ asset('images/metaimage.png') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@yield('body')

</html>
