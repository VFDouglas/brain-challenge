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
    // let settings = {
    //     url     : "client/grava_visita_estande.php",
    //     method  : "POST",
    //     timeout : 0,
    //     cache   : false,
    //     data    : {
    //         "qrcode": qrcode
    //     },
    //     dataType: "json",
    //     success : function (retorno) {
    //         document.querySelector(".spinner").classList.add("d-none");
    //         document.body.classList.remove("pe-none");
    //         if (retorno.msg === "sucesso") {
    //             modalMessage(`Visita ao estande '${retorno.apresentacao}' confirmada.`, "alert-success", "N", "S",
    // null, "far fa-check-circle", 4000); return false; } if (!!retorno.ultima_leitura) { gera_log("QR Code",
    // "Aguardando para escanear novo código.");
    // document.getElementById("tempo_restante").closest(".row").classList.remove("d-none");
    // document.getElementById("tempo_restante").setAttribute("tempo", retorno.ultima_leitura);
    // document.getElementById("tempo_restante").focus();  clearInterval(interval); interval = setInterval(function ()
    // { let tempo_segundos  = document.getElementById("tempo_restante").getAttribute("tempo"); let tempo_formatado =
    // (parseInt(tempo_segundos / 60) >= 10 ? parseInt(tempo_segundos / 60) : "0" + parseInt(tempo_segundos / 60)) +
    // ":" + (tempo_segundos % 60 < 10 ? "0" + tempo_segundos % 60 : tempo_segundos % 60);
    // document.getElementById("tempo_restante").setAttribute("tempo", tempo_segundos - 1);
    // document.getElementById("tempo_restante").innerHTML = tempo_formatado;  if (tempo_segundos <= 0) {
    // clearInterval(interval); modalMessage("Voc&ecirc; j&aacute; pode escanear um novo c&oacute;digo",
    // "alert-success", "N", "S", null, "spinner-grow"); gera_log("QR Code", "Leitura de novo código habilitada.");
    // document.getElementById("tempo_restante").closest(".row").classList.add("d-none");
    // document.getElementById("codigo_apresentacao").focus(); } }, 1000); } modalMessage(retorno.msg, "alert-danger",
    // "N", "S", null, "far fa-times-circle"); if (retorno.msg.includes("precisa visitar o primeiro estande")) {
    // setTimeout(() => { window.location.href = "trilha.php"; }, 2500); } }, error   : function () {
    // document.querySelector(".spinner").classList.add("d-none"); document.body.classList.remove("pe-none");
    // modalMessage("Erro ao escanear c&oacute;digo.", "alert-danger", "S", "S", null, "far fa-times-circle", 2500); }
    // };  $.ajax(settings);
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
