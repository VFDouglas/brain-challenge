<?php
	/**
	* @author Douglas Vicentini Ferreira
	* @since  15/07/2021
	* Página de classificação da convenção
	**/
	$titulo_pagina = "Ranking";
	include_once "header.php";

	if (!in_array($_SESSION["codigogrupo"], $grupo_acesso_adm)) {
		header("Location: index.php");
		exit();
	}

	$codintroducao = isset($_REQUEST["codintroducao"]) ? ANTI_SQL($_REQUEST["codintroducao"]) : null;

	if (in_array($_SESSION["codigogrupo"], $grupo_acesso_adm)) {
		$sql_lista_convencao = "
			select *
			from convencao.cnv_introducao a
			where NOW() BETWEEN a.datainicio AND a.dataencerramento
		";
		$resultado_sql_lista = $conexao->TConsulta($sql_lista_convencao);
		$linhas_busca 		 = $conexao->TLinhas($resultado_sql_lista);

		if ($linhas_busca > 0) {
			$html_select_convencao = "
				<div class='container-fluid'>
					<div class='row mt-2'>
						<div class='col-10 offset-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3'>
							<button type='button' class='btn btn-block botao_convencao' ". (isset($codintroducao) ? "tipo='convencao'" : "tipo='rca'") ." id='btn_modo_ranking'>
								Trocar para ". (isset($codintroducao) ? "modo RCA" : "modo conven&ccedil;&atilde;o") ."
							</button>
						</div>
					</div>
					<div class='row mt-1'>
						<div class='col-10 offset-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3'>
							<select class='form-control' id='select_convencao'>
			";
			for ($i = 0; $i < $linhas_busca; $i++) { 
				$reg_busca = $conexao->TFetch($resultado_sql_lista);
				$html_select_convencao .= "<option value='". $reg_busca->codintroducao ."' ". ($codintroducao == $reg_busca->codintroducao ? "selected" : "") .">". htmlentities(utf8_encode($reg_busca->introducao), 0, "UTF-8") ."</option>";
			}
			$html_select_convencao .= "
							</select>
						</div>
					</div>
				</div>
			";
		}
	}

	if (is_null($codintroducao)) {
		include_once "dados_rca.php";
		$tempo_refresh = 60000;

		$codintroducao = $_SESSION["codintroducao"];
		$qtd_exibicao  = ($codintroducao == 1 ? 50 : 100);

		$sql_premiacao = "
			select *
				from convencao.cnv_premiacao a
				where a.codintroducao = $codintroducao
				order by a.colocacao
		";
		$resultado_sql_premiacao = $conexao->TConsulta($sql_premiacao);
		$linhas_sql_premiacao	 = $conexao->TLinhas($resultado_sql_premiacao);

		$sql_pontuacao = "
			SELECT *
				FROM (SELECT @rownum:=@rownum + 1 AS posicao, 
							t.*
						FROM ( 
							SELECT pg.codparticipante,
									pg.pontuacao,
									time_to_sec(pg.temporesposta) temporesposta,
									pa.nomeparticipante,
									pa.descequipe,
									pa.foto
								FROM convencao.cnv_pontuacaogeral pg,
									convencao.cnv_participante pa
								WHERE pg.codparticipante = pa.codparticipante
								AND pg.codintroducao = $codintroducao
								ORDER BY pg.pontuacao DESC, pg.temporesposta
							) t,
			(SELECT @rownum := 0) r ORDER BY t.pontuacao DESC, t.temporesposta) a
				WHERE a.posicao <= $qtd_exibicao OR a.codparticipante = ". $_SESSION["rca_convencao"] ."
		";
		$resultado_sql_pontuacao = $conexao->TConsulta($sql_pontuacao);
		$linhas_pontuacao_geral  = $conexao->TLinhas($resultado_sql_pontuacao);
		if ($linhas_pontuacao_geral > 0) {
			$html_pontuacao = "";
			for ($i = 0; $i < $linhas_pontuacao_geral; $i++) {
				$reg_pontuacao = $conexao->TFetch($resultado_sql_pontuacao);
				$reg_premiacao = $conexao->TFetch($resultado_sql_premiacao);

				$_SESSION["colocacao_rca"][$reg_pontuacao->codparticipante] = $reg_pontuacao->posicao;

				if ($_SESSION["rca_convencao"] == $reg_pontuacao->codparticipante || in_array($_SESSION["codigogrupo"], $grupo_acesso_adm))
					$funcao_detalhe = "onclick='busca_detalhe_ranking(". $reg_pontuacao->codparticipante .")'";
				else
					$funcao_detalhe = "";

				$horas 	  = intval($reg_pontuacao->temporesposta / 3600);
				$minutos  = intval($reg_pontuacao->temporesposta % 3600 / 60);
				$segundos = intval($reg_pontuacao->temporesposta % 60);

				if ($horas 	  < 10) $horas 	  = "0" . $horas;
				if ($minutos  < 10) $minutos  = "0" . $minutos;
				if ($segundos < 10) $segundos = "0" . $segundos;
				$temporesposta = ($horas > 0 ? $horas . "&ordm; " : "") . ($minutos > 0 ? $minutos . "&lsquo; " : "") . $segundos . "&ldquo;";

				$cor_fonte = $_SESSION["rca_convencao"] == $reg_pontuacao->codparticipante ? "text-white' style='font-family: poppins" : null;

				if ($i < 3) {
					if ($i == 0) $medalha = "ouro";
					else if ($i == 1) $medalha = "prata";
					else if ($i == 2) $medalha = "bronze";


					$html_pontuacao .= "
						<div class='row mt-4 texto_classificacao mr-sm-4'>
							<div class='col-10 offset-1 col-lg-6 offset-lg-3'>
								<div class='row div_top3 texto_classificacao vertical-center ". ($_SESSION["rca_convencao"] == $reg_pontuacao->codparticipante ? "rca_logado" : "") ."' $funcao_detalhe>
									<div class='col-3 div_imagem_demais_colocados'>
										<img src='img/medalha_$medalha.png' class='medalha_colocacao ml-5 mt-sm-1'>
									</div>
									<div class='col-7 offset-2 text-uppercase'>
										<strong class='text-nowrap fonte-media $cor_fonte'>C&oacute;digo: ". $reg_pontuacao->codparticipante ."<br></strong>
										<strong class='text-nowrap fonte-media $cor_fonte'>Equipe: ". $reg_pontuacao->descequipe ."<br></strong>
										<hr class='my-0'>
										<strong class='text-nowrap subtitulo2 $cor_fonte'>". intval($reg_pontuacao->pontuacao) ." pontos - ". $temporesposta ."</strong>
										". ($reg_premiacao->colocacao == $reg_pontuacao->posicao ? "<br><strong class='text-nowrap $cor_fonte'>R$ ". number_format($reg_premiacao->premiacao, 0, ",", ".") ."</strong><br>" : null) ."
									</div>
								</div>
							</div>
						</div>
					";
				}
				else {
					$html_pontuacao .= "
						<div class='row mt-3'>
							<div class='col-10 offset-1 col-lg-6 offset-lg-3'>
								<div class='row div_classificacao_rca texto_classificacao vertical-center ". ($_SESSION["rca_convencao"] == $reg_pontuacao->codparticipante ? "rca_logado" : "") ."' $funcao_detalhe>
									<div class='col-9 text-uppercase'>
										<strong class='text-nowrap fonte-media $cor_fonte'>C&oacute;digo: ". $reg_pontuacao->codparticipante ."<br></strong>
										<strong class='text-nowrap fonte-media $cor_fonte'>Equipe: ". $reg_pontuacao->descequipe ."<br></strong>
										<hr class='my-0'>
										<strong class='text-nowrap $cor_fonte'>
											". intval($reg_pontuacao->pontuacao) ." pontos - ". $temporesposta ."
											<br>". ($reg_premiacao->colocacao == $reg_pontuacao->posicao ? "
											R$ ". number_format($reg_premiacao->premiacao, 0, ",", ".") ."<br>" : null) ."
										</strong>
									</div>
									<div class='col-3 vertical-center'>
										<div class='posicao_rca ml-3'><strong ". ($reg_pontuacao->posicao < 10 ? "style='padding: 5px;'" : "") .">". $reg_pontuacao->posicao ."&ordm;</strong></div>
									</div>
								</div>
							</div>
						</div>
					";
				}
			}
		}
	}
	else {
		$qtd_exibicao = ($codintroducao == 1 ? 50 : 100);

		$sql_premiacao = "
			select *
			  from convencao.cnv_premiacao a
			 where a.codintroducao = $codintroducao
			 order by a.colocacao
		";
		$resultado_sql_premiacao = $conexao->TConsulta($sql_premiacao);
		$linhas_sql_premiacao	 = $conexao->TLinhas($resultado_sql_premiacao);

		$tempo_refresh = 300000;
		$sql_dados_convencao = "
			SELECT *
			  FROM (SELECT @rownum:=@rownum + 1 AS posicao,
						   t.*
					  FROM (
							SELECT pg.codparticipante,
								   pg.pontuacao,
								   time_to_sec(pg.temporesposta) temporesposta,
								   pa.nomeparticipante,
								   pa.descequipe,
								   pa.foto
							  FROM convencao.cnv_pontuacaogeral pg,
								   convencao.cnv_participante pa
							 WHERE pg.codparticipante = pa.codparticipante
							   AND pg.codintroducao = $codintroducao
							 ORDER BY pg.pontuacao DESC, pg.temporesposta
						   ) t,
			(SELECT @rownum := 0) r ORDER BY t.pontuacao DESC, t.temporesposta) a
			 WHERE a.posicao <= $qtd_exibicao
		";
		$resultado_sql_dados_convencao = $conexao->TConsulta($sql_dados_convencao);
		$linhas_dados_convencao		   = $conexao->TLinhas($resultado_sql_dados_convencao);

		$html_pontuacao = "";
		if ($linhas_dados_convencao > 0) {
			for ($i = 0; $i < $linhas_dados_convencao; $i++) {
				$reg_dados 	   = $conexao->TFetch($resultado_sql_dados_convencao);
				$reg_premiacao = $conexao->TFetch($resultado_sql_premiacao);

				$_SESSION["colocacao_rca"][$reg_dados->codparticipante] = $reg_dados->posicao;

				if ($_SESSION["rca_convencao"] == $reg_dados->codparticipante || in_array($_SESSION["codigogrupo"], $grupo_acesso_adm))
					$funcao_detalhe = "onclick='busca_detalhe_ranking(". $reg_dados->codparticipante .")'";
				else
					$funcao_detalhe = "";

				$horas 	  = intval($reg_dados->temporesposta / 3600);
				$minutos  = intval($reg_dados->temporesposta % 3600 / 60);
				$segundos = intval($reg_dados->temporesposta % 60);

				if ($horas 	  < 10) $horas 	  = "0" . $horas;
				if ($minutos  < 10) $minutos  = "0" . $minutos;
				if ($segundos < 10) $segundos = "0" . $segundos;
				$temporesposta = ($horas > 0 ? $horas . "&ordm; " : "") . ($minutos > 0 ? $minutos . "&lsquo; " : "") . $segundos . "&ldquo;";

				if ($_SESSION["rca_convencao"] == $reg_dados->codparticipante)
					$cor_fonte = "text-white' style='font-family: Open Sans;'";
				else
					$cor_fonte = "fonte-open-sans";

				if ($i < 3) {
					if ($i == 0) $medalha = "ouro";
					else if ($i == 1) $medalha = "prata";
					else if ($i == 2) $medalha = "bronze";

					$html_pontuacao .= "
						<div class='row mt-4 texto_classificacao mr-sm-4'>
							<div class='col-10 offset-1 col-lg-6 offset-lg-3'>
								<div class='row div_top3 texto_classificacao vertical-center ". ($_SESSION["rca_convencao"] == $reg_dados->codparticipante ? "rca_logado" : "") ."' $funcao_detalhe>
									<div class='col-3 div_imagem_demais_colocados'>
										<img src='img/medalha_$medalha.png' class='medalha_colocacao ml-5 mt-sm-1'>
									</div>
									<div class='col-7 offset-2 text-uppercase'>
										<strong class='text-nowrap fonte-media $cor_fonte'>C&oacute;digo: ". $reg_dados->codparticipante ."<br></strong>
										<strong class='text-nowrap fonte-media $cor_fonte'>Equipe: ". $reg_dados->descequipe ."<br></strong>
										<hr class='my-0'>
										<strong class='text-nowrap $cor_fonte'>". intval($reg_dados->pontuacao) ." pontos - ". $temporesposta ."</strong>
										". ($reg_premiacao->colocacao == $reg_dados->posicao ? "<br><strong class='text-nowrap $cor_fonte'>R$ ". number_format($reg_premiacao->premiacao, 0, ",", ".") ."</strong><br>" : null) ."
									</div>
								</div>
							</div>
						</div>
					";
				}
				else {
					$html_pontuacao .= "
						<div class='row mt-3'>
							<div class='col-10 offset-1 col-lg-6 offset-lg-3'>
								<div class='row div_classificacao_rca texto_classificacao vertical-center ". ($_SESSION["rca_convencao"] == $reg_dados->codparticipante ? "rca_logado" : "") ."' $funcao_detalhe>
									<div class='col-9 text-uppercase'>
										<strong class='text-nowrap fonte-media $cor_fonte'>C&oacute;digo: ". $reg_dados->codparticipante ."<br></strong>
										<strong class='text-nowrap fonte-media $cor_fonte'>Equipe: ". $reg_dados->descequipe ."<br></strong>
										<hr class='my-0'>
										<strong class='text-nowrap $cor_fonte'>
											". intval($reg_dados->pontuacao) ." pontos - ". $temporesposta ."
											<br>". ($reg_premiacao->colocacao == $reg_pontuacao->posicao ? "
											R$ ". number_format($reg_premiacao->premiacao, 0, ",", ".") ."<br>" : null) ."
										</strong>
									</div>
									<div class='col-3 vertical-center'>
										<div class='posicao_rca ml-3'><strong ". ($reg_dados->posicao < 10 ? "style='padding: 5px;'" : "") .">". $reg_dados->posicao ."&ordm;</strong></div>
									</div>
								</div>
							</div>
						</div>
					";
				}
			}
		}
		else {
			$html_pontuacao = "<h6 class='text-center texto_titulo1'>Sem dados para a conven&ccedil;&atilde;o.</h6>";
		}
	}
	echo $html_select_convencao;
