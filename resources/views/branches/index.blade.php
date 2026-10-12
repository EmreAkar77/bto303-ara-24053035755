@extends('layouts.app')

@section('title', 'Şubeler - Araç Kiralama Sistemi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Hizmet Şubeleri</h1>
            <p class="text-sm text-slate-500 mt-1">Araç teslim alma ve iade noktalarımız.</p>
        </div>
        <div>
            <a href="{{ route('branches.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">
                + Yeni Şube Ekle
            </a>
        </div>
    </div>

    <!-- Arama Çubuğu -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('branches.index') }}" class="flex gap-2">
            @csrf
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Şube adı, adres veya telefon ile ara..." class="flex-1 rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <button type="submit" class="bg-slate-900 text-white rounded-lg px-4 py-2 text-sm font-medium hover:bg-slate-800 transition">Ara</button>
            @if(request('search'))
                <a href="{{ route('branches.index') }}" class="px-3 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100 text-sm font-medium transition">Sıfırla</a>
            @endif
        </form>
    </div>

    <!-- Şube Listesi -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3.5">Şube Adı</th>
                        <th class="px-4 py-3.5">Adres</th>
                        <th class="px-4 py-3.5">Telefon</th>
                        <th class="px-4 py-3.5 text-center">Mevcut Araç Sayısı</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($branches as $branch)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3.5 font-bold text-slate-900">{{ $branch->name }}</td>
                            <td class="px-4 py-3.5 text-slate-600 text-xs">{{ $branch->address }}</td>
                            <td class="px-4 py-3.5 text-slate-600 font-mono text-xs">{{ $branch->phone }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                    {{ $branch->vehicles_count }} Araç
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-400">Kayıtlı şube bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($branches->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $branches->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
