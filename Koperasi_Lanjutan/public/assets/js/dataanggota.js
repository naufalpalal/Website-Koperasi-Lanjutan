document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('searchInput');
  const searchButton = document.getElementById('searchButton');
  const resultsContainer = document.getElementById('anggotaResultsContainer');
  const currentRoute = document.getElementById('anggotaResultsContainer').dataset.route;

  // Fungsi utama untuk mengambil data dari server
  function fetchData(q, page = 1) {
    // Tentukan URL AJAX:
    const url = `${currentRoute}?q=${encodeURIComponent(q)}&page=${page}`;

    // Tampilkan visual loading
    resultsContainer.style.opacity = '0.5';

    fetch(url, {})
      .then(response => response.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.getElementById('anggotaResultsContainer').innerHTML;

        resultsContainer.innerHTML = newContent;
        resultsContainer.style.opacity = '1';
        bindPaginationListeners(q);
      })

      .catch(error => {
        console.error('Error fetching data:', error);
        resultsContainer.style.opacity = '1';
        alert('Gagal mengambil data. Silakan coba lagi.');
      });
  }

  // Event Listener untuk tombol "Cari"
  searchButton.addEventListener('click', function () {
    const query = searchInput.value;
    fetchData(query, 1); // Selalu mulai dari halaman 1 saat pencarian baru
  });

  // Event Listener untuk Enter di input field
  searchInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault(); // Mencegah submit form bawaan
      searchButton.click();
    }
  });

  // Fungsi untuk membuat link pagination bekerja dengan AJAX
  function bindPaginationListeners(currentQuery) {
    // Target semua link pagination di dalam container yang baru dimuat
    resultsContainer.querySelectorAll('.mt-6 a[href*="page="]').forEach(link => {
      // Cek apakah listener sudah ada untuk mencegah duplikasi
      if (!link.classList.contains('ajax-bound')) {
        link.classList.add('ajax-bound'); // Tambahkan flag
        link.addEventListener('click', function (e) {
          e.preventDefault();
          const url = new URL(this.href);
          const page = url.searchParams.get('page');

          // Panggil fetchData lagi dengan query saat ini dan nomor halaman baru
          fetchData(currentQuery, page);
        });
      }
    });
  }

  // Panggil bindPaginationListeners saat halaman pertama kali dimuat
  bindPaginationListeners(searchInput.value);
});