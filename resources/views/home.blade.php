<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <link rel="stylesheet" href="/css/app.css">

        <script>
            window.csrf = '{{ csrf_token() }}'
        </script>
    </head>
    <body>
        <div id="app" class="rtl wrapper">
            <router-view :key="$route.fullPath"></router-view>

            <notify :data='@json(session('notify'))' />
        </div>
        <script src="/js/app.js"></script>
    </body>
</html>
