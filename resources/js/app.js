import './bootstrap';
/**
 * @function bootstrap.Modal.getOrCreateInstance
 */

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


/**
 * Confirma participação na convenção
 * @author Douglas Vicentini Ferreira
 * @since 28/06/2021
 **/
function confirma_participacao(valor) {
    gera_log("Boas Vindas", "Clicou para confirmar participação.");
    modalMessage("Aguarde um momento...", "alert-warning", "N", "S", null, "spinner-grow");

    $.ajax({
        url     : "client/confirma_participacao.php",
        data    : {
            aceite: valor
        },
        dataType: "json",
        cache   : false,
        success : function (retorno) {
            if (retorno.msg == "sucesso") {
                gera_log("Boas Vindas", "Confirmou que " + (valor == "N" ? "não" : "") + "irá participar.");
                setTimeout(() => {
                    window.location.href = retorno.link;
                }, 1000);
            } else {
                modalMessage(retorno.msg, "alert-danger", "S", "S", null, null, 4500);
            }
        }
    });
}

const DATA = new Intl.DateTimeFormat("pt-BR", {
    year  : 'numeric',
    month : 'numeric',
    day   : 'numeric',
    hour  : 'numeric',
    minute: 'numeric',
    second: 'numeric',
    hour12: false
});

function busca_detalhe_ranking(codrepresentante) {
    document.querySelector(".spinner").classList.remove("d-none");
    document.body.classList.add("pe-none");

    gera_log("Geral", "Clicou para buscar os detalhes de pontuação.");

    $.ajax({
        url     : "./client/detalhe_ranking_rca.php",
        data    : {
            codrepresentante: codrepresentante
        },
        dataType: "json",
        cache   : false,
        success : function (retorno) {
            document.querySelector(".spinner").classList.add("d-none");
            document.body.classList.remove("pe-none");
            if (retorno.msg !== 'sucesso') {
                gera_log("Geral", retorno.log);
                modalMessage(retorno.msg, "alert-danger", "S", "S", null, "far fa-times-circle", 4000);
                return false;
            }
            gera_log("Geral", "Detalhes de pontuação carregados com sucesso.");

            let horas    = parseInt(retorno.temporesposta / 3600);
            let minutos  = parseInt(retorno.temporesposta % 3600 / 60);
            let segundos = parseInt(retorno.temporesposta % 60);

            if (horas < 10) {
                horas = "0" + horas;
            }
            if (minutos < 10) {
                minutos = "0" + minutos;
            }
            if (segundos < 10) {
                segundos = "0" + segundos;
            }
            let temporesposta = (horas > 0 ? horas + "&ordm; " : "") + (minutos > 0 ? minutos + "&lsquo; " : "") + segundos + "&ldquo;";

            $("#nomeparticipante").html(retorno.nomeparticipante);
            $("#codparticipante").html(retorno.codparticipante);
            $("#descequipe").html(retorno.descequipe);
            $("#img_detalhe_rca").attr("src", retorno.foto);

            $("#pontuacaototal").html(retorno.pontuacaototal);
            $("#temporesposta").html(temporesposta);
            $("#datahoratermoaceite").html(retorno.datahoratermoaceite);

            let html_pontuacao = "";
            for (let i = 0; i < retorno.itemavaliado.length; i++) {
                let cor_pontuacao = "";
                if (retorno.debitocredito[i] === "C" && retorno.pontuacao[i] > 0) {
                    cor_pontuacao = `<h5 class='text-success'>+ ${parseInt(retorno.pontuacao[i])}`;
                } else if (retorno.debitocredito[i] === "D" && retorno.pontuacao[i] > 0) {
                    cor_pontuacao = `<h5 class='text-danger'>- ${parseInt(retorno.pontuacao[i])}`;
                } else {
                    cor_pontuacao = `<h5>+ ${parseInt(retorno.pontuacao[i])}`;
                }
                html_pontuacao += `
					<div class='row'>
						<div class="col-8 vertical-center">${retorno.itemavaliado[i]}</div>
						<div class="col-4 vertical-center text-nowrap">${cor_pontuacao} pts</h5></div>
					</div><br>
				`;
            }

            $("#div_pontuacao_analitica").html(html_pontuacao);
            $("#modal_detalhe_ranking_rca").modal();
        },
        error   : function () {
            document.querySelector(".spinner").classList.add("d-none");
            document.body.classList.remove("pe-none");
            modalMessage("Dados n&atilde;o encontrados.", "alert-danger", "S", "S", null, "far fa-times-circle", 2000);
        }
    });
}

