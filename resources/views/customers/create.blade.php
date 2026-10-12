@extends('layouts.app')

@section('title', 'Yeni Müşteri Kaydı - Araç Kiralama Sistemi')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-900">Yeni Müşteri Kaydı Oluştur</h1>
                <p class="text-xs text-slate-500">Kiralama sözleşmesi düzenlenecek müşteri bilgilerini giriniz.</p>
            </div>
            <a href="{{ route('customers.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">
                &larr; Listeye Dön
            </a>
        </div>

        <form method="POST" action="{{ route('customers.store') }}" class="p-6 space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-slate-700 mb-1">Müşteri Adı <span class="text-rose-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="Örn: Ahmet" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('first_name') border-rose-500 @enderror" required>
                    @error('first_name')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-slate-700 mb-1">Müşteri Soyadı <span class="text-rose-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="Örn: Yılmaz" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('last_name') border-rose-500 @enderror" required>
                    @error('last_name')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Telefon Numarası <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+90 532 000 00 00" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('phone') border-rose-500 @enderror" required>
                    @error('phone')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-Posta Adresi <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="ahmet@example.com" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-rose-500 @enderror" required>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="driving_license_number" class="block text-sm font-medium text-slate-700 mb-1">Ehliyet Belge Seri No <span class="text-rose-500">*</span></label>
                <input type="text" name="driving_license_number" id="driving_license_number" value="{{ old('driving_license_number') }}" placeholder="TR12345678" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('driving_license_number') border-rose-500 @enderror" required>
                @error('driving_license_number')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('customers.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    İptal
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-sm transition">
                    Müşteriyi Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
