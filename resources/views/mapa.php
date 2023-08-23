<?php
	/**
	* @author Douglas Vicentini Ferreira
	* @since  26/07/2021
	* Página de detalhe da imagem da timeline
	**/
	$titulo_pagina = "Mapa do Evento";
	include_once "header.php";
	include_once "dados_rca.php";

	$sql_mapa = "
		SELECT m.idimagem,
			   m.descricao,
			   m.url,
			   UNIX_TIMESTAMP(m.datahoracadastro) datahoracadastro
		  FROM convencao.cnv_mapa m
		 WHERE m.codintroducao = ". $_SESSION["codintroducao"] ."
	";
	$resultado_sql_mapa = $conexao->TConsulta($sql_mapa);
	$linhas_sql_mapa	= $conexao->TLinhas($resultado_sql_mapa);

	$html_mapa = "";

	if ($linhas_sql_mapa > 0) {
		for ($i = 0; $i < $linhas_sql_mapa; $i++) {
			$reg_mapa = $conexao->TFetch($resultado_sql_mapa);
			$imagem = strstr($reg_mapa->url, "base64") ? $reg_mapa->url : $reg_mapa->url . "?v=" . $reg_mapa->datahoracadastro;
			$html_mapa .= "
				<div class='row mt-4'>
					<div class='col-12'>
						<figure>
							<figcaption class='text-center titulo4 texto_azul'><strong>". utf8_encode($reg_mapa->descricao) ."</strong></figcaption>
							<img src='$imagem' alt='". utf8_encode($reg_mapa->descricao) ."' class='w-100'>
						</figure>
					</div>
				</div>
			";
		}
	}
	else {
		$html_mapa = "
			<div class='row mt-4'>
				<div class='col-12'>
					<h6 class='text-center texto_azul'>Nenhum mapa encontrado para a conven&ccedil;&atilde;o</h6>
				</div>
			</div>
		";
	}
?>
<div class="container-fluid mt-4">
	<div class="row mr-3">
		<div class="col-6 offset-3 col-md-6 offset-md-5 text-center open-sans-bold vertical-center">
			<img src="img/icone_mapa_azul.png" width="50" height="44" class="mb-1"><span class="titulo3 texto_azul text-nowrap"><strong>MAPA DO EVENTO</strong></span>
		</div>
	</div>
	<?php echo $html_mapa; ?>
</div>
<script>
	gera_log("Mapa", "Carregou o mapa do evento.");
</script>
<?php
	include_once "footer_2022.php";
?>