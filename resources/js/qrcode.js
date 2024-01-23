document.getElementById('form_scan_qrcode').addEventListener('submit', function (event) {
    event.preventDefault();

    let options = {
        method : 'POST',
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            qrcode: document.getElementById('presentation_qrcode').value
        })
    }

    fetch(`./scan_qrcode`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : 'QR Code',
                description: document.getElementById('error_scan_qrcode').value,
                type       : 'error',
                time       : 2000
            })
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.error) {
                window.modalMessage({
                    title      : 'QR Code',
                    description: jsonResponse.error,
                    type       : 'error',
                    time       : 3000
                });
            } else {
                window.modalMessage({
                    title      : 'QR Code',
                    description: document.getElementById('success_scan_qrcode').value,
                    type       : 'success',
                    time       : 2000
                });
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    let scanner = new Instascan.Scanner({
        video : document.getElementById('preview'),
        mirror: false
    });

    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            scanner.camera = cameras[cameras.length - 1];
            scanner.start();
            logAccess('QR Code', 'Camera enabled.');
        } else {
            window.modalMessage({
                title      : 'QR Code',
                description: 'Camera not found. Type the code below',
                type       : 'error'
            });
            logAccess('QR Code', 'Camera not found.');
            setTimeout(() => {
                document.getElementById('presentation_qrcode').focus();
            }, 2000);
        }
    }).catch(function (e) {
        window.modalMessage({
            title      : 'QR Code',
            description: 'Camera not found. Type the code below',
            type       : 'error'
        });
        logAccess('QR Code', 'Camera not found.');
        setTimeout(() => {
            document.getElementById('presentation_qrcode').focus();
        }, 2000);
    });

    scanner.addListener('scan', function (content) {
        if (content.length !== 8) {
            logAccess('QR Code', 'QR Code ' + content + ' scanned.');
            document.getElementById('presentation_qrcode').value = content;
            document.getElementById('btn_send_qrcode').click();
        } else {
            logAccess('QR Code', 'Invalid QR code scanned.');
            window.modalMessage({
                title      : 'QR Code',
                description: 'Invalid QR code scanned',
                type       : 'error'
            });
        }
    });
});
