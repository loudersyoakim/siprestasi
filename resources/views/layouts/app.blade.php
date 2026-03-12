<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIARPRESTASI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts-3d.js') }}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/11.3.0/highcharts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/11.3.0/highcharts-3d.min.js"></script> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900">

    <div class="flex min-h-screen">
        @include('layouts.app.sidebar')
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden opacity-0 transition-opacity duration-300 md:hidden"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-x-hidden">
            @include('layouts.app.header')

            <main class="p-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>

</html>