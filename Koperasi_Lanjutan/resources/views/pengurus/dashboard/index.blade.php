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
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 0 0-4-4h-1"/></svg>
			</div>
			<div>
				<div class="text-sm text-gray-500">Total Anggota</div>
				<div class="text-2xl font-semibold text-gray-800">{{ $totalAnggota }}</div>
			</div>
		</div>

		<div class="bg-white rounded-2xl shadow p-5 flex items-center gap-4 border">
			<div class="p-3 rounded-lg bg-yellow-50 text-yellow-600">
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="10" rx="2"/></svg>
			</div>
			<div>
				<div class="text-sm text-gray-500">Pinjaman Aktif</div>
				<div class="text-2xl font-semibold text-gray-800">90</div>
			</div>
		</div>

		<div class="bg-white rounded-2xl shadow p-5 flex items-center gap-4 border">
			<div class="p-3 rounded-lg bg-green-50 text-green-600">
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-2.21 0-4 1.79-4 4"/></svg>
			</div>
			<div>
				<div class="text-sm text-gray-500">Total Simpanan</div>
				<div class="text-2xl font-semibold text-gray-800">Rp 240.000.000</div>
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
				<svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
			<p class="text-sm text-gray-600 leading-relaxed">Gunakan panel ini untuk melihat ringkasan bulanan, aktifitas anggota, dan laporan keuangan. Untuk laporan detail gunakan fitur "Buat Laporan".</p>
		</div>

		<!-- Kontak Cepat (DIHAPUS DARI SINI - dipindahkan ke footer di bawah) -->
		{{-- removed contact quick card --}}
	</div>

	<!-- Footer Informasi Lengkap (gabungan + logo) -->
	<div class="bg-white rounded-2xl p-8 shadow border mb-8">
		<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-8">
			<!-- Logo + Institusi -->
			<div class="md:col-span-4 pr-6 border-r border-gray-200">
				<div class="flex items-start gap-4">
					<img src="{{ asset('assets/poliwangi_icon.png') }}" alt="Poliwangi Logo" class="w-20 h-20 object-contain rounded-lg bg-gray-50 p-2">
					<div>
						<div class="flex items-center gap-2 mb-1">
							<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
							</svg>
							<h4 class="text-md font-semibold text-gray-800">Politeknik Negeri Banyuwangi</h4>
						</div>
						<p class="text-sm text-gray-600 mb-4">Institusi</p>
						
						<div class="flex items-center gap-2 mb-2">
							<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
							</svg>
							<p class="text-sm text-gray-700"><span class="font-medium text-gray-800">Dosen Pembimbing:</span><br/>Devit Suwardiyanto, S.Si., M.T</p>
						</div>
						
						<div class="flex items-center gap-2">
							<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
							</svg>
							<p class="text-sm text-gray-700"><span class="font-medium text-gray-800">Client:</span><br/>Danang Sudarso W.P.J.W, S.P., M.M</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Tim Pengembang (tengah) -->
			<div class="md:col-span-4 space-y-4">
				<div class="flex items-center gap-2 mb-4">
					<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
					</svg>
					<h5 class="text-sm font-semibold text-gray-800">Tim Pengembang</h5>
				</div>
				<ul class="space-y-4">
					<li class="bg-gray-50 p-3 rounded-lg flex items-center gap-3">
						<div class="bg-blue-100 p-2 rounded-full">
							<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
							</svg>
						</div>
						<div>
							<div class="font-medium text-gray-800">Moh Naufal</div>
							<div class="text-gray-600 text-sm flex items-center gap-1">
								<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
								</svg>
								+62 856-4881-8190
							</div>
						</div>
					</li>
					<li class="bg-gray-50 p-3 rounded-lg flex items-center gap-3">
						<div class="bg-blue-100 p-2 rounded-full">
							<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
							</svg>
						</div>
						<div>
							<div class="font-medium text-gray-800">Leo Eka Matra</div>
							<div class="text-gray-600 text-sm flex items-center gap-1">
								<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
								</svg>
								+62 858-1548-5484
							</div>
						</div>
					</li>
					<li class="bg-gray-50 p-3 rounded-lg flex items-center gap-3">
						<div class="bg-blue-100 p-2 rounded-full">
							<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
							</svg>
						</div>
						<div>
							<div class="font-medium text-gray-800">Dimas Januar Pradana</div>
							<div class="text-gray-600 text-sm flex items-center gap-1">
								<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
								</svg>
								+62 895-3670-77711
							</div>
						</div>
					</li>
				</ul>
			</div>

			<!-- Tim Pengembang (kanan) -->
			<div class="md:col-span-4 space-y-4 pt-8 md:pt-12">
				<ul class="space-y-4">
					<li class="bg-gray-50 p-3 rounded-lg flex items-center gap-3">
						<div class="bg-blue-100 p-2 rounded-full">
							<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
							</svg>
						</div>
						<div>
							<div class="font-medium text-gray-800">Wida Monica Putri</div>
							<div class="text-gray-600 text-sm flex items-center gap-1">
								<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
								</svg>
								+62 896-7322-0932
							</div>
						</div>
					</li>
					<li class="bg-gray-50 p-3 rounded-lg flex items-center gap-3">
						<div class="bg-blue-100 p-2 rounded-full">
							<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
							</svg>
						</div>
						<div>
							<div class="font-medium text-gray-800">Moh. Eric Ardiansyah</div>
							<div class="text-gray-600 text-sm flex items-center gap-1">
								<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
								</svg>
								+62 851-3660-1435
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>

		<div class="mt-8 pt-6 text-center text-xs text-gray-500 border-t border-gray-200">
			<div class="flex items-center justify-center gap-2">
				<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
				</svg>
				<p>© {{ date('Y') }} Koperasi Poliwangi — Dibuat oleh Tim Pengembang Politeknik Negeri Banyuwangi</p>
			</div>
		</div>
	</div>
</div>

@endsection


