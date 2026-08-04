<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CoRide') }}</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body{
            font-family:'Poppins',sans-serif;
            background:#f4f7fb;
        }

        main{
            min-height:calc(100vh - 140px);
        }

        .page-container{
            max-width:1400px;
            margin:auto;
            padding:25px;
        }

        footer{
            background:#fff;
            border-top:1px solid #e5e7eb;
            padding:18px;
            text-align:center;
            color:#6b7280;
            font-size:14px;
            margin-top:40px;
        }
    </style>
</head>

<body class="antialiased">

<div class="min-h-screen">

    @include('layouts.navigation')

    @isset($header)
        <header class="bg-white shadow-sm border-b">
            <div class="page-container">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="page-container">

        {{ $slot }}

    </main>

    <footer>
        © {{ date('Y') }} CoRide • Smart Carpooling Platform
    </footer>

</div>

</body>
</html>