window.editSchedule = function (scheduleId) {
    let options = {
        method : 'GET',
        headers: window.ajaxHeaders
    }

    fetch(`./schedules/${scheduleId}`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_get_schedule_title').value,
                description: document.getElementById('error_get_schedule').value,
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.id) {
                document.getElementById('schedule_id').value          = jsonResponse.id;
                document.getElementById('schedule_event').value       = jsonResponse.event_id;
                document.getElementById('schedule_title').value       = jsonResponse.title;
                document.getElementById('schedule_description').value = jsonResponse.description;
                document.getElementById('schedule_starts_at').value   = jsonResponse.starts_at;
                document.getElementById('schedule_ends_at').value     = jsonResponse.ends_at;

                document.getElementById('mode_schedule_modal').value      = 'edit';
                document.getElementById('modal_schedule_title').innerHTML = document.getElementById('edit_schedule_modal_title').value;
                bootstrap.Modal.getOrCreateInstance('#modal_edit_schedule').show();
            } else {
                window.modalMessage({
                    title      : document.getElementById('error_get_schedule_title').value,
                    description: document.getElementById('error_get_schedule_description').value
                });
            }
        });
    });
}

window.deleteSchedule = function (id) {
    let options = {
        method : 'DELETE',
        headers: window.ajaxHeaders
    }
    fetch(`./schedules/${id}`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_get_schedule_title').value,
                description: document.getElementById('error_get_schedule').value,
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.error) {
                window.modalMessage({
                    title      : document.getElementById('error_get_schedule_title').value,
                    description: jsonResponse.error,
                });
            } else {
                window.location.reload();
            }
        });
    })
}
document.getElementById('form_save_schedule').addEventListener('submit', function (event) {
    event.preventDefault();
    let method = document.getElementById('mode_schedule_modal').value === 'edit' ? 'PUT' : 'POST'

    let options = {
        method : method,
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            title      : document.getElementById('schedule_title').value,
            description: document.getElementById('schedule_description').value,
            starts_at  : document.getElementById('schedule_starts_at').value,
            ends_at    : document.getElementById('schedule_ends_at').value,
            event_id   : +document.getElementById('schedule_event').value
        })
    }

    let url = method === 'PUT' ? `./schedules/${document.getElementById('schedule_id').value}` : `./schedules`;
    fetch(url, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (!jsonResponse.error) {
                window.location.reload();
            } else {
                document.getElementById('msg_error_modal').innerHTML = jsonResponse.error;
                setTimeout(() => {
                    document.getElementById('msg_error_modal').innerHTML = '';
                }, 2000);
            }
        });
    });
});

document.getElementById('btn_create_schedule').addEventListener('click', function () {
    document.getElementById('mode_schedule_modal').value      = 'create';
    document.getElementById('modal_schedule_title').innerHTML = document.getElementById('create_schedule_modal_title').value;
    document.getElementById('form_save_schedule').reset();
    bootstrap.Modal.getOrCreateInstance('#modal_edit_schedule').show();
});