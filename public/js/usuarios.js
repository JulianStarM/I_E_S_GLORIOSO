document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm('¿Esta seguro de eliminar este usuario? Esta accion no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
});