?>
<div class="container-fluid mt-3">
	<div class="row">
		<div class="col-6 offset-3 col-md-6 offset-md-5 text-center text-uppercase vertical-center">
			<img src="img/icone_ranking_azul.png" width="50" height="44" class="ml-5 mb-1"> <span class="texto_titulo1 text-nowrap"><strong>Ranking</strong></span>
		</div>
	</div>
	<?php echo $html_pontuacao; ?>
</div>
<?php
	include_once "footer_2022.php";
	if (!is_null($_REQUEST["codintroducao"]) && in_array($_SESSION["codigogrupo"], $grupo_acesso_adm)) {
		echo "
			<script>
				function rolagem_automatica() {
					if ($(document).scrollTop() < 10) {
						$([document.documentElement, document.body]).animate({
							scrollTop: $('footer').offset().top
						}, 15000);
					}
					else {
						$([document.documentElement, document.body]).animate({
							scrollTop: 0
						}, 15000);
					}
				}
				rolagem_automatica();

				var rolamento = setInterval(() => {
					rolagem_automatica();
				}, 15000);
				window.addEventListener('wheel', function() {
					clearInterval(rolamento);
				});
			</script>
		";
	}
?>
<script>
	setInterval(() => {window.location.reload();}, <?php echo $tempo_refresh; ?>);
</script>