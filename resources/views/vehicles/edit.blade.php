@extends('layouts.app')

@section('title', 'Araç Düzenle - Araç Kiralama Sistemi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-900">Araç Bilgilerini Düzenle</h1>
                <p class="text-xs text-slate-500">{{ $vehicle->plate_number }} plakalı aracın kayıt bilgilerini güncelleyin.</p>
            </div>
            <a href="{{ route('vehicles.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">
                &larr; Listeye Dön
            </a>
        </div>

        <form method="POST" action="{{ route('vehicles.update', $vehicle) }}" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <!-- Plaka -->
            <div>
                <label for="plate_number" class="block text-sm font-medium text-slate-700 mb-1">Araç Plakası <span class="text-rose-500">*</span></label>
                <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('plate_number') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                @error('plate_number')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Marka -->
                <div>
                    <label for="brand" class="block text-sm font-medium text-slate-700 mb-1">Marka <span class="text-rose-500">*</span></label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand', $vehicle->brand) }}" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('brand') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                    @error('brand')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Model -->
                <div>
                    <label for="model" class="block text-sm font-medium text-slate-700 mb-1">Model <span class="text-rose-500">*</span></label>
                    <input type="text" name="model" id="model" value="{{ old('model', $vehicle->model) }}" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('model') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                    @error('model')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Model Yılı -->
                <div>
                    <label for="model_year" class="block text-sm font-medium text-slate-700 mb-1">Model Yılı <span class="text-rose-500">*</span></label>
                    <input type="number" name="model_year" id="model_year" value="{{ old('model_year', $vehicle->model_year) }}" min="1990" max="{{ date('Y') + 1 }}" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('model_year') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                    @error('model_year')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Günlük Ücret -->
                <div>
                    <label for="daily_rate" class="block text-sm font-medium text-slate-700 mb-1">Günlük Ücret (TL) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="daily_rate" id="daily_rate" value="{{ old('daily_rate', $vehicle->daily_rate) }}" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('daily_rate') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                    @error('daily_rate')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Şube Seçimi -->
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-slate-700 mb-1">Bulunduğu Şube <span class="text-rose-500">*</span></label>
                    <select name="branch_id" id="branch_id" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white @error('branch_id') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $vehicle->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durum -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Durum <span class="text-rose-500">*</span></label>
                    <select name="status" id="status" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white @error('status') border-rose-500 ring-1 ring-rose-500 @enderror" required>
                        <option value="available" {{ old('status', $vehicle->status) === 'available' ? 'selected' : '' }}>Müsait</option>
                        <option value="rented" {{ old('status', $vehicle->status) === 'rented' ? 'selected' : '' }}>Kirada</option>
                        <option value="maintenance" {{ old('status', $vehicle->status) === 'maintenance' ? 'selected' : '' }}>Bakımda</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Eylem Butonları -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('vehicles.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    İptal
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-sm transition">
                    Güncellemeleri Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
