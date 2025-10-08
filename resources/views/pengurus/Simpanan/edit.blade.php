@extends('admin.index')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-black-600 mb-6 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="h-6 w-6 text-blue-500" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zm0 0V4m0 8v8" />
        </svg>
        Update Status Simpanan
    </h1>

    <div class="bg-white shadow-lg rounded-2xl p-6">
        <form action="{{ route('admin.simpanan.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-blue-100 text-black-700 text-left">
                            <th class="px-4 py-3 font-semibold">Jenis</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($simpanans as $simpanan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-800 font-medium capitalize">
                                    {{ $simpanan->type }}
                                </td>
                                <td class="px-4 py-3">
                                    <select name="status[{{ $simpanan->id }}]" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-400">
                                        <option value="pending" {{ $simpanan->status == 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="success" {{ $simpanan->status == 'success' ? 'selected' : '' }}>
                                            Success
                                        </option>
                                        <option value="failed" {{ $simpanan->status == 'failed' ? 'selected' : '' }}>
                                            Failed
                                        </option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <textarea name="note[{{ $simpanan->id }}]" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-400" 
                                        rows="2">{{ $simpanan->note }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between mt-6">
                <a href="{{ route('admin.simpanan.index') }}" 
                   class="px-5 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                    ← Kembali
                </a>
                <button type="submit" 
                        class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow transition">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
