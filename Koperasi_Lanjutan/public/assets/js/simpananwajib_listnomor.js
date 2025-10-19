
document.addEventListener('DOMContentLoaded', function(){
    const perPage = 5;
    const rows = Array.from(document.querySelectorAll('#anggotaTableBody tr'));
    const paginationContainer = document.getElementById('paginationContainer');

    // if no rows or only the "Belum ada data" row, do nothing
    if(!rows.length || (rows.length === 1 && rows[0].querySelector('td[colspan]'))){
        if(paginationContainer) paginationContainer.style.display = 'none';
        return;
    }

    const total = rows.length;
    const totalPages = Math.ceil(total / perPage);

    function showPage(page){
        const start = (page - 1) * perPage;
        const end = start + perPage;
        rows.forEach((r, idx) => {
            r.style.display = (idx >= start && idx < end) ? '' : 'none';
        });
        renderPagination(page);
        // update query string ?page=
        const url = new URL(window.location);
        url.searchParams.set('page', page);
        window.history.replaceState({}, '', url);
    }

    function renderPagination(active){
        if(!paginationContainer) return;
        if(totalPages <= 1){ paginationContainer.innerHTML = ''; return; }

        let html = '<nav class="inline-flex items-center space-x-1">';
        // Prev
        if(active > 1){
            html += `<button data-page="${active-1}" class="px-3 py-1 bg-white border rounded text-sm hover:bg-gray-100">Prev</button>`;
        } else {
            html += `<span class="px-3 py-1 bg-gray-100 border rounded text-sm text-gray-400">Prev</span>`;
        }

        // window of pages
        const start = Math.max(1, active - 2);
        const end = Math.min(totalPages, active + 2);

        if(start > 1){ html += `<button data-page="1" class="px-3 py-1 bg-white border rounded text-sm hover:bg-gray-100">1</button>`; if(start > 2) html += `<span class="px-2">&hellip;</span>`; }

        for(let i = start; i <= end; i++){
            if(i === active){
                html += `<span class="px-3 py-1 bg-blue-600 text-white rounded text-sm">${i}</span>`;
            } else {
                html += `<button data-page="${i}" class="px-3 py-1 bg-white border rounded text-sm hover:bg-gray-100">${i}</button>`;
            }
        }

        if(end < totalPages){ if(end < totalPages - 1) html += `<span class="px-2">&hellip;</span>`; html += `<button data-page="${totalPages}" class="px-3 py-1 bg-white border rounded text-sm hover:bg-gray-100">${totalPages}</button>`; }

        // Next
        if(active < totalPages){
            html += `<button data-page="${active+1}" class="px-3 py-1 bg-white border rounded text-sm hover:bg-gray-100">Next</button>`;
        } else {
            html += `<span class="px-3 py-1 bg-gray-100 border rounded text-sm text-gray-400">Next</span>`;
        }

        html += '</nav>';
        paginationContainer.innerHTML = html;

        // attach handlers
        Array.from(paginationContainer.querySelectorAll('button[data-page]')).forEach(btn => {
            btn.addEventListener('click', function(){
                const p = parseInt(this.getAttribute('data-page')) || 1;
                showPage(p);
                // scroll to table top
                document.querySelector('#anggotaTableBody').scrollIntoView({behavior:'smooth'});
            });
        });
    }

    // initial page from query param
    const params = new URLSearchParams(window.location.search);
    const initPage = parseInt(params.get('page')) || 1;
    const page = Math.min(Math.max(1, initPage), totalPages);
    showPage(page);
});
