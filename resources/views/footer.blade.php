<div style="margin-top: 150px;"></div>
<footer>
    <div class="row fixed-bottom text-center px-2 bg-white pb-4">
        <hr class="w-100">
        <div class="col-3 col-lg-2 offset-lg-2">
            <a href="index.php">
                <img src="/img/icone_home_menu_rapido.png" class="mw-100">
            </a>
            <br>
            <span class="subtitulo3 texto_cinza">HOME</span>
        </div>
        <div class="col-3 col-lg-2">
            <a href="trilha.php">
                <img src="/img/icone_trilha_menu_rapido.png" class="mw-100">
            </a>
            <br>
            <span class="subtitulo3 texto_cinza">TRILHA</span>
        </div>
        <div class="col-3 col-lg-2">
            <a href="estandes.php">
                <img src="/img/icone_estande_menu_rapido.png" class="mw-100" alt="">
            </a>
            <br>
            <span class="subtitulo3 texto_cinza">ESTANDES</span>
        </div>
        <div class="col-3 col-lg-2">
            <a href="qrcode.php">
                <img src="/img/icone_qrcode_cinza.png" class="mw-100" width="40" height="40" alt="">
            </a><br>
            <span class="subtitulo3 texto_cinza">QR CODE</span>
        </div>
    </div>
</footer>
<script type="text/javascript">
    $(window).bind('ready scroll resize load', 'a', function () {
        if ($(window).scrollTop() > 20) {
            $("#logo").addClass("ajusta-logo");
            $("#cabecalho")
                .addClass("shadow")
                .addClass("ajusta-cabecalho");
        } else {
            $("#cabecalho")
                .removeClass("shadow")
                .removeClass("ajusta-cabecalho");
            $("#logo").removeClass("ajusta-logo");
        }
    });
</script>
<span class="backToTop" data-toggle="tooltip" title="Clique para voltar ao topo da p&aacute;gina">
    <i class="fas fa-arrow-alt-circle-up fa-2x" onclick="voltaTopo();"></i>
</span>
