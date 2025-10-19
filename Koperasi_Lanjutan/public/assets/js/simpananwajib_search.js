// file: public/assets/js/simpananwajib_search.js
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const btnSearch = document.getElementById('btnSearch');
    const clearBtn = document.getElementById('clearSearch');
    const tbody = document.getElementById('anggotaTableBody');
    if (!tbody) return;

    const rows = Array.from(tbody.querySelectorAll('tr'));

    function doSearch() {
        const query = searchInput.value.trim().toLowerCase();
        let found = false;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (query === '' || text.includes(query)) {
                row.style.display = '';
                found = true;
            } else {
                row.style.display = 'none';
            }
        });

        // Jika tidak ditemukan
        if (!found && query !== '') {
            rows.forEach(row => row.style.display = 'none');
            if (!document.getElementById('noResultRow')) {
                const noResult = document.createElement('tr');
                noResult.id = 'noResultRow';
                noResult.innerHTML = `
                    <td colspan="4" class="text-center py-4 text-gray-500">
                        Tidak ada hasil ditemukan
                    </td>`;
                tbody.appendChild(noResult);
            }
        } else {
            const noResult = document.getElementById('noResultRow');
            if (noResult) noResult.remove();
        }
    }

    // Tombol Cari
    if(btnSearch) btnSearch.addEventListener('click', doSearch);

    // Enter di input search
    if(searchInput) searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') doSearch();
    });
});
