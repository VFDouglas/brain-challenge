<?php
	/**
	 * @author Douglas Vicentini Ferreira
	 * @since  08/09/2021
	 * Controle de segurança do sistema de convenção
	 **/
	if(session_status() === PHP_SESSION_NONE)
		session_start();

	if(in_array($_SESSION["codpessoa"], array(430599, 589520))) {
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
	}

	function gera_log_admin($sql, $pagina, $descricao) {
		require_once __DIR__ . '/includes/TConexao.class.php';

		$pagina    = str_replace("'", "''", $pagina);
		$descricao = str_replace("'", "''", utf8_decode($descricao));
		$sql       = str_replace("'", "''", $sql);

		$sql_log = "
			insert into convencao.cnv_logadministrativo (codpessoa, pagina, descricao, instrucaosql, datalog)
			values (" . $_SESSION["codpessoa"] . ", '$pagina', '$descricao', '$sql', NOW())
		";
		$conexao = new TConexao();
		$conexao->TConsulta($sql_log);
	}

	$diretorio_raiz = "/var/www/html/bartofil/site";

	$dir              = $_SERVER["DOCUMENT_ROOT"] . "/site/";
	$grupo_acesso_adm = array(5, 9, 15, 22); // Grupos que podem ver todos os representantes
	$codrepresentante = null;

	// Se o grupo for o de representante, pegamos da sessão
	if($_SESSION["codigogrupo"] == 3) {
		$codrepresentante = str_ireplace("RCA", "", $_SESSION["codusuario"]);
	}
	// Se for algum grupo de acesso administrador
	else if(in_array($_SESSION["codigogrupo"], $grupo_acesso_adm)) {
		$codrepresentante = isset($_REQUEST["rca"]) ? ANTI_SQL($_REQUEST["rca"]) : $_SESSION["rca_convencao"];
	}
	// Se não for fornecedor ou nenhum dos outros acima, direcionamos à página principal
	else if($_SESSION["codigogrupo"] != 7 || empty($_SESSION["codpessoa"])) {
		header("Location: /site/convencao/sair.php");
		exit;
	}