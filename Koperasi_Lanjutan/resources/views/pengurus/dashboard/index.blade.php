@extends ('pengurus.index')
<!-- @extends ('pengurus.layouts.navbar') -->
@extends ('pengurus.layouts.sidebar')
@section('content')

	<div class="container mx-auto px-4 pt-8">
		<!-- Header / Title + Actions -->
		<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
			<div>
				<h1 class="text-2xl font-semibold text-gray-800">Dashboard Pengurus</h1>
				<p class="text-sm text-gray-500 mt-1">Ringkasan operasional dan statistik koperasi</p>
			</div>
		</div>

		<!-- Statistik Cards -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
			<div class="bg-white rounded-2xl shadow p-5 flex items-center gap-4 border">
				<div class="p-3 rounded-lg bg-blue-50 text-blue-600">
					<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M17 20h5v-2a4 4 0 0 0-4-4h-1" />
					</svg>
				</div>
				<div>
					<div class="text-sm text-gray-500">Total Anggota</div>
					<div class="text-2xl font-semibold text-gray-800">{{ $totalAnggota }}</div>
				</div>
			</div>

			<div class="bg-white rounded-2xl shadow p-5 flex items-center gap-4 border">
				<div class="p-3 rounded-lg bg-yellow-50 text-yellow-600">
					<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<rect x="3" y="11" width="18" height="10" rx="2" />
					</svg>
				</div>
				<div>
					<div class="text-sm text-gray-500">Total Pinjaman</div>
					<div class="text-2xl font-semibold text-gray-800">
						Rp {{ number_format($totalPinjaman, 0, ',', '.') }}
					</div>
					<small class="text-sm text-gray-500">
						Dibayar: Rp {{ number_format($totalPinjamanDibayar, 0, ',', '.') }}
					</small>
				</div>
			</div>

			<!-- Kartu Total Simpanan -->
			<div class="bg-white rounded-2xl shadow p-5 flex items-center gap-4 border">
				<div class="p-3 rounded-lg bg-green-50 text-green-600">
					<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path d="M12 8c-2.21 0-4 1.79-4 4" />
					</svg>
				</div>
				<div>
					<div class="text-sm text-gray-500">Total Simpanan</div>
					<div class="text-2xl font-semibold text-gray-800">
						Rp {{ number_format($totalSimpanan, 0, ',', '.') }}
					</div>
					<small class="text-sm text-gray-500">
						Dibayar: Rp {{ number_format($totalSimpananDibayar, 0, ',', '.') }}
					</small>
				</div>
			</div>
		</div>

		<!-- Chart / Main Visualization -->
		<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
			<div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow border">
				<div class="flex items-center justify-between mb-4">
					<h2 class="text-lg font-semibold text-gray-800">Grafik Perkembangan Koperasi</h2>
					<span class="text-sm text-gray-500">Periode: Bulanan</span>
				</div>
				<div class="h-64 flex items-center justify-center bg-gray-50 rounded-md">
					<!-- Placeholder chart; replace with chart component -->
					<svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-blue-300" fill="none" viewBox="0 0 24 24"
						stroke="currentColor">
						<path d="M4 20v-6m4 6V10m4 10V4m4 16v-8" />
						<path d="M4 20h16" />
					</svg>
				</div>
			</div>

			<!-- Recent / Quick Info -->
			<div class="bg-white rounded-2xl p-6 shadow border">
				<h3 class="text-lg font-semibold text-gray-800 mb-3">Aktivitas Terbaru</h3>
				<ul class="space-y-3 text-sm text-gray-700">
					<li class="flex items-start gap-3">
						<span class="text-blue-500 mt-1">•</span>
						<div>
							<div class="font-medium">Andi mengajukan pinjaman</div>
							<div class="text-xs text-gray-500">10:45 — ID #PJ-00123</div>
						</div>
					</li>
					<li class="flex items-start gap-3">
						<span class="text-blue-500 mt-1">•</span>
						<div>
							<div class="font-medium">Verifikasi anggota baru: Siti</div>
							<div class="text-xs text-gray-500">09:20 — ID #AG-0045</div>
						</div>
					</li>
					<!-- ...existing code for more items... -->
				</ul>
				<div class="mt-4">
					<a href="#" class="text-sm text-blue-600 hover:underline">Lihat semua aktivitas</a>
				</div>
			</div>
		</div>

		<!-- Informasi ringkas + Footer kecil -->
		<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
			<div class="lg:col-span-3 bg-white rounded-2xl p-6 shadow border">
				<h3 class="text-lg font-semibold text-gray-800 mb-3">Catatan Singkat</h3>
				<p class="text-sm text-gray-600 leading-relaxed">Gunakan panel ini untuk melihat ringkasan bulanan,
					aktifitas anggota, dan laporan keuangan. Untuk laporan detail gunakan fitur "Buat Laporan".</p>
			</div>

			<!-- Kontak Cepat (DIHAPUS DARI SINI - dipindahkan ke footer di bawah) -->
			{{-- removed contact quick card --}}
		</div>

    <!-- Footer Informasi Lengkap (compact) -->
    <div class="bg-white rounded-xl p-4 shadow-sm border mb-8 w-full mt-8">
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
            <!-- Logo + Institusi (compact) -->
            <div class="md:col-span-4 pr-4 border-r border-gray-100">
                <div class="flex items-start gap-3">
                    <img src="{{ asset('assets/poliwangi_icon.png') }}" alt="Poliwangi Logo" class="w-16 h-16 object-contain rounded bg-gray-50 p-1">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Politeknik Negeri Banyuwangi</h4>
                        <p class="text-xs text-gray-600 mb-2">Institusi</p>
                        <p class="text-xs text-gray-700 mb-1"><span class="font-medium text-gray-800">Dosen Pembimbing:</span> Devit Suwardiyanto, S.Si., M.T</p>
                        <p class="text-xs text-gray-700"><span class="font-medium text-gray-800">Client:</span> Danang Sudarso W.P.J.W, S.P., M.M</p>
                    </div>
                </div>
            </div>

            <!-- Tim Pengembang (compact) -->
            <div class="md:col-span-4 space-y-2">
                <h5 class="text-sm font-semibold text-gray-800 mb-2">Tim Pengembang</h5>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-center gap-3">
                        <div class="bg-gray-50 p-2 rounded-full w-8 h-8 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800 text-sm">Moh Naufal</div>
                            <div class="text-gray-600 text-xs">+62 896-7580-3596</div>
                        </div>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="bg-gray-50 p-2 rounded-full w-8 h-8 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800 text-sm">Leo Eka Matra</div>
                            <div class="text-gray-600 text-xs">+62 831-1495-1853</div>
                        </div>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="bg-gray-50 p-2 rounded-full w-8 h-8 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800 text-sm">Dimas Januar Pradana</div>
                            <div class="text-gray-600 text-xs">+62 857-0471-7410</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Tim Pengembang (kanan, compact) -->
            <div class="md:col-span-4 space-y-2">
                <!-- spacer heading supaya alignment sama dengan kolom tengah -->
                <h5 class="text-sm font-semibold text-gray-800 mb-2 invisible">Tim Pengembang</h5>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-center gap-3">
                        <div class="bg-gray-50 p-2 rounded-full w-8 h-8 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800 text-sm">Wida Monica Putri</div>
                            <div class="text-gray-600 text-xs">+62 812-3581-5523</div>
                        </div>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="bg-gray-50 p-2 rounded-full w-8 h-8 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800 text-sm">Moh. Eric Ardiansyah</div>
                            <div class="text-gray-600 text-xs">+62 838-6285-5152</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright (compact) -->
        <div class="mt-6 text-center text-xs text-gray-500 border-t border-gray-100 pt-3">
            <p>© {{ date('Y') }} Koperasi Poliwangi — Tim Pengembang Politeknik Negeri Banyuwangi</p>
        </div>
		</div>
	</div>

@endsection