/** Busca as notificações do RCA **/
function busca_notificacao_rca(modal = "N") {
    if (location.href.includes("/admin")) {
        return false;
    }
    gera_log("Geral", "Clicou para buscar notificações.");
    document.getElementById("div_notificacoes_rca").innerHTML = "";

    document.querySelector(".spinner").classList.remove("d-none");
    document.body.classList.add("pe-none");

    let headers = new Headers();
    headers.append("pragma", "no-cache");
    headers.append("cache-control", "no-cache");

    let options = {
        method : "GET",
        headers: headers
    }

    fetch("client/busca_notificacao_rca.php", options).then(function (response) {
        if (!response.ok) {
            document.querySelector(".spinner").classList.add("d-none");
            document.body.classList.remove("pe-none");

            if (modal === "S") {
                gera_log("Geral", "Erro ao buscar notificações.");
                modalMessage("Dados n&atilde;o encontrados.", "alert-danger", "S", "S", null, "far fa-times-circle", 2000);
            }
            return false;
        }
        response.json().then(function (retorno) {
            document.querySelector(".spinner").classList.add("d-none");
            document.body.classList.remove("pe-none");
            if (retorno.msg === "sucesso") {
                if (modal === "S") gera_log("Geral", "Notificações carregadas com sucesso.");

                let html_msg_notificacao = `
					${retorno.qtdnaolida > 0 ? "VOC&Ecirc; TEM <span id='qtd_notificacao'>" + retorno.qtdnaolida.toString().
                    padStart(2, "0") + "</span>" + (retorno.qtdnaolida > 1 ? " NOVAS MENSAGENS" : " NOVA MENSAGEM") : "VOC&Ecirc; N&Atilde;O TEM NENHUMA NOVA MENSAGEM"}
				`;

                document.getElementById("msg_notificacao").innerHTML = html_msg_notificacao;

                if (retorno.qtdnaolida > 0) {
                    document.getElementById("div_msg_notificacao").
                        classList.
                        add("bg-amarelo-notificacao", "cor_texto_com_notificacao");
                    document.getElementById("div_msg_notificacao").
                        classList.
                        remove("bg-light", "cor_texto_sem_notificacao");

                    document.getElementById("icone_notificacao_home").classList.add("fa-bell");
                    document.getElementById("icone_notificacao_home").classList.remove("fa-bell-slash");
                } else {
                    document.getElementById("div_msg_notificacao").
                        classList.
                        remove("bg-amarelo-notificacao", "cor_texto_com_notificacao");
                    document.getElementById("div_msg_notificacao").
                        classList.
                        add("bg-light", "cor_texto_sem_notificacao");

                    document.getElementById("icone_notificacao_home").classList.remove("fa-bell");
                    document.getElementById("icone_notificacao_home").classList.add("fa-bell-slash");
                }

                document.getElementById("div_notificacoes_rca").innerHTML = retorno.html;
                if (modal == "S") $("#modal_notificacao_rca").modal();
            } else {
                if (modal == "S") {
                    gera_log("Geral", "Erro ao buscar notificações.");
                    modalMessage(retorno.msg, "alert-danger", "S", "S", null, "far fa-times-circle", 3000);
                }
            }
        })
    })
}

function leitura_notificacao(idnotificacao) {
    gera_log("Geral", "Script acionado para ler notificação.");
    if (document.getElementById("notificacao" + idnotificacao).getAttribute("lida") === "S") {
        return false;
    }

    let qtd_notificacao = document.getElementById("qtd_notificacao").innerHTML;

    let headers = new Headers();
    headers.append("pragma", "no-cache");
    headers.append("cache-control", "no-cache");

    let options = {
        method : "POST",
        headers: headers,
        body   : JSON.stringify({"idnotificacao": idnotificacao})
    }

    fetch("client/leitura_notificacao_rca.php", options).then(function (response) {
        if (response.status == 200) {
            response.json().then(function (retorno) {
                if (retorno.msg == "sucesso") {
                    gera_log("Geral", "Notificação " + idnotificacao + " marcada como lida.");
                    document.getElementById("notificacao" + idnotificacao).setAttribute("lida", "S");
                    document.getElementById("icone_notificacao" + idnotificacao).remove();
                    qtd_notificacao -= 1;

                    let html_msg_notificacao                             = `
						${qtd_notificacao > 0 ? "VOC&Ecirc; TEM <span id='qtd_notificacao'>" + qtd_notificacao.toString().
                        padStart(2, "0") + "</span>" + (qtd_notificacao > 1 ? " NOVAS MENSAGENS" : " NOVA MENSAGEM") : "VOC&Ecirc; N&Atilde;O TEM NENHUMA NOVA MENSAGEM"}
					`;
                    document.getElementById("msg_notificacao").innerHTML = html_msg_notificacao;

                    if (qtd_notificacao > 0) {
                        document.getElementById("div_msg_notificacao").
                            classList.
                            add("bg-amarelo-notificacao", "cor_texto_com_notificacao");
                        document.getElementById("div_msg_notificacao").
                            classList.
                            remove("bg-light", "cor_texto_sem_notificacao");

                        document.getElementById("icone_notificacao_home").classList.add("fa-bell");
                        document.getElementById("icone_notificacao_home").classList.remove("fa-bell-slash");
                    } else {
                        document.getElementById("div_msg_notificacao").
                            classList.
                            remove("bg-amarelo-notificacao", "cor_texto_com_notificacao");
                        document.getElementById("div_msg_notificacao").
                            classList.
                            add("bg-light", "cor_texto_sem_notificacao");

                        document.getElementById("icone_notificacao_home").classList.remove("fa-bell");
                        document.getElementById("icone_notificacao_home").classList.add("fa-bell-slash");
                    }
                } else {
                    gera_log("Geral", "Erro ao ler notificação.");
                }
            });
        } else {
            gera_log("Geral", "Erro ao ler notificação.");
        }
    })
}

/**
 * Salva o parãmetro da convenção
 * @param id
 */
