import './bootstrap';
/**
 * @function bootstrap.Modal.getOrCreateInstance
 */

window.onload = function () {
    /**
     * Destroying all tooltips when clicking, in case they get stuck.
     */
    document.addEventListener('click', function () {
        for (const tooltip of document.querySelectorAll('.tooltip.bs-tooltip-auto')) {
            tooltip.remove();
        }
    });
}

/**
 * Interval to read the next QR Code
 * @type number
 */
let interval;


// Hiding hamburger menu for small screens
if (window.screen.availWidth < 768) {
    setTimeout(() => {
        document.getElementById('sidebarToggleTop').click();
    }, 1);
}

/**
 * Headers automatically applied to all requests
 * @type {Headers}
 */
window.ajaxHeaders = new Headers({
    'Content-Type'    : 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN'    : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
});

/**
 * Show a modal with a custom message
 * @author Douglas Vicentini Ferreira
 * @param {string} params.title Title for the modal message.
 * @param {string} params.description Message for the modal body.
 * @param {string} params.type Type of the message to be displayed. Info, success, warning or error. Default: info.
 * @param {number} params.time Time for the modal to close.
 * @param {Function} params.callback Callback function.
 **/
window.modalMessage = function (params = {}) {
    if (!params.title || !params.description) {
        return false;
    }

    switch (params.type) {
        case 'success':
            params.title = '<i class="fa-solid fa-circle-check text-success"></i> ' + params.title;
            break;
        case 'warning':
            params.title = '<i class="fa-solid fa-exclamation-triangle text-warning"></i> ' + params.title;
            break;
        case 'error':
            params.title = '<i class="fa-solid fa-circle-xmark text-danger"></i> ' + params.title;
            break;
    }

    document.getElementById('modalMessageHeaderTitle').innerHTML = params.title;
    document.getElementById('modalMessageBody').innerHTML        = params.description;

    bootstrap.Modal.getOrCreateInstance('#modalMessage').show();

    if (typeof params.time === 'number') {
        setTimeout(() => {
            bootstrap.Modal.getOrCreateInstance('#modalMessage').hide();
        }, params.time);
    }

    if (typeof params.callback === 'function') {
        if (typeof params.time === 'number') {
            setTimeout(() => {
                params.callback();
            }, params.time);
        } else {
            params.callback();
        }
    }
}

window.enableTooltips = function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
}
window.enableTooltips();

/**
 * Log user access
 * @author Douglas Vicentini Ferreira
 **/
window.logAccess = function (page, description) {
    let options = {
        method : 'POST',
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            page       : page,
            description: description
        })
    }
    fetch(`./log_access`, options).then();
}

const DATETIME = new Intl.DateTimeFormat("pt-BR", {
    year  : 'numeric',
    month : 'numeric',
    day   : 'numeric',
    hour  : 'numeric',
    minute: 'numeric',
    second: 'numeric',
    hour12: false
});

window.readNotification = function (notificationId) {
    window.logAccess('*', 'Clicked to read notification');

    let qttUnread = +document.getElementById('unread_notifications')?.getAttribute('data-unread-notifications') || 0;

    let options = {
        method : 'PUT',
        headers: window.ajaxHeaders
    }

    fetch(`./read_notification/${notificationId}`, options).then(function (response) {
        if (!response.ok) {
            window.logAccess('*', 'Failed to read notification');
        }
        response.json().then(function (jsonResponse) {
            if (!jsonResponse.error) {
                document.getElementById('unread_notifications').setAttribute(
                    'data-unread-notifications', (qttUnread - 1).toString()
                );
                document.getElementById(`btn_notification_${notificationId}`).removeAttribute('onclick');
                document.getElementById(`btn_notification_modal_${notificationId}`).removeAttribute('onclick');
                document.getElementById(`icon_notification_${notificationId}`).remove();

                if (qttUnread - 1 === 0) {
                    document.getElementById('unread_notifications').remove();
                } else {
                    document.querySelector('#unread_notifications b').innerHTML = '0';
                }
            } else {
                window.logAccess('*', 'Failed to read notification');
            }
        });
    })
}

Array.from(document.getElementsByClassName('div_presentation_name')).forEach(element => {
    element.addEventListener('click', () => {
        window.location.href = '/qrcode';
    });
});

