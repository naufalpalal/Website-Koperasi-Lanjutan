@extends('user.index')

@section('content')
    <div class="p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4 text-gray-800">
            Pengajuan Libur Simpanan Sukarela
        </h2>

        <p class="mb-4 text-gray-600">
            Anggota dapat mengajukan <span class="font-semibold text-gray-800">libur simpanan sukarela</span>
            untuk bulan ini apabila terdapat alasan tertentu. Pengajuan akan diproses oleh pengurus koperasi.
        </p>

        <form action="{{ route('user.simpanan.sukarela.libur.submit') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="alasan" class="block mb-2 font-semibold text-gray-700">
                    Alasan Pengajuan Libur
                </label>
                <textarea name="alasan" id="alasan" rows="4"
                    class="border rounded p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-300"
                    placeholder="Tuliskan alasan mengapa Anda ingin libur simpanan bulan ini..." required></textarea>
            </div>

            <div class="mb-4">
                <label for="bulan" class="block mb-2 font-semibold text-gray-700">
                    Bulan Pengajuan
                </label>

                <input type="month" name="bulan" id="bulan" value="{{ \Carbon\Carbon::now()->format('Y-m') }}"
                    class="border rounded p-2 w-full focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
            </div>


            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-200">
                Ajukan Libur
            </button>

            <a href="{{ route('user.simpanan.sukarela.index') }}"
                class="ml-3 px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 transition duration-200">
                Batal
            </a>
        </form>
    </div>
@endsection
