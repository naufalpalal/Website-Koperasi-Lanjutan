@extends('user.index')

@section('title', 'Upload Bukti Transfer Angsuran')

@section('content')

    <div class="bg-white p-6 rounded-lg shadow-lg max-w-xl mx-auto mt-5">

        <h2 class="text-xl font-bold mb-4 text-center">Konfirmasi Pembayaran Angsuran</h2>

        {{-- Informasi Angsuran yang Dipilih --}}
        <div class="mb-4">
            <p class="font-semibold mb-2">Bulan Angsuran yang Dibayar:</p>
            <ul class="list-disc ml-5 text-gray-700">
                @foreach ($angsuran as $item)
                    <li>Bulan ke-{{ $item->bulan_ke }} —
                        <span class="text-green-600">
                            Rp{{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <hr class="my-4">

        {{-- QR atau Nomor Rekening --}}
        <p class="text-gray-700 mb-2 text-center">Silakan scan QR berikut untuk transfer pembayaran:</p>

        <img src="{{ asset('assets/moh.naufal16_qr.png') }}" alt="QR Code"
            class="mx-auto w-40 h-40 border rounded-lg shadow mb-4">

        <p class="text-center text-sm text-gray-600">
            Atau transfer ke rekening: <br>
            <span class="font-semibold text-lg">123 456 7890 (Bank BRI)</span>
        </p>

        <hr class="my-4">

        {{-- Form Upload Bukti Transfer --}}
        <form action="{{ route('anggota.angsuran.bayar', $pinjaman->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf

            {{-- Hidden IDs --}}
            @foreach ($angsuran as $item)
                <input type="hidden" name="angsuran_ids[]" value="{{ $item->id }}">
            @endforeach

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Upload Bukti Transfer
                </label>
                <input type="file" name="bukti_transfer" accept="image/png, image/jpeg, image/jpg"
                    class="w-full border p-2 rounded-lg" required>
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg w-full shadow">
                Kirim Bukti Transfer
            </button>
        </form>

    </div>

@endsection
