@extends('layouts.app')

@section('title', "{$vehicle->plate_number} - Araç Detayı")

@section('content')
<div class="space-y-6">
    <!-- Üst Başlık & Butonlar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-bold text-slate-900">{{ $vehicle->plate_number }}</h1>
                @if($vehicle->status === 'available')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                        Müsait
                    </span>
                @elseif($vehicle->status === 'rented')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        Kirada
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                        Bakımda
                    </span>
                @endif
            </div>
            <p class="text-sm text-slate-500 mt-1">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->model_year }})</p>
        </div>
        <div class="flex items-center space-x-2">
            @if($vehicle->status !== 'maintenance')
                <a href="{{ route('rentals.create', ['vehicle_id' => $vehicle->id]) }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-sm transition">
                    + Bu Aracı Kirala
                </a>
            @endif
            <a href="{{ route('vehicles.edit', $vehicle) }}" class="px-3.5 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium transition">
                Düzenle
            </a>
            <a href="{{ route('vehicles.index') }}" class="px-3.5 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium transition">
                &larr; Filo Listesi
            </a>
        </div>
    </div>

    <!-- Araç Bilgi Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Bağlı Şube</span>
            <div class="mt-2 text-base font-bold text-slate-900">{{ $vehicle->branch->name ?? 'Tanımsız' }}</div>
            <div class="text-xs text-slate-500 mt-1">{{ $vehicle->branch->address ?? '' }}</div>
            <div class="text-xs text-slate-500">{{ $vehicle->branch->phone ?? '' }}</div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kiralama Tarifesi</span>
            <div class="mt-2 text-2xl font-black text-blue-600">{{ number_format($vehicle->daily_rate, 2, ',', '.') }} TL</div>
            <div class="text-xs text-slate-500 mt-1">Günlük sabit taban ücret</div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kiralama Geçmişi</span>
            <div class="mt-2 text-2xl font-bold text-slate-900">{{ $vehicle->rentals->count() }} Kayıt</div>
            <div class="text-xs text-slate-500 mt-1">Aktif: {{ $vehicle->rentals->where('status', 'active')->count() }} &bull; Tamamlanan: {{ $vehicle->rentals->where('status', 'completed')->count() }}</div>
        </div>
    </div>

    <!-- İlişkili Kiralamalar Listesi (Rubric C6) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900">Bu Araca Ait Kiralama Kayıtları</h2>
            <span class="text-xs text-slate-500">İlişkili sözleşmeler ve geçmiş kullanım</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3">Sözleşme No</th>
                        <th class="px-4 py-3">Müşteri</th>
                        <th class="px-4 py-3">Tarih Aralığı</th>
                        <th class="px-4 py-3">Alış / Teslim Şubesi</th>
                        <th class="px-4 py-3">Toplam Tutar</th>
                        <th class="px-4 py-3">Durum</th>
                        <th class="px-4 py-3 text-right">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($vehicle->rentals as $rental)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-900">#{{ $rental->id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900">{{ $rental->customer->full_name ?? 'Bilinmiyor' }}</div>
                                <div class="text-xs text-slate-500">{{ $rental->customer->phone ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ $rental->start_date->format('d.m.Y') }} &rarr; {{ $rental->end_date->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                {{ $rental->pickupBranch->name ?? '-' }} &rarr; {{ $rental->returnBranch->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-slate-900">
                                {{ number_format($rental->total_price, 2, ',', '.') }} TL
                            </td>
                            <td class="px-4 py-3">
                                @if($rental->status === 'active')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800">Aktif</span>
                                @elseif($rental->status === 'completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">Tamamlandı</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-600">İptal Edildi</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('rentals.show', $rental) }}" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                    Görüntüle &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400 text-sm">
                                Bu araca ait henüz herhangi bir kiralama kaydı bulunmamaktadır.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
