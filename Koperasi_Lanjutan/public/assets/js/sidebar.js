document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        
        // Enable pointer events on overlay only when sidebar is visible
        if (sidebar.classList.contains('-translate-x-full')) {
            overlay.classList.add('pointer-events-none');
        } else {
            overlay.classList.remove('pointer-events-none');
        }
    });
});