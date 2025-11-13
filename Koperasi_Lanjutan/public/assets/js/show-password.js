function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (!input) return;

    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);

    // Toggle icon
    const icon = btn.querySelector('i');
    if (icon) {
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }
}
