<?php
	/**
	* @author Douglas Vicentini Ferreira
	* @since  22/07/2021
	* Timeline da convenção
	**/
	$titulo_pagina = "Timeline";
	include_once "header.php";
	include_once "dados_rca.php";
?>
<div class="container-fluid">
	<div class="row ml-2 mt-4 mb-5">
		<div class="col-6 offset-3 col-md-6 offset-md-5 text-center open-sans-bold vertical-center">
			<img src="img/icone_timeline_azul.png" width="50" height="44" class="mb-1"> <span class="texto_titulo1 text-nowrap">Timeline</span>
		</div>
	</div>
	<?php
		require_once "../institucional/wp-load.php";
		$banner_principal = new WP_Query(
			array(
				'post_type' => 'banners',
				'posts_per_page' => -1,
				'meta_query'	 => array(
					'relation'	 => 'AND',
					array(
						'meta_key' => 'vigencia',
						'meta_value' => date('Ymd'),
						'meta_compare' => '>=',
					),
					array(
						'key'	 	=> 'convencao',
						'value'	  	=> 'foto', /* valores => banner e foto */
						'compare' 	=> 'like',
					)
				),
				/* 'tax_query' => array(
					array(
						'taxonomy' => 'cat_banner',
						'field' => 'slug',
						'terms' => 'destaque'
					)
				) */
			)
		);

		if ($banner_principal->have_posts()) :
			$array_fotos = array();
			while ($banner_principal->have_posts()) :
				$banner_principal->the_post();

				$img_banner   = get_field("imagem_banner");
				$url_banner   = get_field("url_do_banner");
				$atributo_url = get_field("atributo_url");

				$post_title	  = str_replace(" ", "", remove_accents(get_the_title($banner_principal->posts[$i]->id)));
				$post_title   = preg_replace('/[^A-Za-z0-9 _\-\+\&]/', "", $post_title);

				$post_id 	  = get_the_ID();

				$array_fotos["curtiu"][$post_id . $post_title] 	= "N";
				$array_fotos["link"][$post_id . $post_title] 	= $img_banner;
				$array_fotos["id_desc"][$post_id . $post_title] = $post_id . $post_title;

			endwhile;
		else :
			echo '<div class="row"><div class="col-12 text-center"><h3 class="texto_titulo1">Nenhuma foto encontrada.</h3></div></div>';
		endif;
		wp_reset_postdata();

		$lista_fotos = "";
		$indice = 0;
		foreach ($array_fotos["id_desc"] as $key => $value) {
			if ($indice != count($array_fotos["id_desc"]) - 1)
				$lista_fotos .= "'$value',";
			else
				$lista_fotos .= "'$value'";
			$indice++;
		}

		$sql_busca_post = "
			select *
			  from convencao.cnv_postinteracao po
			 where po.descricaopost in ($lista_fotos)
			   and po.codparticipante 	   = ". $_SESSION["rca_convencao"] ."
			   and lower(po.tipointeracao) = 'curtida'
			   and upper(po.curtiu) 	   = 'S'
			   and po.codintroducao		   = ". $_SESSION["codintroducao"] ."
		";
		$resultado_sql_busca_post = $conexao->TConsulta($sql_busca_post);
		$linhas_sql_busca_post	  = $conexao->TLinhas($resultado_sql_busca_post);

		if ($linhas_sql_busca_post > 0) {
			for ($i = 0; $i < $linhas_sql_busca_post; $i++) {
				$reg_post = $conexao->TFetch($resultado_sql_busca_post);
				$array_fotos["curtiu"][$reg_post->descricaopost] = "S";
			}
		}
		foreach ($array_fotos["curtiu"] as $key => $value) {
			echo '
					<div class="row mt-3">
						<div class="col-2 col-md-2 offset-md-1 col-lg-2 offset-lg-3">
							<img src="img/logo_timeline.jpg?v=1" alt="Logo do cabeçalho" class="rounded-circle logo_timeline">
						</div>
						<div class="col-10 col-md-9 col-lg-7 vertical-center">
							<h6 class="fonte-media texto1 text-uppercase"><strong>Conven&ccedil;&atilde;o de vendas bartofil '. date("Y") .'</strong></h6>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-12 col-md-10 offset-md-1 col-lg-6 offset-lg-3 px-0">
							<img src="'. $array_fotos["link"][$key] .'" alt="'. $key .'" class="w-100 foto_timeline">
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
							<button type="button" class="btn cursor-pointer btn_curte_foto" '. ($value == "S" ? "curtido='S' disabled" : "curtido='N'") .' id="btn_curtir_'. str_replace(" ", "", $key) .'">
								<i class="'. ($value == "S" ? "fas fa-heart" : "far fa-heart") .' fa-2x icone_timeline"></i>
							</button>
							<a class="btn cursor-pointer btn_compartilha_foto" href="'. $array_fotos["link"][$key] .'"
							id="btn_compartilhar_'. str_replace(" ", "", $key) .'" download>
								<i class="far fa-paper-plane fa-2x icone_timeline"></i>
							</a>
						</div>
					</div>
					'. ($banner_principal->current_post == 0 ? null : '<hr>') .'
				';
		}
	?>
</div>
<script>
	gera_log("Timeline", "Carregou a página.");
</script>
<?php
	include_once "footer_2022.php";
?>