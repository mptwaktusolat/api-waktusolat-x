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

    <!-- Analytics -->
    <script defer src="https://umami.iqfareez.com/analitik.js" data-website-id="d4373d39-35fb-4995-b5e5-931b510181cd">
    </script>

    <!-- favicons -->
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Waktu Solat API" />
    <link rel="manifest" href="/site.webmanifest" />

    {{-- Prevent FOUC (Flash of Unstyled Content) when load. --}}
    <script>
        // use 'colorMode' key to match the key used in scalar swagger page.
        const t = localStorage.getItem('colorMode');
        if (t === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        } else if (t === 'dark') {
            document.documentElement.setAttribute('data-theme', 'black');
        } else {
            // System: detect from OS preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-theme', 'black');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-base-100 min-h-screen flex flex-col antialiased">
    <x-nav-header />
    @yield('content')
</body>

</html>
