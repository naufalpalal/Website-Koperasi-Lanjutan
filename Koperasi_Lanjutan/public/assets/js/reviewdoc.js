const input = document.getElementById('dokumen_pinjaman');
const previewContainer = document.getElementById('previewContainer');

input.addEventListener('change', () => {
    previewContainer.innerHTML = ''; // kosongkan preview lama

    const files = input.files;
    if (files.length === 0) return;

    Array.from(files).forEach(file => {
        if (file.type === "application/pdf") {
            const fileURL = URL.createObjectURL(file);

            // buat wrapper untuk mengatur margin/padding
            const wrapper = document.createElement('div');
            wrapper.className = "w-full flex justify-center mb-4"; // flex center untuk arah ke tengah
            wrapper.style.padding = "0 1rem"; // jarak kiri-kanan

            const iframe = document.createElement('iframe');
            iframe.src = fileURL;
            iframe.className = "w-full max-w-3xl h-64 border rounded-lg"; // max-width supaya tidak mepet
            wrapper.appendChild(iframe);

            previewContainer.appendChild(wrapper);
        }
    });
});
