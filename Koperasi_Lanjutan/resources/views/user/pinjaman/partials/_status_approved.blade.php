<div class="p-4 bg-green-100 text-green-800 rounded-lg flex
            items-center justify-between gap-4">
    <span>
        ✅ <strong>Pinjaman telah disetujui.</strong><br>
        Silakan lihat daftar angsuran Anda.
    </span>

    <a href="{{ route('user.pinjaman.angsuran', $pinjaman->id) }}"
       class="px-4 py-2 bg-green-600 text-white rounded-lg
              hover:bg-green-700 transition">
        Lihat Angsuran
    </a>
</div>
