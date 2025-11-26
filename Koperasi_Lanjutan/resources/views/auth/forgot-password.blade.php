<x-guest-layout>
    <div class="max-w-lg mx-auto mt-10">
        <h2 class="text-3xl font-semibold mb-2 text-center">Lupa Password</h2>
        <p class="mb-6 text-center text-gray-600">
            Masukkan <span class="font-semibold">NIP</span> dan <span class="font-semibold">Email</span> Anda untuk menerima link reset password.
        </p>

        {{-- HALAMAN KIRIM LINK RESET --}}
        
        @if (!isset($token))
        <form id="sendLinkForm" class="space-y-5" novalidate>
            @csrf

            <!-- NIP -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-lg">
                    <i class="fa-solid fa-id-card"></i>
                </span>
                <input id="nip" name="nip" type="text" required
                    class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-300 bg-white"
                    placeholder="Masukkan NIP Anda">
            </div>

            <!-- EMAIL (untuk kirim link, tidak disimpan di DB) -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-lg">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <input id="email" name="email" type="email" required
                    class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-300 bg-white"
                    placeholder="Masukkan Email aktif">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-lg font-medium py-3 rounded-lg transition">
                Kirim Link Reset ke Email
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline text-sm">
                    Kembali ke Login
                </a>
            </div>
        </form>

        {{-- ===================== --}}
        {{-- HALAMAN RESET PASSWORD --}}
        {{-- ===================== --}}
        @else
        <form id="resetPasswordForm" class="space-y-5" novalidate>
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" id="nip" name="nip" value="{{ request('nip') }}">

            <!-- NIP (disabled agar tidak diedit, tapi nilai tetap dikirim dari hidden input di atas) -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-lg">
                    <i class="fa-solid fa-id-card"></i>
                </span>
                <input type="text" value="{{ request('nip') }}" disabled
                    class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-700">
            </div>

            <!-- PASSWORD -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-lg">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <input id="password" name="password" type="password" required
                    class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-300 bg-white"
                    placeholder="Password baru">
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-lg">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-300 bg-white"
                    placeholder="Konfirmasi password baru">
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white text-lg font-medium py-3 rounded-lg transition">
                Ganti Password
            </button>
        </form>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function alertMsg(type, text) {
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Berhasil' : 'Gagal',
                text: text,
                timer: 2500,
                showConfirmButton: false
            });
        }

        function showLoading(msg = 'Memproses...') {
            Swal.fire({
                title: msg,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        }

        // ------------------------------
        // KIRIM LINK RESET PASSWORD
        // ------------------------------
        const sendLinkForm = document.getElementById('sendLinkForm');
        if (sendLinkForm) {
            sendLinkForm.addEventListener('submit', async e => {
                e.preventDefault();

                const nip = document.getElementById('nip').value.trim();
                const email = document.getElementById('email').value.trim();

                if (!nip || !email)
                    return alertMsg('error', 'NIP dan Email harus diisi.');

                showLoading('Mengirim link reset...');

                try {
                    const res = await fetch("{{ route('forgot-password.send') }}", {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ nip, email })
                    });

                    let data;
                    try {
                        data = await res.json();
                    } catch (e) {
                        Swal.close();
                        return alertMsg('error', 'Terjadi kesalahan saat memproses respons dari server.');
                    }

                    Swal.close();

                    if (res.ok && data.success) {
                        alertMsg('success', data.message);
                    } else {
                        alertMsg('error', data.message || 'Terjadi kesalahan saat mengirim link reset password.');
                    }
                } catch (error) {
                    Swal.close();
                    console.error('Error:', error);
                    alertMsg('error', 'Terjadi kesalahan koneksi. Pastikan koneksi internet Anda stabil.');
                }
            });
        }

        // ------------------------------
        // RESET PASSWORD
        // ------------------------------
        const resetForm = document.getElementById('resetPasswordForm');
        if (resetForm) {
            resetForm.addEventListener('submit', async e => {
                e.preventDefault();

                const nip = document.getElementById('nip').value.trim();
                const password = document.getElementById('password').value;
                const confirm = document.getElementById('password_confirmation').value;
                const token = document.querySelector('input[name="token"]').value;

                if (!nip || !password || !confirm)
                    return alertMsg('error', 'Semua kolom wajib diisi.');

                if (password !== confirm)
                    return alertMsg('error', 'Konfirmasi password tidak sama.');

                showLoading('Menyimpan password baru...');

                try {
                    const res = await fetch("{{ route('forgot-password.reset') }}", {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ nip, password, password_confirmation: confirm, token })
                    });

                    let data;
                    try {
                        data = await res.json();
                    } catch (e) {
                        Swal.close();
                        return alertMsg('error', 'Terjadi kesalahan saat memproses respons dari server.');
                    }

                    Swal.close();

                    if (res.ok && data.success) {
                        alertMsg('success', data.message);
                        setTimeout(() => window.location.href = "{{ route('login') }}", 1800);
                    } else {
                        alertMsg('error', data.message || 'Terjadi kesalahan saat mereset password.');
                    }
                } catch (error) {
                    Swal.close();
                    console.error('Error:', error);
                    alertMsg('error', 'Terjadi kesalahan koneksi. Pastikan koneksi internet Anda stabil.');
                }
            });
        }
    </script>
</x-guest-layout>
