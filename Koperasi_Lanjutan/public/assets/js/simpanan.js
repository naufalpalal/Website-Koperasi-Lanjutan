// Check all
document.addEventListener('DOMContentLoaded', function () {
    const btnGenerate = document.getElementById('btnGenerate');
    const modalNominal = document.getElementById('modalNominal');
    const closeModal = document.getElementById('closeModal');

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

    closeModal.addEventListener('click', function () {
        modalNominal.classList.add('hidden');
    });


    // Move the select into the form for auto submit
    const select = document.getElementById('filterBulan');
    const form = document.getElementById('bulanForm');
    if(select && form){
        form.appendChild(select);
    }
});

