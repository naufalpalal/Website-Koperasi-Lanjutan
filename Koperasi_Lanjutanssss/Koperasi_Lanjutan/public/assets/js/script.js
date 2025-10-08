const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebarOverlay = document.getElementById('sidebarOverlay');

sidebarToggle.addEventListener('click', () => {
    sidebar.classList.remove('hidden');
    sidebar.classList.remove('-translate-x-full');
    sidebarOverlay.classList.remove('hidden');
});

sidebarOverlay.addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');
    sidebarOverlay.classList.add('hidden');
    setTimeout(() => sidebar.classList.add('hidden'), 300);
});

// Hide sidebar on mobile by default
if (window.innerWidth < 640) {
    sidebar.classList.add('hidden');
    sidebar.classList.add('-translate-x-full');
}
