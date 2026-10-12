@extends('layouts.app')

@section('title', "Kiralama Sözleşmesi #{$rental->id} - Detay")

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Üst Başlık & Eylemler -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-bold text-slate-900">Kiralama Sözleşmesi #{{ $rental->id }}</h1>
                @if($rental->status === 'active')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        Aktif Kiralama
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
            </div>
            <p class="text-sm text-slate-500 mt-1">Oluşturulma Tarihi: {{ $rental->created_at->format('d.m.Y H:i') }}</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('rentals.edit', $rental) }}" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium shadow-sm transition">
                Düzenle
            </a>
            <form method="POST" action="{{ route('rentals.destroy', $rental) }}" class="inline" onsubmit="return confirm('#{{ $rental->id }} numaralı sözleşmeyi silmek istediğinizden emin misiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3.5 py-2 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-sm font-medium transition">
                    Sil
                </button>
            </form>
            <a href="{{ route('rentals.index') }}" class="px-3.5 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium transition">
                &larr; Kiralamalar
            </a>
        </div>
    </div>

    <!-- Detay Kartları (İlişkili Varlıklar - Rubric C6) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Araç Bilgileri -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tahsis Edilen Araç</span>
                @if($rental->vehicle)
                    <a href="{{ route('vehicles.show', $rental->vehicle) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                        Araç Profiline Git &rarr;
                    </a>
                @endif
            </div>
            @if($rental->vehicle)
                <div class="text-lg font-bold text-slate-900">{{ $rental->vehicle->brand }} {{ $rental->vehicle->model }}</div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div><span class="text-slate-400">Plaka:</span> <span class="font-semibold text-slate-700">{{ $rental->vehicle->plate_number }}</span></div>
                    <div><span class="text-slate-400">Model Yılı:</span> <span class="font-semibold text-slate-700">{{ $rental->vehicle->model_year }}</span></div>
                    <div><span class="text-slate-400">Günlük Ücret:</span> <span class="font-semibold text-slate-700">{{ number_format($rental->vehicle->daily_rate, 2, ',', '.') }} TL</span></div>
                    <div><span class="text-slate-400">Kayıtlı Şube:</span> <span class="font-semibold text-slate-700">{{ $rental->vehicle->branch->name ?? 'Merkez' }}</span></div>
                </div>
            @else
                <p class="text-sm text-rose-500 font-medium">Bu araç sistemden silinmiştir.</p>
            @endif
        </div>

        <!-- Müşteri Bilgileri -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-3">
            <div class="border-b border-slate-100 pb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Müşteri Bilgileri</span>
            </div>
            @if($rental->customer)
                <div class="text-lg font-bold text-slate-900">{{ $rental->customer->full_name }}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    <div><span class="text-slate-400">Telefon:</span> <span class="font-semibold text-slate-700">{{ $rental->customer->phone }}</span></div>
                    <div><span class="text-slate-400">E-Posta:</span> <span class="font-semibold text-slate-700">{{ $rental->customer->email }}</span></div>
                    <div><span class="text-slate-400">Ehliyet No:</span> <span class="font-semibold text-slate-700">{{ $rental->customer->driving_license_number }}</span></div>
                </div>
            @else
                <p class="text-sm text-rose-500 font-medium">Müşteri kaydı bulunamadı.</p>
            @endif
        </div>

        <!-- Şube Güzergahı -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-3">
            <div class="border-b border-slate-100 pb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Şube Güzergahı</span>
            </div>
            <div class="space-y-3 text-xs">
                <div>
                    <span class="block text-slate-400 font-semibold mb-0.5">Alış Şubesi:</span>
                    <span class="text-sm font-bold text-slate-800">{{ $rental->pickupBranch->name ?? 'Belirtilmedi' }}</span>
                    <p class="text-slate-500">{{ $rental->pickupBranch->address ?? '' }}</p>
                </div>
                <div class="pt-2 border-t border-slate-50">
                    <span class="block text-slate-400 font-semibold mb-0.5">Teslim Şubesi:</span>
                    <span class="text-sm font-bold text-slate-800">{{ $rental->returnBranch->name ?? 'Belirtilmedi' }}</span>
                    <p class="text-slate-500">{{ $rental->returnBranch->address ?? '' }}</p>
                </div>
            </div>
        </div>

        <!-- Kiralama Maliyeti & Süre Özeti -->
        <div class="bg-blue-50/50 p-6 rounded-xl border border-blue-100 shadow-sm space-y-3">
            <div class="border-b border-blue-200/50 pb-3">
                <span class="text-xs font-bold text-blue-900 uppercase tracking-wider">Mali Özet & Süre</span>
            </div>
            <div class="space-y-2 text-xs text-slate-700">
                <div class="flex justify-between">
                    <span>Alış Tarihi:</span>
                    <span class="font-bold text-slate-900">{{ $rental->start_date->format('d.m.Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Teslim Tarihi:</span>
                    <span class="font-bold text-slate-900">{{ $rental->end_date->format('d.m.Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Toplam Süre:</span>
                    <span class="font-bold text-slate-900">{{ $rental->start_date->diffInDays($rental->end_date) }} Gün</span>
                </div>
                <div class="pt-3 border-t border-blue-200 flex justify-between items-center">
                    <span class="text-sm font-bold text-slate-900">Toplam Ücret:</span>
                    <span class="text-2xl font-black text-blue-700">{{ number_format($rental->total_price, 2, ',', '.') }} TL</span>
                </div>
            </div>
        </div>
    </div>

    @if($rental->notes)
        <!-- Notlar -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sözleşme Notları</span>
            <p class="text-sm text-slate-700 mt-2 whitespace-pre-line">{{ $rental->notes }}</p>
        </div>
    @endif
</div>
@endsection
