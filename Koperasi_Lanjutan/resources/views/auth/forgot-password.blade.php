<x-guest-layout>
    <div class="max-w-lg mx-auto mt-10">
        <h2 class="text-3xl font-semibold mb-2 text-center">Lupa Password</h2>
        <p class="mb-6 text-center text-gray-600">
            Masukkan <span class="font-semibold">NIP</span> dan <span class="font-semibold">Email</span> Anda untuk menerima kode OTP.
        </p>

        <!-- Step 1: Kirim OTP -->
        <div id="step1">
            <form id="requestOtpForm" class="space-y-5" novalidate>
                @csrf
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-lg">
                        <i class="fa-solid fa-id-card"></i>
                    </span>
                    <input id="nip" name="nip" type="text" required
                        class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-0"
                        placeholder="Masukkan NIP">
                </div>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-lg">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input id="email" name="email" type="email" required
                        class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-0"
                        placeholder="Masukkan Email aktif">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white text-lg font-medium py-3 rounded-lg transition">
                    Kirim Kode OTP ke Email
                </button>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-blue-500 hover:underline text-sm">
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>

        <!-- Step 2: Verifikasi OTP -->
        <div id="step2" class="hidden mt-6">
            <form id="verifyOtpForm" class="space-y-5" novalidate>
                @csrf
                <h3 class="text-xl font-semibold text-center">Verifikasi OTP</h3>
                <p class="text-center mb-3">
                    Kode OTP telah dikirim ke email: <span id="userEmail" class="font-semibold text-blue-600"></span>
                </p>

                <input id="otp" name="otp" type="text" maxlength="6" required
                    class="w-full text-center text-lg font-mono pl-3 pr-3 py-3 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-0"
                    placeholder="Masukkan 6 digit OTP">

                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white text-lg font-medium py-3 rounded-lg transition">
                    Verifikasi OTP
                </button>

                <button type="button" id="backToStep1"
                    class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium py-2 rounded-lg transition">
                    Kembali
                </button>
            </form>
        </div>

        <!-- Step 3: Reset Password -->
        <div id="step3" class="hidden mt-6">
            <form id="resetPasswordForm" class="space-y-5" novalidate>
                @csrf
                <h3 class="text-xl font-semibold text-center">Reset Password</h3>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-lg">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input id="password" name="password" type="password" required
                        class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-0"
                        placeholder="Password baru">
                </div>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-lg">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-0"
                        placeholder="Konfirmasi password baru">
                </div>

                <button type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white text-lg font-medium py-3 rounded-lg transition">
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let currentNip = '', currentEmail = '', resetToken = '';

        function showStep(n) {
            ['step1', 'step2', 'step3'].forEach((id, i) =>
                document.getElementById(id).classList.toggle('hidden', n !== i + 1)
            );
        }

        function alertMsg(type, text) {
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Berhasil' : 'Gagal',
                text: text,
                confirmButtonColor: type === 'error' ? '#d33' : '#3085d6',
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

        // STEP 1: Kirim OTP
        document.getElementById('requestOtpForm').addEventListener('submit', async e => {
            e.preventDefault();
            const nip = document.getElementById('nip').value.trim();
            const email = document.getElementById('email').value.trim();
            if (!nip || !email) return alertMsg('error', 'Isi NIP dan Email terlebih dahulu.');

            currentNip = nip;
            currentEmail = email;
            showLoading('Mengirim kode OTP...');

            const res = await fetch("{{ route('password.sendOtp') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ nip, email })
            });

            const data = await res.json();
            Swal.close();

            if (res.ok && data.status) {
                alertMsg('success', data.message || 'Kode OTP telah dikirim ke email.');
                document.getElementById('userEmail').textContent = currentEmail;
                showStep(2);
            } else {
                alertMsg('error', data.message || 'Gagal mengirim OTP.');
            }
        });

        // STEP 2: Verifikasi OTP
        document.getElementById('verifyOtpForm').addEventListener('submit', async e => {
            e.preventDefault();
            const otp = document.getElementById('otp').value.trim();
            if (!otp) return alertMsg('error', 'Masukkan OTP terlebih dahulu.');

            showLoading('Memverifikasi kode OTP...');
            const res = await fetch("{{ route('password.verifyOtp') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ nip: currentNip, email: currentEmail, otp })
            });

            const data = await res.json();
            Swal.close();

            if (res.ok && data.status) {
                alertMsg('success', data.message || 'OTP valid.');
                resetToken = data.reset_token;
                showStep(3);
            } else {
                alertMsg('error', data.message || 'OTP salah atau kadaluarsa.');
            }
        });

        // STEP 3: Reset Password
        document.getElementById('resetPasswordForm').addEventListener('submit', async e => {
            e.preventDefault();
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            if (!password || password !== confirm) return alertMsg('error', 'Password tidak sama.');

            showLoading('Menyimpan password baru...');
            const res = await fetch("{{ route('password.request') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    nip: currentNip,
                    email: currentEmail,
                    reset_token: resetToken,
                    password,
                    password_confirmation: confirm
                })
            });

            const data = await res.json();
            Swal.close();

            if (res.ok && data.status) {
                alertMsg('success', data.message || 'Password berhasil direset.');
                setTimeout(() => window.location.href = "{{ route('login') }}", 1800);
            } else {
                alertMsg('error', data.message || 'Gagal mereset password.');
            }
        });

        document.getElementById('backToStep1').addEventListener('click', () => showStep(1));
    </script>
</x-guest-layout>
