<?php
	/**
	* @author Douglas Vicentini Ferreira
	* @since  15/07/2022
	* Página de escolha de convenção para quando RCA está cadastrado em mais de uma
	**/
	$titulo_pagina = "Escolha - Conven&ccedil;&atilde;o de Vendas 2021";
	include_once "header.php";

	// URL pode ser acessada apenas por RCAs com mais de uma convenção cadastrada
	if ($_SESSION["qtd_convencao_cadastrada"] < 1) {
		header("Location: index.php");
		exit;
	}
	$html_select_escolha = "<select class='form-control' name='codintroducao' onchange='this.form.submit()'><option>--> Escolha <--</option>";
	// Exibindo as convenções ativas que o RCA está cadastrado
	for ($i = 0; $i < $linhas_resultado; $i++) { 
		$reg_escolha = $conexao->TFetch($resultado_select);
		$html_select_escolha .= "<option value='$reg_escolha->codintroducao'>". utf8_encode($reg_escolha->introducao) ."</option>";
	}
	$html_select_escolha .= "</select>";
?>
<div class="container">
	<div class="row mb-1 mt-5 texto_azul">
		<div class="col-12 mt-md-3">
			<p class="text-center text-uppercase bg-white titulo3">
				<strong>
					Escolha qual conven&ccedil;&atilde;o voc&ecirc; ir&aacute; participar!
				</strong>
			</p>
		</div>
		<div class="col-12 col-md-11 col-lg-8 mx-auto mt-4">
			<form action="define_convencao.php" method="POST">
				<?php echo $html_select_escolha; ?>
			</form>
		</div>
	</div>
</div>
<script>
	gera_log("Boas Vindas", "Carregou a página de Boas Vindas.");
	document.querySelectorAll(".menu_cabecalho").forEach(element => element.remove());
</script>
<?php
	include_once "footer.php";
?>