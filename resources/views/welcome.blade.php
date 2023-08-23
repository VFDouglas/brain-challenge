<?php
/**
 * @author Douglas Vicentini Ferreira
 * @since  28/06/2021
 * Página de confirmação de participação na convenção
 **/
$titulo_pagina = "Confirma&ccedil;&atilde;o de Participa&ccedil;&atilde;o - Conven&ccedil;&atilde;o de Vendas 2021";
require_once __DIR__ . '/header.php';
?>
<div class="container">
    <div class="row mb-1 mt-5 texto_azul">
        <div class="col-12 mt-md-3">
            <p class="text-center text-uppercase bg-white titulo3">
                <strong>
                    Ol&aacute;! Voc&ecirc; foi
                    <br class="d-md-none">selecionado para
                    <br class="d-md-none">participar da conven&ccedil;&atilde;o
                    <br class="d-md-none">de vendas <?= utf8_encode($_SESSION["local"]) . ' ' . date('Y'); ?>, nos dias
                    <?= date("d/m", strtotime($_SESSION["datainicio_convencao"])); ?> &agrave;
                    <?= date("d/m", strtotime($_SESSION["dataencerramento_convencao"])); ?>.
                </strong>
            </p>
        </div>
        <div class="col-12 mt-5">
            <p class="text-center text-uppercase bg-white titulo4 texto_cinza">
                <strong>
                    Lembre-se que<br class="d-md-none"> sua presen&ccedil;a &eacute; muito
                    <br class="d-md-none"> importante para n&oacute;s!
                </strong>
            </p>
        </div>
        <div class="col-12 mt-4">
            <p class="text-center text-uppercase bg-white titulo4 texto_cinza"><strong>Deseja participar?</strong></p>
        </div>
    </div>
    <div class="row">
        <div class="col-4 offset-2 col-sm-4 offset-sm-2 pr-0">
            <button type="button"
                    class="btn cor_botao_boas_vindas texto_cinza px-4 float-right" onclick="confirma_participacao('N')">
                <strong>N&Atilde;O</strong>
            </button>
        </div>
        <div class="col-4 col-sm-4 pl-0">
            <button type="button" class="btn texto_cinza cor_cabecalho px-4" onclick="confirma_participacao('S')">
                <strong>SIM</strong>
            </button>
        </div>
    </div>
</div>
<script>
    gera_log("Boas Vindas", "Carregou a página de Boas Vindas.");
    document.querySelectorAll(".menu_cabecalho").forEach(element => element.remove());
</script>
<?php
require_once __DIR__ . '/footer.php';
?>
