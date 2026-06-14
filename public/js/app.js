document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.querySelector('.app-wrapper');
    const toggle  = document.getElementById('toggleSidebar');

    if (toggle && wrapper) {
        toggle.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                wrapper.classList.toggle('sidebar-open');
            } else {
                wrapper.classList.toggle('sidebar-collapsed');
            }
        });
    }

    // Auto-cierre de alerts
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            const inst = bootstrap.Alert.getOrCreateInstance(el);
            inst.close();
        }, 5000);
    });
});