document.getElementById('btn_edit_profile')?.addEventListener('click', function () {
    let options = {
        method : 'GET',
        headers: window.ajaxHeaders
    }

    fetch(`./logged_user`, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.id) {
                bootstrap.Modal.getOrCreateInstance('#modal_edit_profile').show();
                document.getElementById('profile_name_modal').value  = jsonResponse.name;
                document.getElementById('profile_email_modal').value = jsonResponse.email;
            } else {
                //
            }
        });
    });
});

document.getElementById('form_edit_profile').addEventListener('submit', function (event) {
    event.preventDefault();

    let options = {
        method : 'PUT',
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            name : document.getElementById('profile_name_modal').value,
            email: document.getElementById('profile_email_modal').value
        })
    }

    fetch(`./update_profile`, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (jsonResponse) {
            bootstrap.Modal.getOrCreateInstance('#modal_edit_profile').hide();
            if (!jsonResponse.error) {
                document.getElementById('profile_name').innerHTML = jsonResponse.name;
            } else {
                window.modalMessage({
                    title      : document.getElementById('error_update_profile').value,
                    description: jsonResponse.error,
                    type       : 'error',
                    show       : true
                });
            }
        });
    });
});

document.getElementById('btn_detailed_score_modal')?.addEventListener('click', function () {
    this.querySelector('i').className = 'fa-solid fa-circle-notch fa-spin';

    let options = {
        headers: window.ajaxHeaders,
        method : 'GET'
    }

    fetch(`./detailed_score`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : document.getElementById('error_detailed_score_modal_title').value,
                description: document.getElementById('error_fetch_detailed_score').value,
                type       : 'error',
                show       : true
            });
            document.querySelector('#btn_detailed_score_modal i').className = 'fa-solid fa-circle-info';
            return false;
        }
        response.json().then(function (jsonResponse) {
            let html = '';
            if (jsonResponse.length > 0) {
                jsonResponse.forEach(function (element) {
                    html += `
                        <div class='row align-items-center'>
                            <div class="col-8">${element.description}</div>
                            <div class="col-4 text-nowrap text-success">
                                ${element.score}
                                ${document.getElementById('points_abbreviation').value}
                            </div>
                        </div>
                    `;
                });
            } else {
                html = `
                    <div class='row'>
                        <div class="col-12">
                            <b>No detailed score found</b>
                        </div>
                    </div>
                `;
            }
            document.querySelector('#btn_detailed_score_modal i').className       = 'fa-solid fa-circle-info';
            document.querySelector('#modal_detailed_score .modal-body').innerHTML = html;
            bootstrap.Modal.getOrCreateInstance('#modal_detailed_score').show();
        });
    });
});

document.getElementById('btn_show_all_notifications')?.addEventListener('click', function () {
    let options = {
        method : 'GET',
        headers: window.ajax_headers
    }

    fetch(`/get_notifications`, options).then(function (response) {
        if (!response.ok) {
            return false;
        }
        response.json().then(function (jsonResponse) {
            let html = `
                <div class='row align-items-center'>
                    <div class="col-12">
                        <div class="accordion" id="accordionModalNotifications">
            `;
            for (const element of jsonResponse) {
                let iconeNaoLido = '';
                if (!element.read_at) {
                    iconeNaoLido = `
                        <i class="fa-solid fa-circle fs-6 text-warning
                           position-absolute end-0 me-2" id="icon_notification_modal_${element.id}"></i>
                    `;
                }
                html += `
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed position-relative"
                                    type="button" id="btn_notification_modal_${element.id}"
                                    data-bs-toggle="collapse" data-bs-target="#notification_modal_${element.id}"
                                    onclick="readNotification(${element.id})">
                                ${element.title}
                                ${iconeNaoLido}
                            </button>
                        </h2>
                        <div id="notification_modal_${element.id}" class="accordion-collapse collapse">
                            <div class="accordion-body">${element.description}</div>
                        </div>
                    </div>
                `;
            }
            html += `    
                        </div>
                    </div>
                </div>
            `;
            document.querySelector('#modal_notifications .modal-body').innerHTML = html;
            bootstrap.Modal.getOrCreateInstance('#modal_notifications').show();
        });
    });
});
