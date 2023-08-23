<?php
	/**
	* @author Douglas Vicentini Ferreira
	* @since  28/06/2021
	* Página de dadps da viagem do RCA
	**/
	$titulo_pagina = "Dados da Viagem";
	include_once "header.php";
	include_once "dados_rca.php";

	setlocale(LC_ALL, "pt_BR.utf8");

	$sql_viagem = "
		SELECT a.tipo,
			   a.codigovoo,
			   a.companhiaaerea,
			   a.identificador,
			   a.dataembarque,
			   a.siglaaeroportosaida,
			   a.aeroportosaida,
			   a.siglaaeroportochegada,
			   a.aeroportochegada,
			   a.horariosaida,
			   a.horariochegada,
			   a.datahoravanretorno
		  FROM convencao.cnv_viagem a,
			   convencao.cnv_participante b
		 WHERE a.codparticipante = b.codparticipante
		   AND a.codintroducao 	 = b.codintroducao
		   AND a.codparticipante = ". $_SESSION["rca_convencao"] ."
		   AND a.codintroducao	 = ". $_SESSION["codintroducao"] ."
		 ORDER BY a.tipo, a.dataembarque, a.horariosaida
	";
	$resultado_sql_viagem = $conexao->TConsulta($sql_viagem);
	$qtd_viagem = $conexao->TLinhas($resultado_sql_viagem);

	$qtd_tipo_viagem = array();

	$html_viagem = "";
	if ($qtd_viagem > 0) {
		for ($i = 0; $i < $qtd_viagem; $i++) {
			$reg_viagem = $conexao->TFetch($resultado_sql_viagem);

			isset($qtd_tipo_viagem[$reg_viagem->tipo]) ? $qtd_tipo_viagem[$reg_viagem->tipo]++ : $qtd_tipo_viagem[$reg_viagem->tipo] = 1;

			if ($reg_viagem->codigovoo != "") {
				$voo = explode(" ", $reg_viagem->codigovoo);
				$descricaovoo = (!empty($reg_viagem->companhiaaerea) ? $reg_viagem->companhiaaerea . "<br>" : null) . "VOO " . $reg_viagem->codigovoo . (!empty($reg_viagem->identificador) ? "<br>Loc. " . $reg_viagem->identificador : null);
			}
			else {
				$descricaovoo = "VAN";
			}

			$dt = new DateTime($reg_viagem->dataembarque);
			$formatter = new IntlDateFormatter("pt_BR", IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
			$formatter->setPattern("E");
			$dia_viagem_formatado = $formatter->format($dt);

			$data_viagem = $qtd_tipo_viagem[$reg_viagem->tipo] <= 1 ? ucwords($dia_viagem_formatado) . ", " . date('d/m/Y', strtotime($reg_viagem->dataembarque)) : "Conex&atilde;o";

			$html_viagem .= '
				<div class="row div_viagem_'. strtolower($reg_viagem->tipo) .' mt-2">
					<div class="col-12 text-center">
						<h2 class="texto_titulo1 text-uppercase"><strong>'. $data_viagem .'</strong></h2>
					</div>
				</div>
				<hr class="div_viagem_'. strtolower($reg_viagem->tipo) .'">
				<div class="row div_viagem_'. strtolower($reg_viagem->tipo) .'" id="resumo_viagem">
					<div class="col-4 text-center">
						<h2 class="texto_titulo2 texto_azul">
							<span class="fonte_sigla_viagem"><strong>'. $reg_viagem->siglaaeroportosaida .'</strong></span><br>
							<span class="subtitulo1 text-uppercase"><strong>'. utf8_encode($reg_viagem->aeroportosaida) .'</strong></span>
						</h2>
					</div>
					<div class="col-4 text-center">
						<img src="img/icone_'. ($reg_viagem->codigovoo != "" ? 'aviao_novo' : 'van') .'.png" class="'. (strtoupper($reg_viagem->tipo) == "VOLTA" ? "rotacao-horizontal" : "") .'" width="80" height="63" class="mb-1">
					</div>
					<div class="col-4 text-center">
						<h2 class="texto_titulo2 texto_azul">
							<span class="fonte_sigla_viagem"><strong>'. $reg_viagem->siglaaeroportochegada .'</strong></span><br>
							<span class="subtitulo1 text-uppercase"><strong>'. utf8_encode($reg_viagem->aeroportochegada) .'</strong></span>
						</h2>
					</div>
				</div>
				<div class="row div_viagem_'. strtolower($reg_viagem->tipo ).'">
					<div class="col-4 text-center">
						<h6 class="texto_horario_viagem">Hor&aacute;rio: <br> '. date("H:i", strtotime($reg_viagem->horariosaida)) .'</h6>
					</div>
					<div class="col-4 text-center mt-3">
						<h2 class="texto_azul titulo_voo"><strong>VOO</strong></h2><h2 class="texto_azul titulo_voo"><strong>'. $reg_viagem->codigovoo .'</strong></h2>
					</div>
					<div class="col-4 text-center">
						<h6 class="texto_horario_viagem">Hor&aacute;rio: <br> '. date("H:i", strtotime($reg_viagem->horariochegada)) .'</h6>
					</div>
				</div>
				<div class="row div_viagem_'. strtolower($reg_viagem->tipo ).'">
					<div class="col-12 text-center titulo4 cor_dados_viagem">
						Companhia: <span class="text-uppercase">'. $reg_viagem->companhiaaerea .'</span><br>
						Identificador: <span>'. $reg_viagem->identificador .'</span>
					</div>
				</div>
			';
			if (!empty($reg_viagem->datahoravanretorno)) {
				$html_viagem .= "
					<hr class='row div_viagem_". strtolower($reg_viagem->tipo )."'>
					<div class='row div_viagem_". strtolower($reg_viagem->tipo )."'>
						<div class='col-12 text-center cor_dados_viagem'>
							<span class='texto_titulo2 subtitulo1 text-uppercase text-center'>
								<strong>VAN PARA O AEROPORTO <br> SA&Iacute;DA: ". date("d/m", strtotime($reg_viagem->datahoravanretorno)) . " - " . date("H:i", strtotime($reg_viagem->datahoravanretorno)) ."</strong>
							</span>
						</div>
					</div>
				";
			}
		}
	}
	else {
		$html_viagem = "<div class='row text-center'><div class='col-12'><h6 class='texto_titulo1'>Voc&ecirc; n&atilde;o possui viagens cadastradas.</h6></div></div>";
	}
?>
<div class="container-fluid mt-4">
	<div class="row">
		<div class="col-6 mx-auto text-center texto_azul">
			<img src="img/icone_viagem_azul.png" width="50" height="44" class="mb-1"><span class="titulo4 text-nowrap"><strong>DADOS DA VIAGEM</strong></span>
		</div>
	</div>
	<div class="row mt-2" <?php if ($qtd_viagem == 0) echo "hidden"; ?>>
		<div class="col-4 offset-2 pr-0">
			<button type="button" class="btn cor_botao_boas_vindas cor_cabecalho texto_cinza btn_viagem px-4 float-right" id="btn_ida" valor="ida"><strong>IDA</strong></button>
		</div>
		<div class="col-4 pl-0">
			<button type="button" class="btn cor_botao_boas_vindas texto_cinza btn_viagem px-2" id="btn_volta" valor="volta"><strong>VOLTA</strong></button>
		</div>
	</div>
	<?php echo $html_viagem; ?>
</div>
<script>
	gera_log("Dados de Viagem", "Carregou os dados de viagem.");
</script>
<?php
	include_once "footer_2022.php";
?>