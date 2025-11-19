// public/js/custom/bendahara-setting.js

function addBendahara() {
    const wrapper = document.getElementById('bendahara-wrapper');
    
    // Membuat elemen div baru
    const div = document.createElement('div');
    div.className = 'flex gap-2 bendahara-item mt-2';
    
    // HTML untuk input baru + tombol hapus
    div.innerHTML = `
        <input type="text" name="nama_bendahara_koperasi[]" 
            placeholder="Masukkan Nama User Bendahara"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        <button type="button" onclick="removeInput(this)" class="bg-red-500 text-white px-3 rounded-lg hover:bg-red-600">X</button>
    `;
    
    wrapper.appendChild(div);
}

function removeInput(btn) {
    // Menghapus elemen parent (div .bendahara-item) dari tombol yang diklik
    btn.closest('.bendahara-item').remove();
}