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
            if (jsonResponse.length > 0) {
                document.getElementById('presentation_id').value       = jsonResponse[0].id;
                document.getElementById('presentation_name').value     = jsonResponse[0].name;
                document.getElementById('presentation_email').value    = jsonResponse[0].email;
                document.getElementById('presentation_status').checked = !!jsonResponse[0].status;

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
            name  : document.getElementById('presentation_name').value,
            email : document.getElementById('presentation_email').value,
            status: document.getElementById('presentation_status').checked
        })
    }

    let url = method === 'PUT' ? `./presentations/${document.getElementById('presentation_id').value}` : `./presentations`;
    fetch(url, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (!jsonResponse.error) {
                // window.location.reload();
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