document.addEventListener('DOMContentLoaded', function () {
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');
    const defaultAvatar = document.getElementById('defaultAvatar');

    if(photoInput && photoPreview){
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    if(defaultAvatar) defaultAvatar.style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                photoPreview.src = photoPreview.getAttribute('data-original') || '';
                if(defaultAvatar) defaultAvatar.style.display = 'block';
            }
        });
    }
});
