<?php
	/**
	* @author Douglas Vicentini Ferreira
	* @since  28/06/2021
	* Página de quiz da convenção
	**/
	$titulo_pagina = "Perguntas Extras";
	include_once "header.php";
	include_once "dados_rca.php";

	$sql_busca_pergunta = "
		SELECT *
		  FROM convencao.cnv_perguntaextra p
		 WHERE NOW() BETWEEN p.datainicio AND p.datafim
		   AND p.status = 'A'
		   AND NOT EXISTS (
			   SELECT 1
				 FROM convencao.cnv_respostaextra r
				WHERE r.codparticipante = ". $_SESSION["rca_convencao"] ."
				  AND r.codpergunta 	= p.codpergunta
				  AND r.codintroducao = ". $_SESSION["codintroducao"] ."
			   )
		 LIMIT 1
	";
	$resultado_sql 	  = $conexao->TConsulta($sql_busca_pergunta);
	$linhas_perguntas = $conexao->TLinhas($resultado_sql);

	if ($linhas_perguntas > 0) {
		$reg = $conexao->TFetch($resultado_sql);

		$_SESSION["codpergunta_extra"] = $reg->codpergunta;
		$pergunta	 	  			   = $reg->pergunta;

		$sql_opcao_pergunta = "
			SELECT a.codpergunta, a.codopcao, upper(a.descricaoopcao) descricaoopcao, a.correto, a.status
			  FROM convencao.cnv_opcaoextra a
			 WHERE a.codpergunta = ". $_SESSION["codpergunta_extra"] ."
			   AND a.status		 = 'A'
		";
		$resultado_sql_opcao = $conexao->TConsulta($sql_opcao_pergunta);
		$linhas_sql_opcao	 = $conexao->TLinhas($resultado_sql_opcao);

		$html_opcao = "";
		if ($linhas_sql_opcao > 0) {
			for ($i = 0; $i < $linhas_sql_opcao; $i++) {
				$reg_opcao = $conexao->TFetch($resultado_sql_opcao);
				if ($reg_opcao->correto == "S") {
					$_SESSION["opcaocorreta"] = $reg_opcao->codopcao;
				}
				$html_opcao .= '
					<div class="col-11 col-md-8 mx-auto texto_titulo2">
						<input type="radio" required class="radio_opcao_pergunta" name="radio_opcao_pergunta" codopcao="'. $reg_opcao->codopcao .'" id="radio_opcao_pergunta'. $i .'" correta="'. $reg_opcao->correto .'">&nbsp;&nbsp;
						<label for="radio_opcao_pergunta'. $i .'">&nbsp;&nbsp;<strong>'. $reg_opcao->descricaoopcao .'</strong></label>
					</div>
				';
			}
		}
		else {
			$html_opcao .= "<div class='col-11 text-center texto_titulo1'><h6>Nenhuma alternativa cadastrada para a pergunta.</h6></div>";
		}
	}
	else {
		$retorno_pergunta = "Nenhuma pergunta extra dispon&iacute;vel no momento.";
	}
?>
<div class="container-fluid mt-2">
	<div class="row text-center mt-4">
		<div class="col-12">
			<div class="d-inline-flex">
				<img src="img/icone_quiz_azul.png" width="50" height="50" alt=""><h2 class="texto_titulo1"><strong>PERGUNTAS EXTRAS</strong></h2>
			</div>
		</div>
	</div>
	<hr>
	<?php
		if ($linhas_perguntas > 0) {
	?>
	<div class="row" id="div_titulo_pergunta">
		<div class="col-11 col-md-8 mx-auto text-center">
			<h2 class="texto_titulo1" id="titulo_pergunta">
				<strong>
					<p id="nome_pergunta"><?= utf8_encode($pergunta); ?></p>
				</strong>
			</h2>
		</div>
		<div class="col-12 text-center mt-2 " <?php if ($linhas_perguntas > 0) echo "hidden"; ?> id="btn_volta_pagina_inicial">
			<a href="index.php" class="btn btn-block text-uppercase fonte-open-sans fonte-media botao_convencao vertical-center" style="height: 125%; justify-content: center;">
				Voltar &agrave; p&aacute;gina inicial
			</a>
		</div>
	</div>
	<form id="form_pergunta_extra">
		<div class="row mt-1" id="opcoes_pergunta">
			<?php echo utf8_encode($html_opcao); ?>
		</div>
		<div class="row mt-4" id="div_btn_envia_pergunta" <?php if ($linhas_perguntas == 0) echo "hidden"; ?>>
			<div class="col-12 text-center">
				<button class="btn btn_enviar_quiz px-5" type="submit" id="btn_envia_resposta"><strong>ENVIAR</strong></button>
			</div>
		</div>
	</form>
	<?php
		}
		else {
			echo "
				<div class='row'>
					<div class='col-12'>
						<h6 class='text-center texto_titulo1'><strong>". $retorno_pergunta ."</strong></h6>
					</div>
				</div>
			";
		}
	?>
</div>
<script>
	if (document.getElementById("nome_pergunta")) {
		gera_log("Quiz", `Carregou a pergunta extra '${document.getElementById("nome_pergunta").innerText}'.`);
	}
	else {
		gera_log("Quiz", "Nenhuma pergunta encontrada.");
	}
</script>
<?php
	include_once "footer_2022.php";
?>