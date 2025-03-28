// Obtener los modales
const modal = document.getElementById("problemaModal");
const editModal = document.getElementById("editProblemaModal");
const deleteModal = document.getElementById("deleteProblemaModal");

// Elementos del modal de registro
const modalBusId = document.getElementById("modalBusId");
const modalIdInput = document.getElementById("modal_id_autobus");
const formIdInput = document.getElementById("id_autobus");

// Elementos del modal de edición
const editModalBusId = document.getElementById("editModalBusId");
const editModalIdInput = document.getElementById("edit_modal_id_autobus");
const editProblemaInput = document.getElementById("edit_problema");

// Elementos del modal de eliminación
const deleteModalBusId = document.getElementById("deleteModalBusId");
const deleteModalIdInput = document.getElementById("delete_modal_id_autobus");

// Obtener los botones para cerrar los modales
const closeBtns = document.getElementsByClassName("close");

// Agregar evento a todos los botones de problema
const problemaBtns = document.querySelectorAll(".problema-btn");
problemaBtns.forEach(btn => {
    btn.addEventListener("click", function() {
        const busId = this.getAttribute("data-id");
        modalBusId.textContent = busId;
        modalIdInput.value = busId;
        formIdInput.value = busId;
        modal.style.display = "block";
    });
});

// Agregar evento a todos los botones de editar
const editBtns = document.querySelectorAll(".edit-btn");
editBtns.forEach(btn => {
    btn.addEventListener("click", function() {
        const busId = this.getAttribute("data-id");
        const problema = this.getAttribute("data-problema");

        editModalBusId.textContent = busId;
        editModalIdInput.value = busId;
        editProblemaInput.value = problema;

        editModal.style.display = "block";
    });
});

// Agregar evento a todos los botones de eliminar
const deleteBtns = document.querySelectorAll(".delete-btn");
deleteBtns.forEach(btn => {
    btn.addEventListener("click", function() {
        const busId = this.getAttribute("data-id");

        deleteModalBusId.textContent = busId;
        deleteModalIdInput.value = busId;

        deleteModal.style.display = "block";
    });
});

// Función para cerrar el modal de eliminación
function closeDeleteModal() {
    deleteModal.style.display = "none";
}

// Cerrar los modales al hacer clic en la X
Array.from(closeBtns).forEach(btn => {
    btn.addEventListener("click", function() {
        modal.style.display = "none";
        editModal.style.display = "none";
        deleteModal.style.display = "none";
    });
});

// Cerrar los modales al hacer clic fuera de ellos
window.addEventListener("click", function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
    if (event.target == editModal) {
        editModal.style.display = "none";
    }
    if (event.target == deleteModal) {
        deleteModal.style.display = "none";
    }
});