function salva_parametro(id) {
    document.getElementById(`btn_salvar_${id}`).querySelector('i').classList.add('d-none');
    document.getElementById(`btn_salvar_${id}`).querySelector('span').classList.remove('d-none');

    let dados = {
        id           : id,
        valor        : document.getElementById(`valor_${id}`).value,
        descricao    : document.getElementById(id).querySelector('.descricao_parametro').textContent,
        faixa_inicial: document.getElementById(`faixa_inicial_${id}`).value,
        faixa_final  : document.getElementById(`faixa_final_${id}`).value
    };
    if (['checkbox', 'radio'].includes(document.getElementById(`valor_${id}`).type)) {
        dados.valor = document.getElementById(`valor_${id}`).checked ? 'A' : 'I';
    } else {
        dados.valor = document.getElementById(`valor_${id}`).value;
    }

    let headers = new Headers();
    headers.append('pragma', 'no-cache');
    headers.append('cache-control', 'no-cache');

    let options = {
        method : 'PUT',
        headers: headers,
        body   : JSON.stringify(dados)
    }
    fetch('/site/convencao/client/salva_parametro.php', options).then(function (response) {
        if (!response.ok) {
            modalMessage("Erro ao realizar requisi&ccedil;&atilde;o.", "alert-danger", "S", "S", '', "far fa-times-circle", 2000);
            document.getElementById(`btn_salvar_${id}`).querySelector('i').classList.remove('d-none');
            document.getElementById(`btn_salvar_${id}`).querySelector('span').classList.add('d-none');
            return false;
        }
        response.json().then(function (retorno) {
            document.getElementById(`btn_salvar_${id}`).querySelector('i').classList.remove('d-none');
            document.getElementById(`btn_salvar_${id}`).querySelector('span').classList.add('d-none');
            if (retorno) {
                if (retorno.msg !== 'sucesso') {
                    modalMessage(retorno.msg, 'alert-danger', 'S', 'S', '', 'far fa-times-circle', 2000);
                }
            } else {
                modalMessage("Erro no retorno dos dados.", "alert-danger", "S", "S", '', "far fa-times-circle", 2000);
            }
        });
    });
}

// Se a página rolar a mais de 350 pixels, aparece um botão no canto direito para voltar ao topo
$(document).scroll(function () {
    let y = $(this).scrollTop();
    if (y > 350) {
        $(".backToTop").fadeIn();
    } else {
        $(".backToTop").fadeOut();
    }
});


$(".btn_viagem").click(function () {
    let valor = $(this).attr("valor");

    if (valor == "ida") {
        gera_log("Dados de Viagem", "Clicou para ver dados da viagem de ida.");
        $(".div_viagem_ida").css("display", "flex");
        $(".div_viagem_volta").css("display", "none");
        document.getElementById("btn_ida").classList.add("cor_cabecalho");
        document.getElementById("btn_volta").classList.remove("cor_cabecalho");
    } else {
        gera_log("Dados de Viagem", "Clicou para ver dados da viagem de volta.");
        $(".div_viagem_volta").css("display", "flex");
        $(".div_viagem_ida").css("display", "none");
        document.getElementById("btn_ida").classList.remove("cor_cabecalho");
        document.getElementById("btn_volta").classList.add("cor_cabecalho");
    }
});

$("#form_pergunta_quiz").submit(function (e) {
    e.preventDefault();
    $("#btn_envia_resposta").prop("disabled", true);
    gera_log("Quiz", `Clicou para responder a pergunta '${document.getElementById("nome_pergunta").innerText}' do fornecedor '${document.getElementById("nome_apresentacao").innerText}'.`);

    let valor    = $(".radio_opcao_pergunta:checked").attr("correta");
    let codopcao = $(".radio_opcao_pergunta:checked").attr("codopcao");

    $.ajax({
        url     : "client/responde_pergunta.php",
        data    : {
            codopcao: codopcao
        },
        dataType: "json",
        cache   : false,
        success : function (retorno) {
            let data_atual = new Date();
            if (retorno.msg === "sucesso") {
                gera_log("Quiz", `Respondeu a pergunta '${document.getElementById("nome_pergunta").innerText}' do fornecedor '${document.getElementById("nome_apresentacao").innerText}' com sucesso.`);
                if (valor === "S") {
                    modalMessage("Resposta correta.", "alert-success", "N", "S", null, "far fa-check-circle", 2000);
                } else {
                    modalMessage("Resposta incorreta.", "alert-danger", "S", "S", null, "far fa-times-circle", 2000);
                }
                setTimeout(() => {
                    window.location.href = "quiz.php?v=" + data_atual.getSeconds();
                }, 1500);
            } else {
                gera_log("Quiz", `Erro ao responder a pergunta '${document.getElementById("nome_pergunta").innerText}' do fornecedor '${document.getElementById("nome_apresentacao").innerText}'.`);
                modalMessage(retorno.msg, "alert-danger", "S", "S", null, null, 2000);
                $("#btn_envia_resposta").prop("disabled", false);
            }
        },
        error   : function () {
            gera_log("Quiz", `Erro ao responder a pergunta '${document.getElementById("nome_pergunta").innerText}' do fornecedor '${document.getElementById("nome_apresentacao").innerText}'.`);
            $("#btn_envia_resposta").prop("disabled", false);
        }
    });
});
$("#form_busca_apresentacao").submit(function (e) {
    e.preventDefault();
    gera_log("QR Code", `Clicou para buscar a apresentação com o QR Code ${document.getElementById("codigo_apresentacao").
        value.
        toUpperCase()}.`);
    insere_visita_estande(document.getElementById("codigo_apresentacao").value.toUpperCase());
});
$("#btn_modo_ranking").click(function () {
    let tipo      = $(this).attr("tipo");
    let convencao = $("#select_convencao option:selected").val();

    window.location.href = tipo === "rca" ? "ranking.php?codintroducao=" + convencao : "ranking.php";
});
$("#select_convencao").change(function () {
    let tipo = $("#btn_modo_ranking").attr("tipo");
    if (tipo === "convencao") window.location.href = "ranking.php?codintroducao=" + $(this).val();
});

