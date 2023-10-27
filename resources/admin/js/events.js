window.editEvent   = function (eventId) {
    let options = {
        method : 'GET',
        headers: window.ajaxHeaders
    }

    fetch(`./events/${eventId}`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_get_event_title').value,
                description: document.getElementById('error_get_event').value,
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.id) {
                document.getElementById('event_id').value        = jsonResponse.id;
                document.getElementById('event_name').value      = jsonResponse.name;
                document.getElementById('event_location').value  = jsonResponse.location;
                document.getElementById('event_starts_at').value = jsonResponse.starts_at;
                document.getElementById('event_ends_at').value   = jsonResponse.ends_at;
                document.getElementById('event_status').checked  = !!jsonResponse.status;

                document.getElementById('mode_event_modal').value = 'edit';
                bootstrap.Modal.getOrCreateInstance('#modal_edit_event').show();
            } else {
                window.modalMessage({
                    title      : document.getElementById('error_get_event_title').value,
                    description: 'No event found'
                });
            }
        });
    });
}
window.deleteEvent = function (id) {
    let options = {
        method : 'DELETE',
        headers: window.ajaxHeaders
    }
    fetch(`./events/${id}`, options).then(function (response) {
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
document.getElementById('form_save_event').addEventListener('submit', function (event) {
    event.preventDefault();
    let method = document.getElementById('mode_event_modal').value === 'edit' ? 'PUT' : 'POST'

    let options = {
        method : method,
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            name     : document.getElementById('event_name').value,
            location : document.getElementById('event_location').value,
            starts_at: document.getElementById('event_starts_at').value,
            ends_at  : document.getElementById('event_ends_at').value,
            status   : document.getElementById('event_status').checked ? '1' : '0'
        })
    }

    let url = method === 'PUT' ? `./events/${document.getElementById('event_id').value}` : `./events`;
    fetch(url, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (retorno) {
            if (retorno) {
                window.location.reload();
            } else {
                window.modalMessage({
                    title      : document.getElementById('error_get_event_title').value,
                    description: document.getElementById('error_get_event').value
                });
            }
        });
    });
});

document.getElementById('btn_create_event').addEventListener('click', function () {
    document.getElementById('mode_event_modal').value = 'create';
    document.getElementById('form_save_event').reset();
    bootstrap.Modal.getOrCreateInstance('#modal_edit_event').show();
});