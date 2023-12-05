window.editAward = function (awardId) {
    let options = {
        method : 'GET',
        headers: window.ajaxHeaders
    }

    fetch(`./awards/${awardId}`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_get_award_title').value,
                description: document.getElementById('error_get_award').value,
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.id) {
                document.getElementById('award_id').value           = jsonResponse.id;
                document.getElementById('award_event').value        = jsonResponse.event_id;
                document.getElementById('award_presentation').value = jsonResponse.presentation_id;
                document.getElementById('award_user').value         = jsonResponse.user_id;

                document.getElementById('mode_award_modal').value      = 'edit';
                document.getElementById('modal_award_title').innerHTML = document.getElementById('edit_award_modal_title').value;
                bootstrap.Modal.getOrCreateInstance('#modal_edit_award').show();
            } else {
                window.modalMessage({
                    title      : document.getElementById('error_get_award_title').value,
                    description: document.getElementById('error_get_award_description').value
                });
            }
        });
    });
}

window.deleteAward = function (id) {
    let options = {
        method : 'DELETE',
        headers: window.ajaxHeaders
    }
    fetch(`./awards/${id}`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_get_award_title').value,
                description: document.getElementById('error_get_award').value,
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.error) {
                window.modalMessage({
                    title      : document.getElementById('error_get_award_title').value,
                    description: jsonResponse.error,
                });
            } else {
                window.location.reload();
            }
        });
    })
}

window.associateUser = function (notificationId) {

}
document.getElementById('form_save_award').addEventListener('submit', function (event) {
    event.preventDefault();
    let method = document.getElementById('mode_award_modal').value === 'edit' ? 'PUT' : 'POST'

    let options = {
        method : method,
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            event_id       : document.getElementById('award_event').value,
            presentation_id: document.getElementById('award_presentation').value,
            user_id        : document.getElementById('award_user').value,
        })
    }

    let url = method === 'PUT' ? `./awards/${document.getElementById('award_id').value}` : `./awards`;
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

document.getElementById('btn_create_award').addEventListener('click', function () {
    document.getElementById('mode_award_modal').value      = 'create';
    document.getElementById('modal_award_title').innerHTML = document.getElementById('create_award_modal_title').value;
    document.getElementById('form_save_award').reset();
    bootstrap.Modal.getOrCreateInstance('#modal_edit_award').show();
});