$(".btn_curte_foto").click(function () {
    let descricaopost = $(this).attr("id").split("btn_curtir_")[1].replace(/[\u2018\u2019]/g, "'").
        replace(/[\u201C\u201D]/g, '"').
        replace(/[\u2013\u2014]/g, '-').
        replace(/[\u2026]/g, '...');
    let id            = $(this).attr("id");

    gera_log("Timeline", `Clicou para curtir foto '${descricaopost}'.`);

    $.ajax({
        url     : "client/curtir_foto_timeline.php",
        data    : {
            tipo         : "curtida",
            descricaopost: descricaopost
        },
        dataType: "json",
        cache   : false,
        success : function (retorno) {
            if (retorno.msg === "sucesso") {
                gera_log("Timeline", `Curtida na foto '${descricaopost}' pontuada com sucesso.`);
                document.getElementById(id).innerHTML = "<i class='fas fa-heart fa-2x icone_timeline'></i>";
                document.getElementById(id).disabled  = true;
            } else {
                gera_log("Timeline", `Erro ao pontuar a curtida na foto '${descricaopost}'.`);
                modalMessage(retorno.msg, "alert-danger", "S", "S", null, "far fa-times-circle", 2000);
            }
        },
        error   : function () {
            gera_log("Timeline", `Erro ao curtir a foto '${descricaopost}'.`);
            $(this).prop("disabled", false);
        }
    });
});
$(".btn_compartilha_foto").click(function () {
    $(this).prop("disabled", true);

    let link          = $(this).attr("link");
    let descricaopost = $(this).attr("id").split("btn_compartilhar_")[1];

    gera_log("Timeline", `Clicou para compartilhar a foto '${descricaopost}'.`);

    $("#link_oculto").attr("href", link);
    $.ajax({
        url     : "client/curtir_foto_timeline.php",
        data    : {
            tipo         : "compartilhamento",
            descricaopost: descricaopost
        },
        dataType: "json",
        cache   : false,
        success : function (retorno) {
            $(this).prop("disabled", false);
            if (retorno.msg == "sucesso") {
                gera_log("Timeline", `Compartilhamento da foto '${descricaopost}' pontuado com sucesso.`);
            } else {
                gera_log("Timeline", `Erro ao pontuar compartilhamento da foto '${descricaopost}'.`);
            }
        },
        error   : function () {
            $(this).prop("disabled", false);
        }
    });
});

$(".botao_convencao").click(function () {
    let id = $(this).attr("id");
    $(".botao_convencao").css("background-color", "#e5edf5").css("color", "#6C757D");

    $("#" + id).css("background-color", "#6C757D");
    $("#" + id).css("color", "white");
    $("#" + id).css("box-shadow", "none");
});

Array.from(document.getElementsByClassName("btn_confirmacao_fornecedor")).forEach(element => {
    element.addEventListener("click", function () {
        this.closest(".div_convencao").querySelectorAll(".btn_confirmacao_fornecedor").forEach(element => {
            element.classList.remove("fundo_verde");
        });
        this.classList.add("fundo_verde");
    });
});

if (document.getElementById("whatsapp")) {
    document.getElementById("whatsapp").addEventListener("keyup", function (e) {
        let codigo = this.keyCode || this.which;
        if (codigo != 8) {
            let x          = e.target.value.replace(/\D/g, "").match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : "(" + x[1] + ")" + x[2] + (x[3] ? "-" + x[3] : "");
        }
    })
}
if (document.getElementsByClassName("cpf_participante")) {
    Array.from(document.getElementsByClassName("cpf_participante")).forEach(element => {
        element.addEventListener("keyup", function (e) {
            let codigo = this.keyCode || this.which;
            if (codigo != 8) {
                let x          = e.target.value.replace(/\D/g, "").match(/(\d{0,3})(\d{0,3})(\d{0,3})(\d{0,2})/);
                e.target.value = !x[2] ? x[1] : x[1] + "." + x[2] + (x[3] ? "." + x[3] : "") + (x[4] ? "-" + x[4] : "");
            }
        })
    });
}

if (document.getElementById("form_dados_fornecedor")) {
    document.getElementById("form_dados_fornecedor").addEventListener("submit", function (event) {
        event.preventDefault();

        modalMessage("Aguarde um momento...", "alert-warning", "N", "S", null, "spinner-grow");

        let dados = JSON.stringify({
            "nomeresponsavel": document.getElementById("nomeresponsavel").value,
            "fornecedor"     : document.getElementById("fornecedor").value,
            "email"          : document.getElementById("email").value,
            "whatsapp"       : document.getElementById("whatsapp").value.replace(/\D/g, ""),
            "observacao"     : document.getElementById("observacao").value,
            "confirmado"     : document.querySelector('.btn_confirmacao_fornecedor.fundo_verde').
                getAttribute("confirmacao"),
            "codintroducao"  : document.querySelector('.btn_confirmacao_fornecedor.fundo_verde').
                getAttribute("codintroducao") // "dia_participacao":
                                              // document.querySelector('.btn_confirmacao_fornecedor').getAttribute("dia"),
        })

        let headers = new Headers();
        headers.append("pragma", "no-cache");
        headers.append("cache-control", "no-cache");

        let options = {
            method : "POST",
            headers: headers,
            body   : dados
        }

        fetch("../client/salva_dados_fornecedor.php", options).then(function (response) {
            if (+response.status !== 200) {
                modalMessage("Erro ao salvar os dados.", "alert-danger", "S", "S", null, "far fa-times-circle", 2000);
                return false;
            }
            response.json().then(function (retorno) {
                if (retorno.msg === "sucesso") {
                    modalMessage("Dados salvos com sucesso.", "alert-success", "N", "S", null, "far fa-check-circle", 2000);
                    setTimeout(() => {
                        window.location.href = "fornecedor_resp_receb.php";
                    }, 1500);
                } else {
                    modalMessage(retorno.msg, "alert-danger", "S", "S", null, "far fa-times-circle", 5000);
                }
            })
        })
    });
}

