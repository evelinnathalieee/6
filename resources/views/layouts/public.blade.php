<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Westland Coffee')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @include('partials.vite')
</head>
<body class="min-h-screen bg-zinc-50 text-zinc-900">
    @include('partials.public.header')

    <main class="mx-auto max-w-6xl px-4 py-10">
        @include('partials.flash')
        @yield('content')
    </main>

    @include('partials.public.footer')
</body>
</html>
