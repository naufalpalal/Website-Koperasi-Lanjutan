<form action="{{ route('user.pinjaman.upload', $pinjaman->id) }}"
      method="POST" enctype="multipart/form-data">
    @csrf

    <label class="block text-gray-700 font-semibold mb-2">
        Upload Dokumen Verifikasi (PDF)
    </label>

    <input type="file"
           name="dokumen_verifikasi"
           accept="application/pdf"
           required
           class="border rounded w-full p-2 mb-4">

    <button class="bg-green-600 text-white w-full py-2 rounded-lg
                   hover:bg-green-700 transition">
        ⬆️ Upload Dokumen
    </button>
</form>
