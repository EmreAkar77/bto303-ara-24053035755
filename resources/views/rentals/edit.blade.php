@extends('layouts.app')

@section('title', "Kiralama #{$rental->id} Düzenle - Araç Kiralama Sistemi")

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-900">Kiralama Kaydını Düzenle (#{{ $rental->id }})</h1>
                <p class="text-xs text-slate-500">Mevcut kiralama sözleşmesi detaylarını güncelleyin.</p>
            </div>
            <a href="{{ route('rentals.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">
                &larr; Listeye Dön
            </a>
        </div>

        <form method="POST" action="{{ route('rentals.update', $rental) }}" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <!-- Araç Seçimi -->
            <div>
                <label for="vehicle_id" class="block text-sm font-medium text-slate-700 mb-1">Kiralanan Araç <span class="text-rose-500">*</span></label>
                <select name="vehicle_id" id="vehicle_id" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white @error('vehicle_id') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                    @foreach($vehicles as $veh)
                        <option value="{{ $veh->id }}" {{ old('vehicle_id', $rental->vehicle_id) == $veh->id ? 'selected' : '' }}>
                            {{ $veh->plate_number }} - {{ $veh->brand }} {{ $veh->model }} (Günlük: {{ number_format($veh->daily_rate, 2, ',', '.') }} TL - Şube: {{ $veh->branch->name ?? 'Merkez' }})
                        </option>
                    @endforeach
                </select>
                @error('vehicle_id')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Müşteri Seçimi -->
            <div>
                <label for="customer_id" class="block text-sm font-medium text-slate-700 mb-1">Müşteri <span class="text-rose-500">*</span></label>
                <select name="customer_id" id="customer_id" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white @error('customer_id') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id', $rental->customer_id) == $customer->id ? 'selected' : '' }}>
                            {{ $customer->full_name }} (Tel: {{ $customer->phone }} - Ehliyet: {{ $customer->driving_license_number }})
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Şube Seçimleri -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="pickup_branch_id" class="block text-sm font-medium text-slate-700 mb-1">Alış Şubesi <span class="text-rose-500">*</span></label>
                    <select name="pickup_branch_id" id="pickup_branch_id" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white @error('pickup_branch_id') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('pickup_branch_id', $rental->pickup_branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('pickup_branch_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="return_branch_id" class="block text-sm font-medium text-slate-700 mb-1">Teslim Şubesi <span class="text-rose-500">*</span></label>
                    <select name="return_branch_id" id="return_branch_id" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white @error('return_branch_id') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('return_branch_id', $rental->return_branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('return_branch_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tarih Seçimleri -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-slate-700 mb-1">Alış Tarihi <span class="text-rose-500">*</span></label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $rental->start_date->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('start_date') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                    @error('start_date')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-slate-700 mb-1">Teslim Tarihi <span class="text-rose-500">*</span></label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $rental->end_date->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('end_date') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                    @error('end_date')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Kiralama Durumu -->
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Kiralama Durumu <span class="text-rose-500">*</span></label>
                <select name="status" id="status" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white @error('status') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                    <option value="active" {{ old('status', $rental->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ old('status', $rental->status) === 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                    <option value="cancelled" {{ old('status', $rental->status) === 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                </select>
                @error('status')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Özel Notlar -->
            <div>
                <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">Kiralama Notları</label>
                <textarea name="notes" id="notes" rows="3" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('notes', $rental->notes) }}</textarea>
            </div>

            <!-- Eylem Butonları -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('rentals.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    İptal
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-sm transition">
                    Değişiklikleri Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
