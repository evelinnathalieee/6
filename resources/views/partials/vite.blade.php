@php
    $hasVite = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));
@endphp

@if ($hasVite)
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    <link rel="stylesheet" href="{{ asset('fallback.css') }}">
@endif

