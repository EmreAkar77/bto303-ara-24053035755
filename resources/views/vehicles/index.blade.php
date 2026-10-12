@extends('layouts.app')

@section('title', 'Araç Filosu - Araç Kiralama Sistemi')

@section('content')
<div class="space-y-6">

    <!-- Yatay Görsel Hero Banner (Görselleştirme) -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-900 text-white shadow-xl border border-slate-800">
        <!-- Arka Plan Desenleri ve Görsel Işıltı -->
        <div class="absolute inset-0 opacity-15 pointer-events-none">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        <div class="relative z-10 p-6 sm:p-8 lg:p-10 flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="max-w-xl text-center lg:text-left space-y-3">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-400/30">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    <span>2026 Filo Yönetim Paneli</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    Premium Araç Filosu & Kiralama Yönetimi
                </h1>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Ekonomik segmentten lüks sınıfa kadar tüm filo envanterini anlık takip edin, müsaitlik ve kiralama operasyonlarını tek ekrandan yönetin.
                </p>
                <div class="pt-2 flex flex-wrap gap-3 justify-center lg:justify-start">
                    <a href="{{ route('vehicles.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-600/30 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Filoya Araç Ekle
                    </a>
                    <a href="{{ route('rentals.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Yeni Kiralama Başlat
                    </a>
                </div>
            </div>

            <!-- Yatay Araç İllüstrasyonu / İstatistik Rozetleri -->
            <div class="w-full lg:w-auto flex flex-col items-center gap-4">
                <!-- Yatay Otomobil Silüeti SVG -->
                <div class="w-full max-w-md p-4 rounded-xl bg-slate-800/60 border border-slate-700/60 backdrop-blur-sm shadow-inner">
                    <div class="flex items-center justify-between text-xs text-slate-300 font-medium mb-2 px-1">
                        <span>Aktif Filo Durumu</span>
                        <span class="text-emerald-400 font-bold">&#9679; Canlı Veri</span>
                    </div>
                    <svg class="w-full h-24 text-blue-400" viewBox="0 0 640 240" fill="currentColor">
                        <!-- Modern Araba Silüeti SVG -->
                        <path d="M570,140 L530,140 C520,110 490,90 450,90 L230,90 C190,90 160,110 150,140 L70,140 C55,140 40,155 40,170 L40,190 C40,195 45,200 50,200 L95,200 C100,175 125,155 155,155 C185,155 210,175 215,200 L425,200 C430,175 455,155 485,155 C515,155 540,175 545,200 L590,200 C595,200 600,195 600,190 L600,170 C600,155 585,140 570,140 Z" opacity="0.35"/>
                        <path d="M590,165 L525,140 L450,140 L380,100 L230,100 L170,140 L80,145 C65,148 55,160 55,175 L55,185 C55,188 58,190 60,190 L95,190 C98,170 115,155 135,155 C155,155 172,170 175,190 L465,190 C468,170 485,155 505,155 C525,155 542,170 545,190 L580,190 C585,190 590,185 590,180 L590,165 Z" fill="currentColor"/>
                        <!-- Pencereler -->
                        <path d="M240,110 L370,110 L430,135 L200,135 Z" fill="#0f172a" opacity="0.7"/>
                        <!-- Tekerlekler -->
                        <circle cx="135" cy="190" r="32" fill="#0f172a"/>
                        <circle cx="135" cy="190" r="18" fill="#94a3b8"/>
                        <circle cx="505" cy="190" r="32" fill="#0f172a"/>
                        <circle cx="505" cy="190" r="18" fill="#94a3b8"/>
                        <!-- Farlar -->
                        <polygon points="585,168 565,165 565,175" fill="#fef08a"/>
                        <polygon points="55,168 70,165 70,175" fill="#f43f5e"/>
                    </svg>

                    <!-- Hızlı İstatistik Sayaçları (Canlı Kiralama Tarihleri Esaslı) -->
                    <div class="grid grid-cols-3 gap-2 mt-2 pt-2 border-t border-slate-700/60 text-center">
                        <div class="bg-slate-900/50 rounded-lg p-1.5">
                            <span class="block text-base font-extrabold text-white">{{ $totalVehicles }}</span>
                            <span class="block text-[10px] text-slate-400">Toplam Araç</span>
                        </div>
                        <div class="bg-slate-900/50 rounded-lg p-1.5">
                            <span class="block text-base font-extrabold text-emerald-400">{{ $availableCount }}</span>
                            <span class="block text-[10px] text-slate-400">Müsait</span>
                        </div>
                        <div class="bg-slate-900/50 rounded-lg p-1.5">
                            <span class="block text-base font-extrabold text-blue-400">{{ $rentedCount }}</span>
                            <span class="block text-[10px] text-slate-400">Kirada</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtre, Arama ve Sıralama Çubuğu (Rubric C3 & C4) -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('vehicles.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            @csrf
            <div>
                <label for="search" class="block text-xs font-semibold text-slate-600 uppercase mb-1">Arama</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Plaka, marka, model..." class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
                <label for="status" class="block text-xs font-semibold text-slate-600 uppercase mb-1">Durum Filtresi</label>
                <select name="status" id="status" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="">Tüm Durumlar</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Müsait</option>
                    <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>Kirada</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Bakımda</option>
                </select>
            </div>
            <div>
                <label for="branch_id" class="block text-xs font-semibold text-slate-600 uppercase mb-1">Bağlı Şube</label>
                <select name="branch_id" id="branch_id" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="">Tüm Şubeler</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="sort" class="block text-xs font-semibold text-slate-600 uppercase mb-1">Sıralama Ölçütü</label>
                <select name="sort" id="sort" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>En Yeni Eklenenler</option>
                    <option value="daily_rate" {{ request('sort') === 'daily_rate' && request('direction') === 'asc' ? 'selected' : '' }}>Günlük Ücret (Düşükten Yükseğe)</option>
                    <option value="daily_rate_desc" {{ request('sort') === 'daily_rate' && request('direction') === 'desc' ? 'selected' : '' }}>Günlük Ücret (Yüksekten Düşüğe)</option>
                    <option value="model_year" {{ request('sort') === 'model_year' ? 'selected' : '' }}>Model Yılı (Yeni - Eski)</option>
                    <option value="brand" {{ request('sort') === 'brand' ? 'selected' : '' }}>Marka (A-Z)</option>
                </select>
                <input type="hidden" name="direction" id="direction" value="{{ request('direction', 'desc') }}">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-slate-900 text-white rounded-lg px-4 py-2 text-sm font-medium hover:bg-slate-800 transition shadow-sm">
                    Filtrele
                </button>
                @if(request()->hasAny(['search', 'status', 'branch_id', 'sort']))
                    <a href="{{ route('vehicles.index') }}" class="px-3 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100 text-sm font-medium transition" title="Filtreleri Temizle">
                        Sıfırla
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Görsel Zenginleştirilmiş Araç Listesi Tablosu (Rubric C1, C3, E1, E2) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Araç / Plaka</th>
                        <th class="px-4 py-3.5 hidden sm:table-cell">Model Yılı</th>
                        <th class="px-4 py-3.5">Şube</th>
                        <th class="px-4 py-3.5">Günlük Ücret</th>
                        <th class="px-4 py-3.5">Durum</th>
                        <th class="px-4 py-3.5 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($vehicles as $vehicle)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center space-x-3">
                                    <!-- Araç Görsel İkon Rozeti -->
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700 flex-shrink-0 shadow-sm group">
                                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 11l1.5-4.5A2 2 0 016.4 5h11.2a2 2 0 011.9 1.5L21 11M3 11h18m-18 0v6a1 1 0 001 1h1m16-7v6a1 1 0 01-1 1h-1"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-extrabold text-slate-900 text-sm tracking-wide">{{ $vehicle->plate_number }}</span>
                                            <span class="text-[11px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">{{ $vehicle->brand }}</span>
                                        </div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $vehicle->model }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 hidden sm:table-cell text-slate-600 font-medium">
                                {{ $vehicle->model_year }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-600 text-xs">
                                <div class="font-medium text-slate-800">{{ $vehicle->branch->name ?? 'Belirtilmedi' }}</div>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-blue-600 text-sm whitespace-nowrap">
                                {{ number_format($vehicle->daily_rate, 2, ',', '.') }} TL
                                <span class="text-[10px] text-slate-400 font-normal block">/ gün</span>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($vehicle->status === 'available')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Müsait
                                    </span>
                                @elseif($vehicle->status === 'rented')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                                        Kirada
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                        Bakımda
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('vehicles.show', $vehicle) }}" class="p-1.5 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="İncele">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('vehicles.edit', $vehicle) }}" class="p-1.5 text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Düzenle">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" class="inline" onsubmit="return confirm('{{ $vehicle->plate_number }} plakalı aracı silmek istediğinizden emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Sil">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                </svg>
                                <p class="text-base font-semibold text-slate-700">Kayıtlı araç bulunamadı</p>
                                <p class="text-xs text-slate-400 mt-1">Arama kriterlerinizi değiştirebilir ya da yeni bir araç ekleyebilirsiniz.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vehicles->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $vehicles->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    // Sıralama dropdown'u değiştiğinde yön ve sort parametrelerini ayarla
    document.getElementById('sort')?.addEventListener('change', function() {
        var dirInput = document.getElementById('direction');
        if (this.value === 'daily_rate_desc') {
            this.value = 'daily_rate';
            dirInput.value = 'desc';
        } else if (this.value === 'daily_rate') {
            dirInput.value = 'asc';
        } else if (this.value === 'brand') {
            dirInput.value = 'asc';
        } else {
            dirInput.value = 'desc';
        }
    });
</script>
@endsection
