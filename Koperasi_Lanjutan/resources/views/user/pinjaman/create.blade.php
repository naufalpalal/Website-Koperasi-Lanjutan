@extends('user.index')

@section('title', 'Formulir Pengajuan & Upload Dokumen Pinjaman')

@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow rounded-xl p-6 mt-8">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">
            🏛️ Formulir Pengajuan & Upload Dokumen Pinjaman
        </h2>

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if (!$pinjaman)
            {{-- Jika belum ada pengajuan --}}
            <form action="{{ route('user.pinjaman.store') }}" method="POST">
                @csrf
                <label class="block text-gray-700 mb-1">Nominal Pinjaman</label>
                <input type="number" name="nominal"
                    class="w-full border-gray-300 border rounded-md p-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
                    placeholder="Masukkan jumlah pinjaman (min. 100.000)" required>

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 rounded-lg mt-4">
                    💰 Kirim Pengajuan
                </button>
            </form>
        @elseif ($pinjaman->status === 'pending')
            {{-- Jika masih pending --}}
            <div>
                <p class="mb-2"><strong>Nominal Pinjaman:</strong> Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}
                </p>
                <p class="text-yellow-600 font-semibold mb-4">⏳ Menunggu persetujuan pengurus...</p>

                <hr class="my-4">

                <h3 class="text-lg font-semibold text-gray-700 mb-2">📄 Surat Pinjaman</h3>
                <a href="{{ route('user.pinjaman.download', $pinjaman->id) }}"
                    class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    📥 Download Surat Pinjaman
                </a>

                {{-- Upload --}}
                <form action="{{ route('user.pinjaman.upload', $pinjaman->id) }}" method="POST"
                    enctype="multipart/form-data" class="mt-6">
                    @csrf
                    <label for="dokumen_pinjaman" class="block text-gray-600 mb-2">
                        Upload kembali surat pinjaman yang telah ditandatangani (PDF)
                    </label>
                    <input type="file" name="dokumen_pinjaman[]" id="dokumen_pinjaman" accept="application/pdf" multiple
                        class="border border-gray-300 rounded-lg w-full p-2 mb-4">

                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        📤 Upload ke Pengurus
                    </button>
                </form>
            </div>
        @elseif ($pinjaman->status === 'disetujui')
            {{-- Jika sudah disetujui oleh pengurus --}}
            <div>
                <p><strong>Status:</strong> ✅ Disetujui oleh pengurus</p>

                <div class="mt-4">
                    @if (!empty($pinjaman->dokumen_verifikasi) && file_exists(public_path('storage/' . $pinjaman->dokumen_verifikasi)))
                        <div class="bg-white shadow-md p-4 rounded-lg border">
                            <h3 class="font-semibold mb-2 text-gray-700">
                                📄 Dokumen Persetujuan Kedua
                            </h3>
                            <iframe src="{{ asset('storage/' . $pinjaman->dokumen_verifikasi) }}" width="100%"
                                height="600px" class="rounded-lg border">
                            </iframe>
                        </div>
                    @else
                        <p class="text-gray-500 italic">Dokumen persetujuan kedua belum tersedia atau belum bisa diakses.
                        </p>
                    @endif
                </div>



                {{-- <form action="{{ route('user.pinjaman.respon', $pinjaman->id) }}" method="POST" class="mt-6 flex gap-4">
                    @csrf
                    <button type="submit" name="aksi" value="setuju"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex-1">
                        ✅ Setuju
                    </button>
                    <button type="submit" name="aksi" value="tidak_setuju"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex-1">
                        ❌ Tidak Setuju
                    </button>
                </form> --}}
            </div>
            {{-- @elseif ($pinjaman->status === 'disetujui_anggota')
            <div class="p-3 bg-green-100 text-green-700 rounded-md">
                ✅ Kamu sudah menyetujui perjanjian pinjaman ini. Terima kasih.
            </div>
        @elseif ($pinjaman->status === 'ditolak_anggota')
            <div class="p-3 bg-red-100 text-red-700 rounded-md">
                ❌ Kamu telah menolak perjanjian pinjaman ini.
            </div> --}}
        @endif
    </div>
@endsection
