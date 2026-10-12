<!DOCTYPE html>
<html lang="tr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Araç Filosu ve Kiralama Yönetim Sistemi')</title>
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-full flex flex-col text-slate-800 antialiased">
    <!-- Gezinme Menüsü (Header) -->
    <header class="bg-slate-900 border-b border-slate-800 text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Sistem Adı -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('vehicles.index') }}" class="flex items-center space-x-2 font-bold text-lg text-white hover:text-blue-400 transition">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-600 text-white font-extrabold shadow-sm">
                            AF
                        </span>
                        <span>Filo & Kiralama</span>
                    </a>
                </div>

                <!-- Masaüstü Menü -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('vehicles.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('vehicles.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Araç Filosu
                    </a>
                    <a href="{{ route('availability.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('availability.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Müsait Araç Ara
                    </a>
                    <a href="{{ route('rentals.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('rentals.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Kiralamalar
                    </a>
                    <a href="{{ route('statistics.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('statistics.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        İstatistikler
                    </a>
                    <a href="{{ route('branches.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('branches.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Şubeler
                    </a>
                    <a href="{{ route('customers.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('customers.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Müşteriler
                    </a>
                </nav>

                <!-- Hızlı Eylem Butonu -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="{{ route('rentals.create') }}" class="inline-flex items-center px-3.5 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition">
                        + Yeni Kiralama
                    </a>
                    <a href="{{ route('vehicles.create') }}" class="inline-flex items-center px-3.5 py-1.5 border border-slate-700 text-sm font-medium rounded-md text-slate-200 bg-slate-800 hover:bg-slate-700 transition">
                        + Araç Ekle
                    </a>
                </div>

                <!-- Mobil Hamburger Butonu -->
                <div class="md:hidden flex items-center">
                    <button type="button" id="mobile-menu-btn" class="p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none" aria-label="Menüyü aç">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobil Menü Açılır Alanı -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-800 bg-slate-900 px-4 pt-2 pb-4 space-y-1">
            <a href="{{ route('vehicles.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('vehicles.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Araç Filosu
            </a>
            <a href="{{ route('availability.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('availability.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Müsait Araç Ara
            </a>
            <a href="{{ route('rentals.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('rentals.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Kiralamalar
            </a>
            <a href="{{ route('statistics.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('statistics.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                İstatistikler
            </a>
            <a href="{{ route('branches.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('branches.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Şubeler
            </a>
            <a href="{{ route('customers.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('customers.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                Müşteriler
            </a>
            <div class="pt-2 border-t border-slate-800 flex flex-col space-y-2">
                <a href="{{ route('rentals.create') }}" class="w-full text-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    + Yeni Kiralama
                </a>
                <a href="{{ route('vehicles.create') }}" class="w-full text-center px-4 py-2 rounded-md text-sm font-medium text-slate-200 bg-slate-800 hover:bg-slate-700">
                    + Yeni Araç Ekle
                </a>
            </div>
        </div>
    </header>

    <!-- Ana İçerik -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Flash Başarı Mesajı -->
        @if(session('success'))
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 flex items-start space-x-3 shadow-sm">
                <svg class="h-5 w-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <div class="text-sm font-medium flex-1">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Flash Hata Mesajı -->
        @if(session('error'))
            <div class="mb-6 rounded-lg bg-rose-50 border border-rose-200 p-4 text-rose-800 flex items-start space-x-3 shadow-sm">
                <svg class="h-5 w-5 text-rose-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <div class="text-sm font-medium flex-1">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Form Doğrulama Genel Hata Özeti -->
        @if($errors->any())
            <div class="mb-6 rounded-lg bg-amber-50 border border-amber-200 p-4 text-amber-900 shadow-sm">
                <div class="flex items-center space-x-2 font-semibold text-sm mb-2 text-amber-800">
                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Lütfen formdaki eksik veya hatalı alanları düzeltiniz:</span>
                </div>
                <ul class="list-disc list-inside text-xs sm:text-sm space-y-1 text-amber-800">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto py-4 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p>Araç Filosu ve Kiralama Yönetim Sistemi &bull; BTÖ-303 Uygulama Projesi</p>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            var menu = document.getElementById('mobile-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        });
    </script>
</body>
</html>
