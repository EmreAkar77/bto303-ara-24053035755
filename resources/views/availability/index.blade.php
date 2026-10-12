@extends('layouts.app')

@section('title', 'Müsait Araç Ara - Araç Kiralama Sistemi')

@section('content')
<div class="space-y-6">
    <!-- Sayfa Başlığı -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Müsait Araç Arama</h1>
            <p class="text-sm text-slate-500 mt-1">İstediğiniz tarih aralığında ve şubede kiralamaya hazır araçları anında filtreleyin.</p>
        </div>
    </div>

    <!-- Arama Formu (GET ile çalışır, parametreler URL'de kalır) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('availability.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @csrf
            <div>
                <label for="start_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Alış Tarihi <span class="text-rose-500">*</span>
                </label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $startDate ?? date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-300 border px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('start_date') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                @error('start_date')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="end_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Teslim Tarihi <span class="text-rose-500">*</span>
                </label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $endDate ?? date('Y-m-d', strtotime('+3 days'))) }}" min="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-300 border px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('end_date') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                @error('end_date')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="branch_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Alış Şubesi (İsteğe Bağlı)
                </label>
                <select name="branch_id" id="branch_id" class="w-full rounded-xl border-slate-300 border px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                    <option value="">Tüm Şubeler</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ (string) old('branch_id', $branchId) === (string) $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Müsait Araçları Bul
                </button>
            </div>
        </form>
    </div>

    <!-- Sonuçlar Bölümü -->
    @if($vehicles !== null)
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">
                    Müsait Araçlar 
                    <span class="text-sm font-normal text-slate-500">
                        ({{ $startDate }} &rarr; {{ $endDate }} &bull; {{ $days }} Gün &bull; Toplam {{ $vehicles->total() }} araç bulundu)
                    </span>
                </h2>
            </div>

            @if($vehicles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($vehicles as $vehicle)
                        @php
                            $totalEstimate = $days * $vehicle->daily_rate;
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition p-5 flex flex-col justify-between">
                            <div>
                                <!-- Üst Başlık & Rozet -->
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div>
                                        <div class="text-xs font-semibold uppercase text-slate-400">{{ $vehicle->brand }}</div>
                                        <h3 class="text-lg font-bold text-slate-900">{{ $vehicle->model }}</h3>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-slate-900 text-white shadow-sm">
                                        {{ $vehicle->plate_number }}
                                    </span>
                                </div>

                                <!-- Araç Görsel İkonu & Detaylar -->
                                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 11l1.5-4.5A2 2 0 016.4 5h11.2a2 2 0 011.9 1.5L21 11M3 11h18m-18 0v6a1 1 0 001 1h1m16-7v6a1 1 0 01-1 1h-1"></path>
                                        </svg>
                                    </div>
                                    <div class="text-xs space-y-0.5">
                                        <div class="font-medium text-slate-700">Model Yılı: <span class="font-bold text-slate-900">{{ $vehicle->model_year }}</span></div>
                                        <div class="text-slate-500">Şube: <span class="font-semibold text-slate-800">{{ $vehicle->branch->name ?? 'Belirtilmedi' }}</span></div>
                                    </div>
                                </div>

                                <!-- Fiyat Özeti -->
                                <div class="flex items-baseline justify-between border-t border-slate-100 pt-3 mb-4">
                                    <div>
                                        <span class="text-xs text-slate-400 block">Günlük Ücret</span>
                                        <span class="text-sm font-semibold text-slate-700">{{ number_format($vehicle->daily_rate, 2, ',', '.') }} TL</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs text-slate-400 block">{{ $days }} Günlük Toplam</span>
                                        <span class="text-xl font-extrabold text-blue-600">{{ number_format($totalEstimate, 2, ',', '.') }} TL</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Kiralama Oluştur Butonu -->
                            <a href="{{ route('rentals.create', [
                                'vehicle_id' => $vehicle->id,
                                'start_date' => $startDate,
                                'end_date' => $endDate,
                                'pickup_branch_id' => $vehicle->branch_id,
                            ]) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition">
                                Bu Araçla Kiralama Oluştur &rarr;
                            </a>
                        </div>
                    @endforeach
                </div>

                @if($vehicles->hasPages())
                    <div class="p-4 bg-white rounded-xl border border-slate-200">
                        {{ $vehicles->links() }}
                    </div>
                @endif
            @else
                <!-- Boş Durum (Rubric E3) -->
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
                    <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-slate-800">Bu tarihlerde müsait araç bulunamadı</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                        Seçtiğiniz tarih aralığında tüm araçlarımız kirada veya bakımda olabilir. Farklı bir tarih aralığı veya farklı bir şube seçmeyi deneyebilirsiniz.
                    </p>
                </div>
            @endif
        </div>
    @else
        <div class="bg-blue-50/60 rounded-2xl border border-blue-100 p-8 text-center">
            <h3 class="text-base font-bold text-blue-900">Müsaitlik Sorgulaması</h3>
            <p class="text-sm text-blue-700 mt-1">Lütfen yukarıdaki formdan alış tarihi ve teslim tarihi seçerek müsait araçları listeleyiniz.</p>
        </div>
    @endif
</div>
@endsection
