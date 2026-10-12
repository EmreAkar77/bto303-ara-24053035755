@extends('layouts.app')

@section('title', 'Yeni Şube Ekle - Araç Kiralama Sistemi')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-900">Yeni Hizmet Şubesi Tanımla</h1>
                <p class="text-xs text-slate-500">Filo dağıtım ve teslim noktası ekleyin.</p>
            </div>
            <a href="{{ route('branches.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">
                &larr; Listeye Dön
            </a>
        </div>

        <form method="POST" action="{{ route('branches.store') }}" class="p-6 space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Şube Adı <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Örn: İzmir Bornova Şubesi" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('name') border-rose-500 @enderror" required>
                @error('name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Şube Adresi <span class="text-rose-500">*</span></label>
                <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="Cadde, mahalle, ilçe / il bilgisi" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('address') border-rose-500 @enderror" required>
                @error('address')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Telefon Numarası <span class="text-rose-500">*</span></label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+90 232 000 00 00" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('phone') border-rose-500 @enderror" required>
                @error('phone')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('branches.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    İptal
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-sm transition">
                    Şubeyi Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
