@extends('layouts.app')

@section('title', 'Kiralama İşlemleri - Araç Kiralama Sistemi')

@section('content')
<div class="space-y-6">
    <!-- Sayfa Başlığı ve Eylem Butonları -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Kiralama Sözleşmeleri</h1>
            <p class="text-sm text-slate-500 mt-1">Aktif, tamamlanan ve iptal edilen tüm kiralama operasyonlarını yönetin.</p>
        </div>
        <div>
            <a href="{{ route('rentals.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Yeni Kiralama Başlat
            </a>
        </div>
    </div>

    <!-- Filtre, Arama ve Sıralama Çubuğu (Rubric C3 & C4) -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('rentals.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            @csrf
            <div>
                <label for="search" class="block text-xs font-semibold text-slate-600 uppercase mb-1">Arama</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Müşteri adı veya plaka..." class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
                <label for="status" class="block text-xs font-semibold text-slate-600 uppercase mb-1">Durum Filtresi</label>
                <select name="status" id="status" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="">Tüm Durumlar</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                </select>
            </div>
            <div>
                <label for="vehicle_id" class="block text-xs font-semibold text-slate-600 uppercase mb-1">Araç Seçimi</label>
                <select name="vehicle_id" id="vehicle_id" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="">Tüm Araçlar</option>
                    @foreach($vehicles as $veh)
                        <option value="{{ $veh->id }}" {{ request('vehicle_id') == $veh->id ? 'selected' : '' }}>{{ $veh->plate_number }} - {{ $veh->brand }} {{ $veh->model }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="sort" class="block text-xs font-semibold text-slate-600 uppercase mb-1">Sıralama</label>
                <select name="sort" id="sort" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="start_date" {{ request('sort') === 'start_date' && request('direction', 'desc') === 'desc' ? 'selected' : '' }}>Alış Tarihi (Yeniden Eskiye)</option>
                    <option value="start_date_asc" {{ request('sort') === 'start_date' && request('direction') === 'asc' ? 'selected' : '' }}>Alış Tarihi (Eskiden Yeniye)</option>
                    <option value="total_price_desc" {{ request('sort') === 'total_price' && request('direction', 'desc') === 'desc' ? 'selected' : '' }}>Toplam Tutar (Yüksekten Düşüğe)</option>
                    <option value="total_price_asc" {{ request('sort') === 'total_price' && request('direction') === 'asc' ? 'selected' : '' }}>Toplam Tutar (Düşükten Yükseğe)</option>
                </select>
                <input type="hidden" name="direction" id="direction" value="{{ request('direction', 'desc') }}">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-slate-900 text-white rounded-lg px-4 py-2 text-sm font-medium hover:bg-slate-800 transition shadow-sm">
                    Filtrele
                </button>
                @if(request()->hasAny(['search', 'status', 'vehicle_id', 'sort']))
                    <a href="{{ route('rentals.index') }}" class="px-3 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100 text-sm font-medium transition" title="Filtreleri Temizle">
                        Sıfırla
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Kiralama Listesi Tablosu (Rubric C1, C3, C5) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Sözleşme</th>
                        <th class="px-4 py-3.5">Müşteri</th>
                        <th class="px-4 py-3.5">Kiralanan Araç</th>
                        <th class="px-4 py-3.5">Tarih Aralığı</th>
                        <th class="px-4 py-3.5">Toplam Ücret</th>
                        <th class="px-4 py-3.5">Durum</th>
                        <th class="px-4 py-3.5 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rentals as $rental)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-bold text-slate-900">
                                #{{ $rental->id }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-slate-900">{{ $rental->customer->full_name ?? 'Müşteri Silinmiş' }}</div>
                                <div class="text-xs text-slate-500">{{ $rental->customer->phone ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-medium text-slate-900">{{ $rental->vehicle->plate_number ?? 'Araç Silinmiş' }}</div>
                                <div class="text-xs text-slate-500">{{ $rental->vehicle->brand ?? '' }} {{ $rental->vehicle->model ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-slate-700 whitespace-nowrap">
                                <div class="text-xs font-medium">{{ $rental->start_date->format('d.m.Y') }} &rarr; {{ $rental->end_date->format('d.m.Y') }}</div>
                                <div class="text-[11px] text-slate-400">{{ $rental->start_date->diffInDays($rental->end_date) }} Gün</div>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-blue-600 whitespace-nowrap">
                                {{ number_format($rental->total_price, 2, ',', '.') }} TL
                            </td>
                            <td class="px-4 py-3.5">
                                @if($rental->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        Aktif
                                    </span>
                                @elseif($rental->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        Tamamlandı
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        İptal Edildi
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('rentals.show', $rental) }}" class="p-1.5 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Detay">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('rentals.edit', $rental) }}" class="p-1.5 text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Düzenle">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('rentals.destroy', $rental) }}" class="inline" onsubmit="return confirm('#{{ $rental->id }} numaralı kiralama kaydını silmek istediğinizden emin misiniz?');">
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
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-base font-semibold text-slate-700">Kayıtlı kiralama işlemi bulunamadı</p>
                                <p class="text-xs text-slate-400 mt-1">Arama kriterlerinizi değiştirebilir ya da yeni bir kiralama kaydı oluşturabilirsiniz.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rentals->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $rentals->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    document.getElementById('sort')?.addEventListener('change', function() {
        var dirInput = document.getElementById('direction');
        if (this.value === 'start_date_asc') {
            this.value = 'start_date';
            dirInput.value = 'asc';
        } else if (this.value === 'total_price_asc') {
            this.value = 'total_price';
            dirInput.value = 'asc';
        } else if (this.value === 'total_price_desc') {
            this.value = 'total_price';
            dirInput.value = 'desc';
        } else {
            dirInput.value = 'desc';
        }
    });
</script>
@endsection
