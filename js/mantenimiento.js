// Get the modal
const modal = document.getElementById("editModal");

// Get all edit buttons
const editButtons = document.querySelectorAll(".edit-btn");

// Get the close button
const closeBtn = document.querySelector(".close");

// Get the cancel button
const cancelBtn = document.getElementById("cancelEdit");

// Function to format date from database (YYYY-MM-DD) to HTML date input format
function formatDateForInput(dateString) {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString;
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    } catch (e) {
        console.error("Error formatting date:", e);
        return dateString;
    }
}

// When the user clicks on an edit button, open the modal and fill the form
editButtons.forEach(button => {
    button.addEventListener("click", function() {
        const id = this.getAttribute("data-id");
        const autobus = this.getAttribute("data-autobus");
        const problema = this.getAttribute("data-problema");
        const reparacion = this.getAttribute("data-reparacion");
        const observaciones = this.getAttribute("data-observaciones");
        const costo = this.getAttribute("data-costo");
        const fecha = this.getAttribute("data-fecha");

        console.log("Data from attributes:", {
            id, autobus, problema, reparacion, observaciones, costo, fecha
        });

        document.getElementById("edit_id").value = id;
        document.getElementById("edit_placas").value = autobus; // Ensure this ID matches your HTML
        document.getElementById("edit_problema_detectado").value = problema;
        document.getElementById("edit_reparacion_realizada").value = reparacion;
        document.getElementById("edit_observaciones").value = observaciones || '';
        document.getElementById("edit_costo").value = costo;
        document.getElementById("edit_fecha_mantenimiento").value = formatDateForInput(fecha);

        modal.style.display = "block";
    });
});

// When the user clicks on the close button, close the modal
closeBtn.addEventListener("click", function() {
    modal.style.display = "none";
});

// When the user clicks on the cancel button, close the modal
cancelBtn.addEventListener("click", function() {
    modal.style.display = "none";
});

// When the user clicks anywhere outside of the modal, close it
window.addEventListener("click", function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
});