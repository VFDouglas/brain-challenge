window.editUser = function (userId) {
    let options = {
        method : 'GET',
        headers: window.ajaxHeaders
    }

    fetch(`./users/${userId}`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_get_user_title').value,
                description: document.getElementById('error_get_user').value,
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.length > 0) {
                document.getElementById('user_id').value       = jsonResponse[0].id;
                document.getElementById('user_name').value     = jsonResponse[0].name;
                document.getElementById('user_email').value    = jsonResponse[0].email;
                document.getElementById('user_status').checked = !!jsonResponse[0].status;

                document.getElementById('mode_user_modal').value = 'edit';
                bootstrap.Modal.getOrCreateInstance('#modal_edit_user').show();
            } else {
                window.modalMessage({
                    title      : document.getElementById('error_get_user_title').value,
                    description: document.getElementById('error_get_user_description').value
                });
            }
        });
    });
}
document.getElementById('form_save_user').addEventListener('submit', function (event) {
    event.preventDefault();
    let method = document.getElementById('mode_user_modal').value === 'edit' ? 'PUT' : 'POST'

    let options = {
        method : method,
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            name  : document.getElementById('user_name').value,
            email : document.getElementById('user_email').value,
            status: document.getElementById('user_status').checked ? '1' : '0'
        })
    }

    let url = method === 'PUT' ? `./users/${document.getElementById('user_id').value}` : `./users`;
    fetch(url, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (retorno) {
            if (retorno) {
                window.location.reload();
            } else {
                window.modalMessage({
                    title      : document.getElementById('error_get_user_title').value,
                    description: document.getElementById('error_save_user').value
                });
            }
        });
    });
});

document.getElementById('btn_create_user').addEventListener('click', function () {
    document.getElementById('mode_user_modal').value = 'create';
    document.getElementById('form_save_user').reset();
    bootstrap.Modal.getOrCreateInstance('#modal_edit_user').show();
});