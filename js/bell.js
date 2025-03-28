document.addEventListener('DOMContentLoaded', function() {
    const notificationIndicator = document.getElementById('notification-indicator');

    // Function to check for notifications
    function checkNotifications() {
        fetch('./php/fetch_notifications.php')
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    notificationIndicator.style.display = 'block'; // Show red circle
                } else {
                    notificationIndicator.style.display = 'none'; // Hide red circle
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    // Check notifications on page load
    checkNotifications();

    // Toggle notifications display on bell button click
    document.getElementById('bell-btn').addEventListener('click', function() {
        const notificationDisplay = document.getElementById('notification-display');

        if (notificationDisplay.style.display === 'none' || notificationDisplay.style.display === '') {
            fetch('./php/fetch_notifications.php')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notification-list');
                    notificationList.innerHTML = ''; // Clear previous notifications

                    if (data.length > 0) {
                        data.forEach(notification => {
                            const listItem = document.createElement('li');
                            listItem.textContent = `Placa: ${notification.placas}, Tipo: ${notification.tipo_mantenimiento}, Fecha: ${notification.fecha_programada}`;
                            notificationList.appendChild(listItem);
                        });
                        notificationDisplay.style.display = 'block';
                    } else {
                        notificationDisplay.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
        } else {
            notificationDisplay.style.display = 'none';
        }
    });
});