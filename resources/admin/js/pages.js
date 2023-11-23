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
                document.getElementById('page_id').value = pageId;

                let html = '';
                for (const item of jsonResponse) {
                    html += `
                        <tr>
                            <td>
                                <input type="checkbox" ${item.user_id == item.id ? 'checked' : ''}
                                       data-page-id="${item.id}" class="form-check-input" id="user_${item.id}">
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

    let users = [];
    for (const row of document.querySelectorAll('#table_associate_user tbody tr')) {
        if (row.querySelector('input[type="checkbox"]:checked')) {
            users.push(row.querySelector('input[type="checkbox"]:checked').getAttribute('data-page-id'));
        }
    }

    let options = {
        method : 'POST',
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            pageId : document.getElementById('page_id').value,
            eventId: document.getElementById('page_event').value,
            users  : users
        })
    }

    fetch(`./pages/${document.getElementById('page_id').value}`, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (response) {
            if (!response.error) {
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