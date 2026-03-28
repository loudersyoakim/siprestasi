<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIARPRESTASI</title>
    
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.min.css') }}">
    
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


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggles = document.querySelectorAll('.nav-dropdown-toggle');
        
        toggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                const content = toggle.nextElementSibling;
                const icon = toggle.querySelector('.bi-chevron-down');
                const isOpen = content.classList.contains('block');

                // Auto-close menu lain yang sedang BUKAN menu aktif
                toggles.forEach(otherToggle => {
                    if (otherToggle !== toggle && !otherToggle.hasAttribute('data-active')) {
                        otherToggle.nextElementSibling.classList.remove('block');
                        otherToggle.nextElementSibling.classList.add('hidden');
                        otherToggle.querySelector('.bi-chevron-down').classList.remove('rotate-180');
                    }
                });

                // Toggle menu yang diklik
                if (isOpen) {
                    content.classList.remove('block');
                    content.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                } else {
                    content.classList.remove('hidden');
                    content.classList.add('block');
                    icon.classList.add('rotate-180');
                }
            });
        });
    });

    // Menangani Sidebar di Mobile
    window.toggleSidebar = function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if(sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            if(overlay) {
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
            }
        } else {
            sidebar.classList.add('-translate-x-full');
            if(overlay) {
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }
    }
</script>
</html>