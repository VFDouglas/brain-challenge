<?php
	include_once "./seguranca_convencao.php";
	include_once "./includes/TConexao.class.php";

	$codintroducao = isset($_POST["codintroducao"]) ? $_POST["codintroducao"] : null;
	if (!is_numeric($codintroducao)) {
		header("Location: sair.php");
		exit;
	}

	$conexao = new TConexao();
	$sql_dados_convencao_selecionada = "
		select *
		  from convencao.cnv_introducao i
		 where i.codintroducao = $codintroducao
	";
	$resultado_sql_dados = $conexao->TConsulta($sql_dados_convencao_selecionada);
	$reg_dados_selecao   = $conexao->TFetch($resultado_sql_dados);

	if ($conexao->TLinhas($resultado_sql_dados)) {
		$_SESSION["local"]						= $reg_dados_selecao->local;
		$_SESSION["codintroducao"]				= $reg_dados_selecao->codintroducao;
		$_SESSION["datainicio_convencao"]		= $reg_dados_selecao->datainicio;
		$_SESSION["dataencerramento_convencao"] = $reg_dados_selecao->dataencerramento;

		header("Location: index.php");
		exit;
	}
?>