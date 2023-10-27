window.editPresentation = function (presentationId) {
    let options = {
        method : 'GET',
        headers: window.ajaxHeaders
    }

    fetch(`./presentations/${presentationId}`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_get_presentation_title').value,
                description: document.getElementById('error_get_presentation').value,
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.id) {
                document.getElementById('presentation_event').value     = jsonResponse.event_id;
                document.getElementById('presentation_name').value      = jsonResponse.name;
                document.getElementById('presentation_user').value      = jsonResponse.user_id;
                document.getElementById('presentation_starts_at').value = jsonResponse.starts_at;
                document.getElementById('presentation_ends_at').value   = jsonResponse.ends_at;
                document.getElementById('presentation_status').checked  = !!jsonResponse.status;

                document.getElementById('mode_presentation_modal').value      = 'edit';
                document.getElementById('modal_presentation_title').innerHTML = document.getElementById('edit_presentation_modal_title').value;
                bootstrap.Modal.getOrCreateInstance('#modal_edit_presentation').show();
            } else {
                window.modalMessage({
                    title      : document.getElementById('error_get_presentation_title').value,
                    description: document.getElementById('error_get_presentation_description').value
                });
            }
        });
    });
}

window.deletePresentation = function (id) {
    let options = {
        method : 'DELETE',
        headers: window.ajaxHeaders
    }
    fetch(`./presentations/${id}`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_get_presentation_title').value,
                description: document.getElementById('error_get_presentation').value,
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.error) {
                window.modalMessage({
                    title      : document.getElementById('error_get_presentation_title').value,
                    description: jsonResponse.error,
                });
            } else {
                window.location.reload();
            }
        });
    })
}
document.getElementById('form_save_presentation').addEventListener('submit', function (event) {
    event.preventDefault();
    let method = document.getElementById('mode_presentation_modal').value === 'edit' ? 'PUT' : 'POST'

    let options = {
        method : method,
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            name     : document.getElementById('presentation_name').value,
            user_id  : document.getElementById('presentation_user').value,
            starts_at: document.getElementById('presentation_starts_at').value,
            ends_at  : document.getElementById('presentation_ends_at').value,
            status   : document.getElementById('presentation_status').checked,
            event_id : +document.getElementById('presentation_event').value
        })
    }

    let url = method === 'PUT' ? `./presentations/${document.getElementById('presentation_id').value}` : `./presentations`;
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

document.getElementById('btn_create_presentation').addEventListener('click', function () {
    document.getElementById('mode_presentation_modal').value      = 'create';
    document.getElementById('modal_presentation_title').innerHTML = document.getElementById('create_presentation_modal_title').value;
    document.getElementById('form_save_presentation').reset();
    bootstrap.Modal.getOrCreateInstance('#modal_edit_presentation').show();
});