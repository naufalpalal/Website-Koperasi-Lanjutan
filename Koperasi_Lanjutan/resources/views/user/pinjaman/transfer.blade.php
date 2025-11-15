@extends('user.index')

@section('title', 'Upload Bukti Transfer Angsuran')

@section('content')

    <div class="bg-white p-6 rounded-xl shadow-lg max-w-xl mx-auto mt-6">
        {{-- === NOTIFIKASI DI ATAS === --}}
        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200">
                ✓ {{ session('success') }}
            </div>
        @endif


        <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">
            Konfirmasi Pembayaran Angsuran
        </h2>

        {{-- Info angsuran --}}
        <div class="mb-5 bg-gray-50 p-4 rounded-lg border">
            <p class="font-semibold mb-2 text-gray-800">Bulan Angsuran yang Dibayar:</p>
            <ul class="list-disc ml-5 text-gray-700">
                @foreach ($angsuran as $item)
                    <li class="mb-1">
                        Bulan ke-{{ $item->bulan_ke }} —
                        <span class="text-green-600 font-semibold">
                            Rp{{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <hr class="my-5">

        {{-- QR Code --}}
        <div class="text-center">
            <p class="text-gray-700 mb-2 font-medium">Silakan scan kode berikut untuk transfer:</p>

            <img src="{{ asset('assets/moh.naufal16_qr.png') }}" alt="QR Code"
                class="mx-auto w-44 h-44 border rounded-lg shadow-md mb-3">

            <p class="text-sm text-gray-600">
                Atau transfer ke rekening:
                <br>
                <span class="font-bold text-lg text-gray-800">
                    123 456 7890 (Bank BRI)
                </span>
            </p>
        </div>

        <hr class="my-6">

        {{-- Form upload --}}
        <form action="{{ route('anggota.angsuran.bayar', $pinjaman->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">

            @csrf

            {{-- Hidden IDs --}}
            @foreach ($angsuran as $item)
                <input type="hidden" name="angsuran_ids[]" value="{{ $item->id }}">
            @endforeach

            {{-- Input File --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Upload Bukti Transfer
                </label>

                <input type="file" name="bukti_transfer" accept="image/png, image/jpeg, image/jpg"
                    class="w-full border border-gray-300 p-2 rounded-lg bg-gray-50 focus:ring focus:ring-blue-200 outline-none">

                {{-- Error alert --}}
                @if ($errors->any())
                    <div class="mt-3 bg-red-100 text-red-700 p-3 rounded-lg border border-red-300">
                        <ul class="list-disc ml-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li class="leading-relaxed">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 transition text-white py-2 px-4 rounded-lg w-full shadow-md font-semibold">
                Kirim Bukti Transfer
            </button>
        </form>

    </div>

@endsection