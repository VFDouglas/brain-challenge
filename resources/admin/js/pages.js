window.editPage = function (pageId) {
    let options = {
        method : 'GET',
        headers: window.ajaxHeaders
    }

    fetch(`./pages/${pageId}`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_get_page_title').value,
                description: document.getElementById('error_get_page').value,
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.length > 0) {
                let html = '';
                for (const item of jsonResponse) {
                    html += `
                        <tr>
                            <td>
                                <input type="checkbox" ${item.user_id == item.id ? 'checked' : ''}
                                       id="user_${item.id}" class="form-check-input">
                            </td>
                            <td><label for="user_${item.id}">${item.name}</label></td>
                        </tr>
                    `;
                }
                document.querySelector('#table_associate_user tbody').innerHTML = html;
                bootstrap.Modal.getOrCreateInstance('#modal_edit_page').show();
            } else {
                document.getElementById('table_associate_user').innerHTML = '';
                window.modalMessage({
                    title      : document.getElementById('error_get_page_title').value,
                    description: 'No page found'
                });
            }
        });
    });
}
document.getElementById('form_save_page').addEventListener('submit', function (event) {
    event.preventDefault();
    let method = document.getElementById('mode_page_modal').value === 'edit' ? 'PUT' : 'POST';

    let options = {
        method : method,
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            name     : document.getElementById('page_name').value,
            location : document.getElementById('page_location').value,
            starts_at: document.getElementById('page_starts_at').value,
            ends_at  : document.getElementById('page_ends_at').value,
            status   : document.getElementById('page_status').checked ? '1' : '0'
        })
    }

    let url = method === 'PUT' ? `./pages/${document.getElementById('page_id').value}` : `./pages`;
    fetch(url, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (retorno) {
            if (retorno) {
                window.location.reload();
            } else {
                window.modalMessage({
                    title      : document.getElementById('error_get_page_title').value,
                    description: document.getElementById('error_get_page').value
                });
            }
        });
    });
});