window.editEvent = function (eventId) {
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
        response.json().then(function (retorno) {
            if (retorno.length > 0) {
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