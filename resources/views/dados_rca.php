<?php
	$sql_notificacao_rca = "
		select count(1) qtd
		  from convencao.cnv_notificacaoparticipante np
		 where np.codparticipante = ". $_SESSION["rca_convencao"] ."
		   and np.dataleitura IS NULL
		   and np.codintroducao = ". $_SESSION["codintroducao"] ."
	";
	$resultado_sql_notificacao = $conexao->TConsulta($sql_notificacao_rca);
	$qtd_notificacao = $conexao->TFetch($resultado_sql_notificacao);
?>
<div class="container-fluid">
	<div class="row vertical-center mt-4 mt-md-5 mt-lg-3 ml-1">
		<div class="col-10 col-md-8 mx-auto div_dados_rca">
			<div class="row">
				<div class="col-4 div_foto_cabecalho" onclick="busca_detalhe_ranking('<?php echo isset($_SESSION['rca_convencao']) ? $_SESSION['rca_convencao'] : $api_representante->api_dados[0]->codrepresentante; ?>')">
					<img src="<?php echo ($api_representante->api_dados[0]->foto != "" ? $api_representante->api_dados[0]->foto : "img/sem_foto.png"); ?>" alt="" class="w-100 rounded-circle" id="imagem_rca">
				</div>
				<div class="col-8 subtitulo2 texto_azul detalhes_rca vertical-center" onclick="busca_detalhe_ranking('<?php echo isset($_SESSION['rca_convencao']) ? $_SESSION['rca_convencao'] : $api_representante->api_dados[0]->codrepresentante; ?>')">
					<div>
						<strong class="text-nowrap">NOME: <?php echo $api_representante->api_dados[0]->apelido; ?></strong><br>
						<strong class="text-nowrap">C&Oacute;DIGO: <?php echo $_SESSION["rca_convencao"]; ?></strong><br>
						<strong class="text-nowrap">EQUIPE: <?php echo $api_representante->api_dados[0]->descequipe; ?></strong><br>
						<strong class="text-nowrap titulo_voo"><u>DETALHAMENTO DE PONTOS</u></strong><br>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row vertical-center my-5">
		<div class="col-12 col-md-8 mx-auto py-4 <?php echo $qtd_notificacao->qtd > 0 ? "bg-amarelo-notificacao cor_texto_com_notificacao" : "bg-light cor_texto_sem_notificacao"; ?> text-center"
		onclick="busca_notificacao_rca('S')" id="div_msg_notificacao">
			<i class="fas <?php echo $qtd_notificacao->qtd > 0 ? "fa-bell" : "fa-bell-slash"; ?> fa-lg mx-auto" id="icone_notificacao_home"></i>
			<span>
				<strong id="msg_notificacao">
					<?php echo ($qtd_notificacao->qtd > 0 ? "VOC&Ecirc; TEM <span id='qtd_notificacao'>" . str_pad($qtd_notificacao->qtd, 2, 0, STR_PAD_LEFT) . "</span>" . ($qtd_notificacao->qtd > 1 ? " NOVAS MENSAGENS" : " NOVA MENSAGEM") : "VOC&Ecirc; N&Atilde;O TEM NENHUMA NOVA MENSAGEM");?>
				</strong>
			</span>
		</div>
	</div>
</div>