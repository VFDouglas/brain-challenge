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

    let qttUnread = +document.getElementById('unread_notifications').getAttribute('data-unread-notifications');

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



let ExcelToJSON = function () {
    this.parseExcel = function (file) {
        let reader = new FileReader();

        let json_planilha = "";

        reader.onload = function (e) {
            let data     = e.target.result;
            let workbook = XLSX.read(data, {
                type: 'binary'
            });
            workbook.SheetNames.forEach(function (sheetName) {
                // Here is your object
                let XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                let json_object   = JSON.stringify(XL_row_object);
                json_planilha += json_object.replace(/[“”‘’]/g, ''); // Adiciona o retorno após cada execução e retira
                                                                     // aspas especiais
            })
            upload_arquivo(json_planilha)
        };

        reader.onerror = function (ex) {
            modalMessage("Erro ao ler a planilha.", "alert-danger", "S", "S", null, "far fa-times-circle", 2500);
        };

        reader.readAsBinaryString(file);
    };
};

if (document.getElementById("input_arquivo")) {
    document.getElementById("input_arquivo").
        addEventListener("change", gera_json, false);
}

function gera_json(event)
{
    let arquivos  = event.target.files; // Objeto de arquivos
    let conversao = new ExcelToJSON();

    Array.from(arquivos).forEach(element => { // Cria um array a partir de um objeto e o percorre
        conversao.parseExcel(element);
    });
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
            if (jsonResponse) {
                //
            } else {
                //
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
        response.json().then(function (retorno) {
            let html = '';
            if (retorno.length > 0) {
                retorno.forEach(function (element) {
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
