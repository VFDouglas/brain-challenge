<?php
	/**
	* @author Douglas Vicentini Ferreira
	* @since  28/06/2021
	* Página inicial da convenção
	**/
	$titulo_pagina = "Home";
	include_once "header.php";
	include_once "dados_rca.php";
?>
<div class="container-fluid">
	<div class="row botoes_menu botoes_home mx-lg-5">
		<div class="col-6 col-sm-6 vertical-center col-md-3 col-lg">
			<a href="dados_viagem.php" class="botao-grande botao_convencao_home <?php if (!in_array("dados_viagem.php", $_SESSION["pagina_acesso_rca"])) echo "disabled"; ?>">
				<div class="text-center w-100">
					<img src="img/icone_viagem_home.png" width="30" height="30" class="mb-1"> <br><span class="texto_azul">Dados da Viagem</span>
				</div>
			</a>
		</div>
		<div class="col-6 col-sm-6 vertical-center col-md-3 col-lg">
			<a href="programacao.php" class="botao-grande botao_convencao_home <?php if (!in_array("programacao.php", $_SESSION["pagina_acesso_rca"])) echo "disabled"; ?>">
				<div class="text-center w-100">
					<img src="img/icone_programacao_home.png" width="30" height="30" class="mb-1"> <br><span class="texto_azul">Programa&ccedil;&atilde;o</span>
				</div>
			</a>
		</div>
		<div class="col-6 col-sm-6 vertical-center col-md-3 col-lg">
			<a href="mapa.php" class="botao-grande botao_convencao_home <?php if (!in_array("mapa.php", $_SESSION["pagina_acesso_rca"])) echo "disabled"; ?>">
				<div class="text-center w-100">
					<img src="img/icone_mapa_home.png" width="30" height="30" class="mb-1"> <br><span class="texto_azul">Mapa do Evento</span>
				</div>
			</a>
		</div>
		<div class="col-6 col-sm-6 vertical-center col-md-3 col-lg">
			<a href="trilha.php" class="botao-grande botao_convencao_home <?php if (!in_array("trilha.php", $_SESSION["pagina_acesso_rca"])) echo "disabled"; ?>">
				<div class="text-center w-100">
					<img src="img/icone_trilha_home.png" width="30" height="30" class="mb-1"> <br><span class="texto_azul">Trilha</span>
				</div>
			</a>
		</div>
	<script>
		if (window.screen.availWidth > 991) document.write("</div><div class='row mb-5 botoes_menu botoes_home mx-lg-5'>");
	</script>
		<div class="col-6 col-sm-6 vertical-center col-md-3 col-lg">
			<a href="estandes.php" class="botao-grande botao_convencao_home <?php if (!in_array("estandes.php", $_SESSION["pagina_acesso_rca"])) echo "disabled"; ?>">
				<div class="text-center w-100">
					<img src="img/icone_estande_home.png" width="30" height="30" class="mb-1">  <br><span class="texto_azul">Estandes</span>
				</div>
			</a>
		</div>
		<div class="col-6 col-sm-6 vertical-center col-md-3 col-lg">
			<a href="premios.php" class="botao-grande botao_convencao_home <?php if (!in_array("premios.php", $_SESSION["pagina_acesso_rca"])) echo "disabled"; ?>">
				<div class="text-center w-100">
					<img src="img/icone_premios_home.png" width="30" height="30" class="mb-1"> <br><span class="texto_azul">Pr&ecirc;mios</span>
				</div>
			</a>
		</div>
		<div class="col-6 col-sm-6 vertical-center col-md-3 col-lg">
			<a href="qrcode.php" class="botao-grande botao_convencao_home <?php if (!in_array("qrcode.php", $_SESSION["pagina_acesso_rca"])) echo "disabled"; ?>">
				<div class="text-center w-100">
					<img src="img/icone_quiz_home.png" width="30" height="30" class="mb-1"> <br><span class="texto_azul">QR Code / PERGUNTAS</span>
				</div>
			</a>
		</div>
		<div class="col-6 col-sm-6 vertical-center col-md-3 col-lg">
			<a href="quiz_extra.php" class="botao-grande botao_convencao_home <?php if (!in_array("qrcode.php", $_SESSION["pagina_acesso_rca"])) echo "disabled"; ?>">
				<div class="text-center w-100">
					<img src="img/icone_quiz_home.png" width="30" height="30" class="mb-1"> <br><span class="texto_azul">Perguntas Extras</span>
				</div>
			</a>
		</div>
	</div>
</div>
<script>
	gera_log("Home", "Carregou a página inicial.");
</script>
<?php
	include_once "footer.php";
?>