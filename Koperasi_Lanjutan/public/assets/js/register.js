    const jenisSelect = document.getElementById('jenis_anggota');
    const formFields = document.getElementById('form_fields');
    const fieldNipUsername = document.getElementById('field_nip_username');
    const labelNipUsername = document.getElementById('label_nip_username');

    jenisSelect.addEventListener('change', function() {
        if(this.value === 'pegawai') {
            formFields.style.display = 'grid'; // tampilkan seluruh form
            fieldNipUsername.style.display = 'block';
            labelNipUsername.textContent = 'NIP';
        } else if(this.value === 'non_pegawai') {
            formFields.style.display = 'grid';
            fieldNipUsername.style.display = 'block';
            labelNipUsername.textContent = 'Username';
        } else {
            formFields.style.display = 'none'; // sembunyikan seluruh form
        }
    });
