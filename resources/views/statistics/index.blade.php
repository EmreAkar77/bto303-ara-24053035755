@extends('layouts.app')

@section('title', 'Filo İstatistikleri - Araç Kiralama Sistemi')

@section('content')
<div class="space-y-6">
    <!-- Sayfa Başlığı -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Filo & Gelir İstatistikleri</h1>
            <p class="text-sm text-slate-500 mt-1">Belirlenen tarih aralığı için filo doluluk oranları, araç grupları ve operasyonel ciro analizi.</p>
        </div>
    </div>

    <!-- Filtre Formu -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('statistics.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @csrf
            <div>
                <label for="start_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Başlangıç Tarihi</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $startDate) }}" class="w-full rounded-xl border-slate-300 border px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label for="end_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bitiş Tarihi</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $endDate) }}" class="w-full rounded-xl border-slate-300 border px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label for="branch_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Şube Filtresi</label>
                <select name="branch_id" id="branch_id" class="w-full rounded-xl border-slate-300 border px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="">Tüm Şubeler</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ (string) old('branch_id', $branchId) === (string) $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm shadow-md transition">
                    İstatistikleri Güncelle
                </button>
                @if(request()->hasAny(['start_date', 'end_date', 'branch_id']))
                    <a href="{{ route('statistics.index') }}" class="px-3.5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 text-sm font-medium transition" title="Sıfırla">
                        Sıfırla
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 8 Özet Kartı (Rubric / İstek) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
        <!-- Toplam Araç -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Toplam Araç</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $stats['summary']['total_vehicles'] }}</span>
        </div>

        <!-- Bakımda -->
        <div class="bg-amber-50/70 p-4 rounded-xl border border-amber-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-amber-800 uppercase tracking-wider block">Bakımda</span>
            <span class="text-2xl font-black text-amber-700 mt-1 block">{{ $stats['summary']['maintenance_count'] }}</span>
        </div>

        <!-- Kirada -->
        <div class="bg-blue-50/70 p-4 rounded-xl border border-blue-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-blue-800 uppercase tracking-wider block">Kirada</span>
            <span class="text-2xl font-black text-blue-700 mt-1 block">{{ $stats['summary']['rented_count'] }}</span>
        </div>

        <!-- Boşta -->
        <div class="bg-emerald-50/70 p-4 rounded-xl border border-emerald-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider block">Boşta</span>
            <span class="text-2xl font-black text-emerald-700 mt-1 block">{{ $stats['summary']['idle_count'] }}</span>
        </div>

        <!-- Doluluk Oranı -->
        <div class="bg-indigo-50/70 p-4 rounded-xl border border-indigo-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-indigo-800 uppercase tracking-wider block">Doluluk (%)</span>
            <span class="text-2xl font-black text-indigo-700 mt-1 block">%{{ $stats['summary']['occupancy_rate'] }}</span>
        </div>

        <!-- Kiralama Sayısı -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Sözleşmeler</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $stats['summary']['rentals_count'] }}</span>
        </div>

        <!-- Toplam Ciro -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center col-span-2 sm:col-span-1 lg:col-span-1">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Toplam Ciro</span>
            <span class="text-base font-extrabold text-blue-600 mt-1 block">{{ number_format($stats['summary']['total_revenue'], 0, ',', '.') }} TL</span>
        </div>

        <!-- İptal Sayısı -->
        <div class="bg-rose-50/70 p-4 rounded-xl border border-rose-200 shadow-sm text-center col-span-2 sm:col-span-1 lg:col-span-1">
            <span class="text-[11px] font-bold text-rose-800 uppercase tracking-wider block">İptal Edilen</span>
            <span class="text-2xl font-black text-rose-700 mt-1 block">{{ $stats['summary']['cancelled_rentals_count'] }}</span>
        </div>
    </div>

    <!-- Şube Bazlı Dağılım Tablosu -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900">Şube Bazlı Filo Dağılımı ve Doluluk Oranları</h2>
            <span class="text-xs text-slate-500">{{ $startDate }} &bull; {{ $endDate }} Aralığı</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs font-semibold uppercase">
                    <tr>
                        <th class="px-4 py-3">Şube Adı</th>
                        <th class="px-4 py-3 text-center">Toplam Araç</th>
                        <th class="px-4 py-3 text-center">Bakımda</th>
                        <th class="px-4 py-3 text-center">Kirada</th>
                        <th class="px-4 py-3 text-center">Boşta</th>
                        <th class="px-4 py-3 text-right">Doluluk Oranı</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($stats['branch_stats'] as $bs)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $bs['branch']->name }}</td>
                            <td class="px-4 py-3 text-center font-bold text-slate-700">{{ $bs['total'] }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">{{ $bs['maintenance'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800">{{ $bs['rented'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">{{ $bs['idle'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-extrabold text-indigo-600">
                                %{{ $bs['occupancy_rate'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-slate-400">Şube istatistiği bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3 Grubun Altında Araç Listeleri -->
    <div class="space-y-6">
        <!-- 1. Grup: Kiradaki Araçlar -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-blue-50/60 border-b border-blue-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    <h2 class="text-base font-bold text-blue-900">Kirada Olan Araçlar ({{ $stats['rented_vehicles']->count() }})</h2>
                </div>
                <span class="text-xs text-blue-700 font-medium">Bu tarih aralığında aktif kiralama sözleşmesi bulunanlar</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3">Plaka</th>
                            <th class="px-4 py-3">Marka / Model</th>
                            <th class="px-4 py-3">Bağlı Şube</th>
                            <th class="px-4 py-3">Kiralayan Müşteri</th>
                            <th class="px-4 py-3">Kiralama Tarihleri</th>
                            <th class="px-4 py-3 text-right">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($stats['rented_vehicles'] as $vehicle)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $vehicle->plate_number }}</td>
                                <td class="px-4 py-3">{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                <td class="px-4 py-3 text-slate-600 text-xs">{{ $vehicle->branch->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-800 font-medium">
                                    {{ $vehicle->active_rental->customer->full_name ?? 'Müşteri Bilinmiyor' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600 text-xs whitespace-nowrap">
                                    @if(isset($vehicle->active_rental))
                                        {{ $vehicle->active_rental->start_date->format('d.m.Y') }} &rarr; {{ $vehicle->active_rental->end_date->format('d.m.Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('vehicles.show', $vehicle) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">İncele &rarr;</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-slate-400">Bu aralıkta kirada araç bulunmamaktadır.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Grup: Boştaki (Müsait) Araçlar -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-emerald-50/60 border-b border-emerald-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <h2 class="text-base font-bold text-emerald-900">Boştaki (Müsait) Araçlar ({{ $stats['idle_vehicles']->count() }})</h2>
                </div>
                <span class="text-xs text-emerald-700 font-medium">Kiralanmaya hazır araçlar</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3">Plaka</th>
                            <th class="px-4 py-3">Marka / Model</th>
                            <th class="px-4 py-3">Şube</th>
                            <th class="px-4 py-3">Günlük Ücret</th>
                            <th class="px-4 py-3 text-right">Eylem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($stats['idle_vehicles'] as $vehicle)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $vehicle->plate_number }}</td>
                                <td class="px-4 py-3">{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                <td class="px-4 py-3 text-slate-600 text-xs">{{ $vehicle->branch->name ?? '-' }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-800">{{ number_format($vehicle->daily_rate, 2, ',', '.') }} TL</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('rentals.create', ['vehicle_id' => $vehicle->id, 'start_date' => $startDate, 'end_date' => $endDate, 'pickup_branch_id' => $vehicle->branch_id]) }}" class="text-xs text-emerald-700 hover:text-emerald-900 font-semibold bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 rounded-md transition">
                                        Kirala &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-slate-400">Boşta araç bulunmamaktadır.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Grup: Bakımdaki Araçlar -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-amber-50/60 border-b border-amber-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <h2 class="text-base font-bold text-amber-900">Bakımdaki Araçlar ({{ $stats['maintenance_vehicles']->count() }})</h2>
                </div>
                <span class="text-xs text-amber-700 font-medium">Servis, bakım veya onarım sürecindeki araçlar</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3">Plaka</th>
                            <th class="px-4 py-3">Marka / Model</th>
                            <th class="px-4 py-3">Bağlı Şube</th>
                            <th class="px-4 py-3">Durum</th>
                            <th class="px-4 py-3 text-right">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($stats['maintenance_vehicles'] as $vehicle)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $vehicle->plate_number }}</td>
                                <td class="px-4 py-3">{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                <td class="px-4 py-3 text-slate-600 text-xs">{{ $vehicle->branch->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">Bakımda</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('vehicles.edit', $vehicle) }}" class="text-xs text-amber-700 hover:text-amber-900 font-medium">Düzenle &rarr;</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-slate-400">Bakımda araç bulunmamaktadır.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
