window.editNotification   = function (notificationId) {
    document.getElementById('mode_notification_modal').value = 'edit';

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
window.deleteNotification = function (id) {
    let options = {
        method : 'DELETE',
        headers: window.ajaxHeaders
    }
    fetch(`./notifications/${id}`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_get_event_title').value,
                description: document.getElementById('error_delete_event').value,
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.error) {
                window.modalMessage({
                    title      : document.getElementById('error_delete_event').value,
                    description: jsonResponse.error,
                });
            } else {
                window.location.reload();
            }
        });
    })
}

window.associateUser = function (notificationId) {
    let options = {
        method : 'GET',
        headers: window.ajax_headers
    }

    document.getElementById('notification_id').value = notificationId;

    fetch(`./notification_users`, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (response) {
            let html = '';
            if (response.length > 0) {
                for (const item of response) {
                    html += `
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input" value="${item.id}"
                                       id="user_${item.id}" ${item.id === item.user_id ? 'checked' : ''}>
                            </td>
                            <td class="text-start">
                                <label for="user_${item.id}">${item.name}</label>
                            </td>
                        </tr>
                    `;
                }
            } else {
                html = `<tr><td colspan="2">${document.getElementById('no_user_found').value}</td></tr>`;
            }
            document.querySelector('#table_bind_user tbody').innerHTML = html;
            bootstrap.Modal.getOrCreateInstance('#bind_user_modal').show();
        });
    });
}

window.checkAllUsers = function (mainCheckbox) {
    for (const user of document.querySelectorAll('#table_bind_user tbody tr')) {
        user.querySelector('input[type="checkbox"]').checked = mainCheckbox.checked;
    }
}

document.getElementById('form_save_notification').addEventListener('submit', function (event) {
    event.preventDefault();

    let method = document.getElementById('mode_notification_modal').value === 'edit' ? 'PUT' : 'POST'

    let options = {
        method : method,
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            notificationId: document.getElementById('notification_id').value,
            title         : document.getElementById('notification_title').value,
            description   : document.getElementById('notification_description').value,
            eventId       : document.getElementById('notification_event').value,
            status        : document.getElementById('notification_status').checked
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

document.getElementById('form_bind_user_notification').addEventListener('submit', function (event) {
    event.preventDefault();

    let users = [];

    for (const user of document.querySelectorAll('#table_bind_user tbody tr')) {
        if (user.querySelector('input[type="checkbox"]').checked) {
            users.push(+user.querySelector('input[type="checkbox"]').value);
        }
    }

    let options = {
        method : 'POST',
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            notificationId: document.getElementById('notification_id').value,
            users         : users,
            eventId       : document.getElementById('notification_event').value
        })
    }

    fetch(`./notification_users`, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (response) {
            bootstrap.Modal.getOrCreateInstance('#bind_user_modal').hide();
            if (response.error) {
                window.modalMessage({
                    title      : document.getElementById('error_get_notification_title').value,
                    description: response.error,
                    time       : 2500,
                    callback   : function () {
                        bootstrap.Modal.getOrCreateInstance('#bind_user_modal').show();
                    }
                });
            }
        });
    });
    console.log(users);
});

document.getElementById('btn_create_notification').addEventListener('click', function (event) {
    bootstrap.Modal.getOrCreateInstance('#modal_edit_notification').show();
    document.getElementById('form_save_notification').reset();
    document.getElementById('mode_notification_modal').value = 'create';
});
