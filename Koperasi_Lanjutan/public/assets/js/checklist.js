// public/js/simpanan-wajib.js
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('input[name="anggota[]"]');

    if (!checkAll) return; // kalau tidak ada elemen, hentikan

    // Klik "Select All" => centang semua checkbox
    checkAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Kalau salah satu uncheck, maka "Select All" ikut nonaktif
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            if (!this.checked) checkAll.checked = false;
            else if ([...checkboxes].every(c => c.checked)) checkAll.checked = true;
        });
    });
});