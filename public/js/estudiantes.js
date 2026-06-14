document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm('¿Eliminar este estudiante? Esta accion no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
});
