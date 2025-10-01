// Check all
document.addEventListener('DOMContentLoaded', function () {
    const btnGenerate = document.getElementById('btnGenerate');
    const modalNominal = document.getElementById('modalNominal');
    const closeModal = document.getElementById('closeModal');
    const masuk = document.getElementById('masuk'); // ✅ deklarasi dulu

    // Tombol redirect ke route belajar.id
    if (masuk) {
        masuk.addEventListener('click', function () {
            window.location.href = "/pengurus/simpanan-wajib/edit";
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

    // === Bagian toggle periode (radio button) ===
    const radioHariIni = document.getElementById("mulai_hari_ini");
    const radioCustom = document.getElementById("mulai_bulan");
    const customPeriode = document.getElementById("custom_periode");

    function togglePeriode() {
        if (radioCustom && radioCustom.checked) {
            customPeriode.classList.remove("hidden");
        } else if (customPeriode) {
            customPeriode.classList.add("hidden");
        }
    }

    if (radioHariIni) radioHariIni.addEventListener("change", togglePeriode);
    if (radioCustom) radioCustom.addEventListener("change", togglePeriode);

    // panggil sekali biar sesuai default
    togglePeriode();
});
