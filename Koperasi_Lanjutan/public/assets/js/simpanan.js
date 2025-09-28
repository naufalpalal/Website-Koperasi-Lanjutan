// Check all
document.addEventListener('DOMContentLoaded', function () {
    const btnGenerate = document.getElementById('btnGenerate');
    const modalNominal = document.getElementById('modalNominal');
    const closeModal = document.getElementById('closeModal');
    const masuk = document.getElementById('masuk'); // ✅ diperbaiki, deklarasi dulu

    // Tombol redirect ke route belajar.id
    if (masuk) {
        masuk.addEventListener('click', function () {
            window.location.href = "/pengurus/simpanan-wajib/edit"
        });
    }

    // Tombol generate
    if (btnGenerate) {
        btnGenerate.addEventListener('click', function (e) {
            e.preventDefault();

            // Jika nominal belum diatur
            if (this.dataset.nominalEmpty === "true") {
                alert('Harap edit nominal simpanan wajib terlebih dahulu sebelum menambahkan.');
                return;
            }

            // Jika nominal sudah ada, tampilkan modal
            modalNominal.classList.remove('hidden');
        });
    }

    // Tombol close modal
    if (closeModal) {
        closeModal.addEventListener('click', function () {
            modalNominal.classList.add('hidden');
        });
    }

    // Pindahkan select ke dalam form untuk auto submit
    const select = document.getElementById('filterBulan');
    const form = document.getElementById('bulanForm');
    if (select && form) {
        form.appendChild(select);
    }
});
