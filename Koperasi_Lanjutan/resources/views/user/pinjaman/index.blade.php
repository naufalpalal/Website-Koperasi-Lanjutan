@extends('user.index')

@section('title', 'Ajukan Pinjaman')

@section('content')
    <div class="p-6 bg-white rounded shadow">
        {{-- STATUS KEANGGOTAAN --}}
        @if (!$bolehPinjam)
            <div class="bg-red-100 text-red-700 border border-red-300 p-3 rounded mb-5 text-sm">
                <strong>Masa aktif belum mencukupi.</strong><br>
                Minimal keanggotaan untuk mengajukan pinjaman adalah <strong>6 bulan</strong>.<br>
                Anda baru terdaftar selama <strong>{{ 6 - $sisaBulan }} bulan</strong>.<br>
                Sisa waktu: <strong>{{ $sisaBulan }} bulan lagi</strong>.
            </div>
        @endif

        {{-- LIST PAKET PINJAMAN --}}
        @if ($bolehPinjam)
            <h3 class="text-xl font-semibold mb-3">Pilih Paket Pinjaman</h3>

            <div class="grid md:grid-cols-3 gap-4">
                @foreach ($paketPinjaman as $paket)
                    <div class="border rounded p-4 shadow-sm bg-gray-50">
                        <h4 class="font-bold text-lg mb-2">{{ $paket->nama_paket }}</h4>

                        <p><strong>Nominal:</strong> Rp {{ number_format($paket->nominal, 0, ',', '.') }}</p>
                        <p><strong>Tenor:</strong> {{ $paket->tenor }} bulan</p>
                        <p><strong>Bunga:</strong> {{ number_format((float) $paket->bunga, 0) }}%</p>

                        <form action="{{ route('user.pinjaman.store') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="nominal" value="{{ $paket->nominal }}">
                            <input type="hidden" name="tenor" value="{{ $paket->tenor }}">
                            <input type="hidden" name="bunga" value="{{ $paket->bunga }}">

                            <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                                Ajukan Paket Ini
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    </div>

    {{-- POPUP MODAL --}}
    <div id="alertModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden">
        <div class="bg-white w-96 rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-3">Peringatan</h3>
            <p class="text-gray-700 mb-4">
                Masa aktif keanggotaan Anda baru <strong>{{ $lamaBulan ?? 0 }}</strong> bulan.
                Minimal <strong>6 bulan</strong> untuk mengajukan pinjaman.
            </p>
            <button onclick="closeModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Mengerti
            </button>
        </div>
    </div>

    <script>
        const bolehPinjam = @json($bolehPinjam);

        document.querySelectorAll('.paketForm').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (!bolehPinjam) {
                    e.preventDefault(); // cegah form submit
                    document.getElementById('alertModal').classList.remove('hidden');
                }
            });
        });

        function closeModal() {
            document.getElementById('alertModal').classList.add('hidden');
        }
    </script>

@endsection