if (document.getElementById("form_participante_fornecedor")) {
    document.getElementById("form_participante_fornecedor").addEventListener("submit", function (event) {
        event.preventDefault();

        document.getElementById("btn_enviar_formulario").setAttribute("disabled", "disabled");
        let dados = [];
        Array.from(document.getElementsByClassName("div_linha_participante")).forEach(element => {
            let codintroducao = element.querySelector(".codintroducao_participante").value;
            let nome          = element.querySelector(".nome_participante").value;
            let cpf           = element.querySelector(".cpf_participante").value.replace(/\D/g, "");
            let dia1almoco    = element.querySelector(".dia1almoco") ? (element.querySelector(".dia1almoco").checked ? "S" : "N") : null;
            let dia1jantar    = element.querySelector(".dia1jantar") ? (element.querySelector(".dia1jantar").checked ? "S" : "N") : null;
            let dia1palestra  = element.querySelector(".dia1palestra") ? (element.querySelector(".dia1palestra").checked ? "S" : "N") : null;
            let dia2almoco    = element.querySelector(".dia2almoco") ? (element.querySelector(".dia2almoco").checked ? "S" : "N") : null;
            let dia2jantar    = element.querySelector(".dia2jantar") ? (element.querySelector(".dia2jantar").checked ? "S" : "N") : null;
            let dia2palestra  = element.querySelector(".dia2palestra") ? (element.querySelector(".dia2palestra").checked ? "S" : "N") : null;
            let dia3almoco    = element.querySelector(".dia3almoco") ? (element.querySelector(".dia3almoco").checked ? "S" : "N") : null;
            let dia3jantar    = element.querySelector(".dia3jantar") ? (element.querySelector(".dia3jantar").checked ? "S" : "N") : null;
            let dia3palestra  = element.querySelector(".dia3palestra") ? (element.querySelector(".dia3palestra").checked ? "S" : "N") : null;

            if (nome) {
                dados.push({
                    "codintroducao": codintroducao,
                    "nome"         : nome,
                    "cpf"          : cpf,
                    "dia1almoco"   : dia1almoco,
                    "dia1jantar"   : dia1jantar,
                    "dia1palestra" : dia1palestra,
                    "dia2almoco"   : dia2almoco,
                    "dia2jantar"   : dia2jantar,
                    "dia2palestra" : dia2palestra,
                    "dia3almoco"   : dia3almoco,
                    "dia3jantar"   : dia3jantar,
                    "dia3palestra" : dia3palestra
                });
            }
        });

        let headers = new Headers();
        headers.append("pragma", "no-cache");
        headers.append("cache-control", "no-cache");

        let options = {
            method : "POST",
            headers: headers,
            body   : JSON.stringify(dados)
        }

        fetch("../client/salva_participante_fornecedor.php", options).then(function (response) {
            if (response.status != 200) {
                modalMessage("Erro ao salvar os dados.", "alert-danger", "S", "S", null, "far fa-times-circle", 2000);
                document.getElementById("btn_enviar_formulario").removeAttribute("disabled");
                return false;
            }
            response.json().then(function (retorno) {
                document.getElementById("btn_enviar_formulario").removeAttribute("disabled");
                if (retorno.erros.length > 0) {
                    modalMessage(retorno.erros.join("<br>"), "alert-danger", "N", "S", null, "far fa-times-circle");
                } else {
                    modalMessage("Dados salvos com sucesso.", "alert-success", "N", "S", null, "far fa-check-circle", 2000);
                    setTimeout(() => {
                        window.location.href = "fornecedor_resp_receb.php";
                    }, 1000);
                }
            })
        })
    });
}

if (document.getElementById("checkbox_geral")) {
    document.getElementById("checkbox_geral").addEventListener("click", function () {
        let tabela = this.closest("table");

        tabela.querySelectorAll(".checkbox_geral").forEach(element => {
            element.checked = this.checked ? true : false;
        })
    });
}

if (document.getElementById("form_busca_apresentacao_participante")) {
    document.getElementById("form_busca_apresentacao_participante").addEventListener("submit", function (event) {
        event.preventDefault();

        let busca        = document.getElementById("input_busca").value.toUpperCase();
        let linha_tabela = document.querySelectorAll(".tabela_lista_dados tbody tr");

        linha_tabela.forEach(element => {
            element.textContent.toUpperCase().
                includes(busca) ? element.classList.remove("d-none") : element.classList.add("d-none");
        })
    })
}

