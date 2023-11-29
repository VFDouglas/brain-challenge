window.editNotification = function (notificationId) {
    let options = {
        method : 'GET',
        headers: window.ajaxHeaders
    }

    fetch(`./notifications/${notificationId}`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_get_notification_title').value,
                description: document.getElementById('error_get_notification').value,
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.id > 0) {
                document.getElementById('notification_id').value          = notificationId;
                document.getElementById('notification_title').value       = jsonResponse.title;
                document.getElementById('notification_description').value = jsonResponse.description;
                document.getElementById('notification_status').checked    = +jsonResponse.status === 1;

                bootstrap.Modal.getOrCreateInstance('#modal_edit_notification').show();
            } else {
                window.modalMessage({
                    title      : document.getElementById('error_get_notification_title').value,
                    description: 'No notification found'
                });
            }
        });
    });
}
document.getElementById('form_save_notification').addEventListener('submit', function (event) {
    event.preventDefault();

    let users = [];
    for (const row of document.querySelectorAll('#table_associate_user tbody tr')) {
        if (row.querySelector('input[type="checkbox"]:checked')) {
            users.push(row.querySelector('input[type="checkbox"]:checked').getAttribute('data-notification-id'));
        }
    }

    let options = {
        method : 'POST',
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            notificationId: document.getElementById('notification_id').value,
            eventId       : document.getElementById('notification_event').value,
            users         : users
        })
    }

    fetch(`./notifications/${document.getElementById('notification_id').value}`, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (response) {
            if (!response.error) {
                window.location.reload();
            } else {
                window.modalMessage({
                    title      : document.getElementById('error_get_notification_title').value,
                    description: document.getElementById('error_get_notification').value
                });
            }
        });
    });
});

document.getElementById('btn_create_notification').addEventListener('click', function (event) {
    bootstrap.Modal.getOrCreateInstance('#modal_edit_notification').show();
    document.getElementById('form_save_notification').reset();
});