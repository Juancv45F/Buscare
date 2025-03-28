// Función para abrir el modal de edición
function editarNotificacion(id_notificacion) {
    document.getElementById('edit_id_notificacion').value = id_notificacion;
    document.getElementById('editarNotificacionModal').style.display = 'block';
}




// Función para eliminar una notificación
function eliminarNotificacion(id_notificacion) {
    // Mostrar el modal de confirmación
    document.getElementById('delete_id_notificacion').value = id_notificacion;
    document.getElementById('eliminarNotificacionModal').style.display = 'block';
}

// Función para confirmar la eliminación
function confirmarEliminarNotificacion() {
    const id_notificacion = document.getElementById('delete_id_notificacion').value;

    fetch('./php/eliminar_notificacion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_notificacion: id_notificacion })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al eliminar la notificación');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload(); // Recargar la página para actualizar la tabla
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al eliminar la notificación.');
    });
}

// Función para cerrar un modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Manejar el envío del formulario de edición
document.getElementById('editarNotificacionForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const datos = {
        id_notificacion: document.getElementById('edit_id_notificacion').value,
        tipo_mantenimiento: document.getElementById('edit_tipo_mantenimiento').value,
        descripcion: document.getElementById('edit_descripcion').value,
        fecha_programada: document.getElementById('edit_fecha_programada').value,
        estado: document.getElementById('edit_estado').value
    };

    fetch('./php/editar_notificacion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al editar la notificación');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload(); // Recargar la página para actualizar la tabla
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al editar la notificación.');
    });
});

