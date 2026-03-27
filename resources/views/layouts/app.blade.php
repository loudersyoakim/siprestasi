<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIARPRESTASI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts-3d.js') }}"></script>
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
    {{-- Modal Container --}}
<div id="help-modal" class="fixed inset-0 z-[100] hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden">
        {{-- Isi Konten Bantuan --}}
        @include('partials.bantuan') 

        <div class="p-4 bg-gray-50 border-t flex justify-end">
            <button onclick="toggleHelpModal()" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300">Tutup</button>
        </div>
    </div>
</div>
</body>
{{-- SCRIPT JAVASCRIPT UNIVERSAL --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownToggles = document.querySelectorAll('.nav-dropdown-toggle');
        
        dropdownToggles.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const menu = this.nextElementSibling;
                const icon = this.querySelector('.bi-chevron-down');
                
                if(menu.classList.contains('hidden')) {
                    menu.classList.remove('hidden');
                    menu.classList.add('block');
                    icon.classList.add('rotate-180', 'text-[#006633]');
                } else {
                    menu.classList.remove('block');
                    menu.classList.add('hidden');
                    icon.classList.remove('rotate-180', 'text-[#006633]');
                }
            });
        });
    });

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('-translate-x-full');
    }
</script>
</html>