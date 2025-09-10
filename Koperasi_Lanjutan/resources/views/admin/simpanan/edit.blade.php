@extends('admin.index')

@section('content')
<div class="container mx-auto px-6 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Update Status Simpanan</h1>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <form action="{{ route('admin.simpanan.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($simpanans as $simpanan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ ucfirst($simpanan->type) }}
                                </td>
                                <td class="px-4 py-3">
                                    <select name="status[{{ $simpanan->id }}]"
                                        class="block w-40 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                                        <option value="pending" class="text-yellow-600" {{ $simpanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="success" class="text-green-600" {{ $simpanan->status == 'success' ? 'selected' : '' }}>Success</option>
                                        <option value="failed" class="text-red-600" {{ $simpanan->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <textarea name="note[{{ $simpanan->id }}]" rows="2"
                                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">{{ $simpanan->note }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('admin.simpanan.index') }}"
                   class="px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 transition">
                   ← Kembali
                </a>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white shadow transition">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