if (document.getElementById("btn_associacao")) {
    document.getElementById("btn_associacao").addEventListener("click", function () {
        let tipo         = this.getAttribute("tipo");
        let linha_tabela = document.querySelectorAll(".tabela_lista_dados tbody tr");

        let dados         = {"tipo": tipo};
        let json          = [];
        let count         = 0;
        let count_inicial = 0;

        linha_tabela.forEach(element => {
            if (element.querySelector(".checkbox_geral").checked) {
                count++;
                if (tipo == "apresentacao") {
                    json.push({
                        "codapresentacao": element.querySelector(".codigo").innerText,
                        "inicial"        : element.querySelector(".radio_apresentacao").checked ? "S" : "N"
                    });
                    if (element.querySelector(".radio_apresentacao").checked) {
                        count_inicial++;
                    }
                } else if (tipo == "participante") {
                    json.push({
                        "codparticipante": element.querySelector(".codigo").innerText
                    });
                }
            }
        })

        if (count == 0) {
            dados.excluir = "S";
        }
        if (count_inicial == 0 && tipo == "apresentacao" && count > 0) {
            modalMessage("Informe a apresenta&ccedil;&atilde;o inicial.", "alert-danger", "S", "S", null, "far fa-times-circle", 2500);
            return false;
        }
        dados.json = json;

        let headers = new Headers();
        headers.append("pragma", "no-cache");
        headers.append("cache-control", "no-cache");

        let options = {
            method : "POST",
            headers: headers,
            body   : JSON.stringify(dados)
        }

        fetch("../client/insere_planilha.php", options).then(function (response) {
            if (!response.ok) {
                document.querySelector(".spinner").classList.add("d-none");
                document.getElementById("input_arquivo").value = "";
                modalMessage("Erro ao realizar requisi&ccedil;&atilde;o.", "alert-danger", "S", "S", null, "far fa-times-circle", 2500);
                return false;
            }
            response.json().then(function (retorno) {
                document.getElementById("input_arquivo").value = "";
                document.querySelector(".spinner").classList.add("d-none");
                if (retorno.erros.length > 0) {
                    modalMessage(retorno.erros.join("<br>"), "alert-danger", "N", "S", null, "far fa-times-circle");
                } else {
                    modalMessage("Dados associados com sucesso.", "alert-success", "S", "S", null, "far fa-check-circle", 2000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
        })
    })
}

if (document.getElementById("btn_associacao_pagina")) {
    document.getElementById("btn_associacao_pagina").addEventListener("click", function () {
        let linha_tabela = document.querySelectorAll(".tabela_lista_dados tbody tr");

        let dados = {};
        let json  = [];
        let count = 0;

        linha_tabela.forEach(element => {
            if (element.querySelector(".checkbox_geral").checked) {
                count++;
                json.push({
                    "codparticipante": element.querySelector(".codigo").innerText
                });
            }
        })
        if (count == 0) {
            dados.excluir = "S";
        }
        dados.json = json;

        let headers = new Headers();
        headers.append("pragma", "no-cache");
        headers.append("cache-control", "no-cache");

        let options = {
            method : "POST",
            headers: headers,
            body   : JSON.stringify(dados)
        }

        fetch("../client/associa_pagina_participante.php", options).then(function (response) {
            if (!response.ok) {
                document.querySelector(".spinner").classList.add("d-none");
                modalMessage("Erro ao realizar requisi&ccedil;&atilde;o.", "alert-danger", "S", "S", null, "far fa-times-circle", 2500);
                return false;
            }
            response.json().then(function (retorno) {
                document.querySelector(".spinner").classList.add("d-none");
                if (retorno.erros.length > 0) {
                    modalMessage(retorno.erros.join("<br>"), "alert-danger", "N", "S", null, "far fa-times-circle");
                } else {
                    modalMessage("Dados associados com sucesso.", "alert-success", "S", "S", null, "far fa-check-circle", 2000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
        })
    })
}

// Carrega as informações da trilha no modal ou cria uma nova
if (document.getElementsByClassName("btn_modal_trilha")) {
    Array.from(document.getElementsByClassName("btn_modal_trilha")).forEach(element => {
        element.addEventListener("click", function () {
            document.getElementById("form_nova_trilha").reset();

            let codintroducao = document.getElementById("select_convencao").value;
            let codtrilha     = this.getAttribute("codtrilha");

            if (codtrilha) {
                fetch("../client/busca_detalhe_trilha.php?codtrilha=" + codtrilha + "&codintroducao=" + codintroducao).
                    then(function (response) {
                        if (!response.ok) {
                            return false;
                        }
                        response.json().then(function (retorno) {
                            if (retorno) {
                                if (retorno.status == 200) {
                                    document.getElementById("codtrilha_modal").value  = retorno.dados.codtrilha;
                                    document.getElementById("descricaotrilha").value  = retorno.dados.descricao;
                                    document.getElementById("datainiciotrilha").value = retorno.dados.datainicio.replace(" ", "T");
                                    document.getElementById("datafimtrilha").value    = retorno.dados.datafim.replace(" ", "T");
                                } else {
                                    $(".modal").modal("hide");
                                    setTimeout(() => {
                                        modalMessage("Erro ao buscar os dados da trilha.", "alert-danger", "S", "S", null, null, 2000);
                                    }, 500);
                                }
                            }
                        })
                    })
            }
            $("#modal_nova_trilha").modal();
        });
    })
}

// Carrega as informações das páginas
if (document.getElementsByClassName("btn_modal_pagina")) {
    Array.from(document.getElementsByClassName("btn_modal_pagina")).forEach(element => {
        element.addEventListener("click", function () {
            document.getElementById("form_pagina_acesso").reset();

            let codintroducao = document.getElementById("select_convencao").value;
            let idpagina      = this.getAttribute("idpagina");

            if (idpagina) {
                fetch("../client/busca_detalhe_pagina.php?idpagina=" + idpagina + "&codintroducao=" + codintroducao).
                    then(function (response) {
                        if (!response.ok) {
                            return false;
                        }
                        response.json().then(function (retorno) {
                            if (retorno) {
                                if (retorno.status == 200) {
                                    document.getElementById("idpagina_modal").value  = retorno.dados.idpagina;
                                    document.getElementById("nomepagina").value      = retorno.dados.nome;
                                    document.getElementById("status_pagina").checked = retorno.dados.status == "A" ? true : false;
                                } else {
                                    $(".modal").modal("hide");
                                    setTimeout(() => {
                                        modalMessage("Erro ao buscar os dados da p&aacute;gina.", "alert-danger", "S", "S", null, null, 2000);
                                    }, 500);
                                }
                            }
                        })
                    })
            }
            $("#modal_pagina").modal();
        });
    })
}

// Exclui uma trilha
if (document.getElementsByClassName("btn_exclui_trilha")) {
    Array.from(document.getElementsByClassName("btn_exclui_trilha")).forEach(element => {
        element.addEventListener("click", function () {
            let dados = {
                "codintroducao": document.getElementById("select_convencao").value,
                "codtrilha"    : this.getAttribute("codtrilha")
            }

            let headers = new Headers();
            headers.append("pragma", "no-cache");
            headers.append("cache-control", "no-cache");

            let options = {
                method : "DELETE",
                headers: headers,
                body   : JSON.stringify(dados)
            }

            fetch("../client/insere_atualiza_trilha.php", options).then(function (response) {
                if (!response.ok) {
                    modalMessage("Erro ao excluir a trilha.", "alert-danger", "S", "S", null, null, 2000);
                }
                response.json().then(function (retorno) {
                    if (retorno.erros.length > 0) {
                        modalMessage(retorno.erros.join("<br>"), "alert-danger", "S", "S", null, null, 2000);
                    } else {
                        modalMessage("Trilha exclu&iacute;da com sucesso.", "alert-success", "S", "S", null, "far fa-check-circle", 2000);
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                })
            })
        });
    })
}

if (document.getElementById("form_nova_trilha")) {
    document.getElementById("form_nova_trilha").addEventListener("submit", function (event) {
        event.preventDefault();

        let dados = {
            "codintroducao": document.getElementById("select_convencao").value,
            "codtrilha"    : document.getElementById("codtrilha_modal").value,
            "descricao"    : document.getElementById("descricaotrilha").value,
            "datainicio"   : document.getElementById("datainiciotrilha").value,
            "datafim"      : document.getElementById("datafimtrilha").value
        }

        let headers = new Headers();
        headers.append("pragma", "no-cache");
        headers.append("cache-control", "no-cache");

        let options = {
            method : "POST",
            headers: headers,
            body   : JSON.stringify(dados)
        }

        fetch("../client/insere_atualiza_trilha.php", options).then(function (response) {
            if (!response.ok) {
                modalMessage("Erro ao inserir/atualizar a trilha.", "alert-danger", "S", "S", null, null, 2000);
            }
            response.json().then(function (retorno) {
                let comando;
                comando = dados.codtrilha ? "atualizada" : "inserida";

                $(".modal").modal("hide");
                if (retorno.erros.length > 0) {
                    modalMessage(retorno.erros.join("<br>"), "alert-danger", "S", "S", null, null, 2000);
                } else {
                    modalMessage("Trilha " + comando + " com sucesso.", "alert-success", "S", "S", null, "far fa-check-circle", 2000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
        })
    });
}

if (document.getElementById("form_pagina_acesso")) {
    document.getElementById("form_pagina_acesso").addEventListener("submit", function (event) {
        event.preventDefault();

        let dados = {
            "idpagina": document.getElementById("idpagina_modal").value,
            "nome"    : document.getElementById("nomepagina").value,
            "status"  : document.getElementById("status_pagina").checked ? "A" : "I"
        }

        let headers = new Headers();
        headers.append("pragma", "no-cache");
        headers.append("cache-control", "no-cache");

        let options = {
            method : "POST",
            headers: headers,
            body   : JSON.stringify(dados)
        }

        fetch("../client/insere_atualiza_pagina.php", options).then(function (response) {
            if (!response.ok) {
                modalMessage("Erro ao atualizar a p&aacute;gina.", "alert-danger", "S", "S", null, null, 2000);
            }
            response.json().then(function (retorno) {
                let comando;
                comando = dados.idpagina ? "atualizada" : "inserida";

                $(".modal").modal("hide");
                if (retorno.erros.length > 0) {
                    modalMessage(retorno.erros.join("<br>"), "alert-danger", "S", "S", null, null, 2000);
                } else {
                    modalMessage("P&aacute;gina " + comando + " com sucesso.", "alert-success", "S", "S", null, "far fa-check-circle", 2000);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
        })
    });
}

if (document.getElementById("btn_confirma_termo_fornecedor")) {
    document.getElementById("btn_confirma_termo_fornecedor").addEventListener("click", function () {
        document.querySelector(".spinner").classList.remove("d-none");
        document.getElementById("btn_confirma_termo_fornecedor").disabled = true;

        let headers = new Headers();
        headers.append("pragma", "no-cache");
        headers.append("cache-control", "no-cache");

        let options = {
            method : "POST",
            headers: headers
        }

        fetch("../client/confirma_aceite_termo_fornecedor.php", options).then(function (response) {
            if (!response.ok) {
                document.querySelector(".spinner").classList.add("d-none");
                document.getElementById("btn_confirma_termo_fornecedor").disabled = false;
                modalMessage("Erro ao confirmar termo.", "alert-danger", "S", "S", null, null, 2000);
            }
            response.json().then(function (retorno) {
                document.querySelector(".spinner").classList.add("d-none");
                document.getElementById("btn_confirma_termo_fornecedor").disabled = false;

                if (retorno.msg == "sucesso") {
                    modalMessage("Termo confirmado com sucesso.", "alert-success", "S", "S", null, "far fa-check-circle", 2000);
                    setTimeout(() => {
                        window.location.href = retorno.link;
                    }, 2000);
                } else {
                    modalMessage(retorno.msg, "alert-danger", "S", "S", null, null, 2500);
                }
            })
        })
    });
}

if (document.getElementById("form_busca_trilha")) {
    document.getElementById("form_busca_trilha").addEventListener("submit", function (event) {
        event.preventDefault();

        let busca  = document.getElementById("input_busca_trilha").value;
        let linhas = Array.from(document.getElementsByClassName("div_linha_trilha"));

        linhas.forEach(element => {
            if (element.querySelector(".nome_apresentacao").textContent.toUpperCase().includes(busca.toUpperCase())) {
                element.classList.remove("d-none");
            } else {
                element.classList.add("d-none");
            }
        });
    });
}

if (document.getElementById("form_pergunta_extra")) {
    document.getElementById("form_pergunta_extra").addEventListener("submit", function (event) {
        event.preventDefault();

        document.querySelector(".spinner").classList.remove("d-none");
        document.body.classList.add("pe-none");

        let dados = {
            valor   : document.querySelector(".radio_opcao_pergunta:checked").getAttribute("correta"),
            codopcao: document.querySelector(".radio_opcao_pergunta:checked").getAttribute("codopcao")
        }

        gera_log("Quiz", `Clicou para responder a pergunta extra '${document.getElementById("nome_pergunta").innerText}'.`);

        let headers = new Headers();
        headers.append("pragma", "no-cache");
        headers.append("cache-control", "no-cache");

        let options = {
            method : "POST",
            headers: headers,
            body   : JSON.stringify(dados)
        }

        fetch("./client/responde_pergunta_extra.php", options).then(function (response) {
            if (!response.ok) {
                gera_log("Quiz", `Erro ao responder a pergunta extra '${document.getElementById("nome_pergunta").innerText}'.`);
                document.querySelector(".spinner").classList.add("d-none");
                document.body.classList.remove("pe-none");
                modalMessage("Erro ao responder a pergunta extra.", "alert-danger", "S", "S", null, "far fa-times-circle", 2000);
            }
            response.json().then(function (retorno) {
                document.querySelector(".spinner").classList.add("d-none");
                document.body.classList.remove("pe-none");

                let data_atual = new Date();
                if (retorno.msg == "sucesso") {
                    gera_log("Quiz", `Respondeu a pergunta extra '${document.getElementById("nome_pergunta").innerText}'.`);
                    if (dados.valor == "S") {
                        modalMessage("Resposta correta.", "alert-success", "N", "S", null, "far fa-check-circle", 2000);
                    } else {
                        modalMessage("Resposta incorreta.", "alert-danger", "S", "S", null, "far fa-times-circle", 2000);
                    }
                    setTimeout(() => {
                        window.location.href = "quiz_extra.php?v=" + data_atual.getMilliseconds();
                    }, 1500);
                } else {
                    gera_log("Quiz", `Erro ao responder a pergunta extra '${document.getElementById("nome_pergunta").innerText}'.`);
                    modalMessage(retorno.msg, "alert-danger", "S", "S", null, null, 2000);
                }
            });
        });
    });
}


document.addEventListener("click", function (event) {
    // Se o elemento clicado da página não é filho do menu hamburguer, deve fechá-lo caso o menu esteja aberto
    if (document.getElementById("menu_dropdown") && !event.target.closest("#cabecalho") && document.getElementById("menu_dropdown").
        classList.
        contains("show")) {
        document.querySelector("#btn_hamburguer i").click();
    }
});
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

if (document.getElementById("input_arquivo")) document.getElementById("input_arquivo").
    addEventListener("change", gera_json, false);

function gera_json(event) {
    let arquivos  = event.target.files; // Objeto de arquivos
    let conversao = new ExcelToJSON();

    Array.from(arquivos).forEach(element => { // Cria um array a partir de um objeto e o percorre
        conversao.parseExcel(element);
    });
}

function upload_arquivo(json) {
    let tipo = document.getElementById("input_arquivo").getAttribute("tipo");
    document.querySelector(".spinner").classList.remove("d-none");

    let dados  = {};
    dados.tipo = tipo;
    dados.json = JSON.parse(json);

    let headers = new Headers();
    headers.append("pragma", "no-cache");
    headers.append("cache-control", "no-cache");

    let options = {
        method : "POST",
        headers: headers,
        body   : JSON.stringify(dados)
    }

    fetch("../client/insere_planilha.php", options).then(function (response) {
        if (!response.ok) {
            document.querySelector(".spinner").classList.add("d-none");
            document.getElementById("input_arquivo").value = "";
            modalMessage("Erro ao realizar requisi&ccedil;&atilde;o.", "alert-danger", "S", "S", null, "far fa-times-circle", 2500);
            return false;
        }
        response.json().then(function (retorno) {
            document.getElementById("input_arquivo").value = "";
            document.querySelector(".spinner").classList.add("d-none");
            if (retorno.erros.length > 0) {
                modalMessage(retorno.erros.join("<br>"), "alert-danger", "N", "S", null, "far fa-times-circle");
            } else {
                modalMessage("Planilha inserida com sucesso.", "alert-success", "S", "S", null, "far fa-check-circle", 2000);
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        })
    })
}

// Direcionando para a página de leitura de QR Code, caso o RCA clique em um card de fornecedor
Array.from(document.getElementsByClassName('div_presentation_name')).forEach(element => {
    element.addEventListener('click', () => {
        window.location.href = '/qrcode';
    });
});

// Verifica a quantidade de caracteres da descrição do parâmetro
Array.from(document.getElementsByClassName('descricao_parametro')).forEach(element => {
    element.addEventListener('keydown', function (event) {
        if (element.textContent.length > 100) {
            event.preventDefault();
        }
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
