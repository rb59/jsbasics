<?php 
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	global $db;
	global $Functions;
	global $TplClass;
	$category = $Functions->Filter($_GET['category']);
	//VERIFICAMOS QUE LA CATEGORIA EXISTE
	$query = $db->prepare("SELECT null FROM categorias WHERE idcategorias = ? AND status = '1'");
	$query->bind_param("i", $category);
	$query->execute();
	$result = $query->get_result();
	$query->close();
	$max = 6; // Número máximo de blog

?>
	<section class="bg-light py-1">
		<div class="container mt-4">
			<div class="row" data-aos="fade-up" data-aos-delay="50">
			<div class="col-md-6"></div>
				<div class="col-md-6 mb-3">
					<select class="sel form-control input" id="select_post" action="<?php echo PATH;?>/blog" onchange="url(this.value);">
						<option value="">Ordenar por: todas las categorías</option>
						<?php
							$queryy = $db->query("SELECT * FROM categorias WHERE status = '1' ORDER BY idcategorias DESC");
							while($rr = $queryy->fetch_array()){ 
								if ($rr['idcategorias'] == $category){
									$selected = 'selected';
								}else{
									$selected = '';
								}
						?>
							<option value="<?php echo $Functions->Filter($rr['idcategorias']);?>" <?php echo $selected;?>>Ordenar por: <?php echo $Functions->Filter($rr['titulo']);?></option>
						<?php }?>
					</select>
				</div>
			</div>
			<hr class="mt-0">
			<div class="row justify-content-center" data-aos="fade-up" data-aos-delay="50">
			<?php
				if(empty($category) or $result->num_rows <= 0 or !$Functions->CheckInt($category)){
					$dn = $db->query("SELECT blog.idblog as id, blog.titulo_blog as titulo, blog.imagen as imagen, 
					categorias.titulo as categoria, categorias.bg_color as bg_color, blog.fecha as fecha
					FROM blog INNER JOIN categorias on categorias.idcategorias = blog.categorias_idcategorias 
					WHERE blog.status = '1' ORDER BY blog.idblog ASC LIMIT 0,$max"); 
					$allcount = $Functions->Filter($Functions->GetCount('count', 'blog', null, null));
				}else{
					$dn = $db->query("SELECT blog.idblog as id, blog.titulo_blog as titulo, blog.imagen as imagen, 
					categorias.titulo as categoria, categorias.bg_color as bg_color, blog.fecha as fecha
					FROM blog INNER JOIN categorias on categorias.idcategorias = blog.categorias_idcategorias 
					WHERE blog.status = '1' AND categorias_idcategorias = '{$category}' ORDER BY blog.idblog ASC LIMIT 0,$max"); 
					$allcount = $Functions->Filter($Functions->GetCount('count-where', 'blog', 'categorias_idcategorias', $category));
				}
				if($dn->num_rows > 0){
					while($d = $dn->fetch_array()){
			?>
				<div class="post col-lg-4 mb-5">
					<a class="card text-decoration-none h-100 post-preview post-preview-featured lift" href="<?php echo PATH;?>/blog/<?php echo $Functions->Filter($d["id"]);?>">
						<div class="card-body pt-0">
							<div class="row">
								<div class="blog_header">
								<img class="blog_image" src="<?php echo $Functions->Filter($d['imagen']);?>">
								<span class="blog_topic" style="background-color: <?php echo $Functions->Filter($d['bg_color']);?>;"><?php echo $Functions->Filter($d["categoria"]);?></span></div>
								<div class="col-md-12 mt-4">
									<h2><?php echo $Functions->Filter($d["titulo"]);?></h2>
								</div>
							</div>
						</div>
						<div class="card-footer">
						<small class="text-muted"><i class="far fa-clock"></i> <?php echo $Functions->Date(date("F d, Y", strtotime($Functions->Filter($d['fecha']))))?></small>
						</div>
					</a>
				</div>
			<?php 
				}
			}else{ 
				echo "<p class='text-center font-weight-normal mt-5 mb-5'>No hay registros disponibles :(<p>";
			}
			?>
			</div>
			<?php if($allcount > $max){ ?>
			<div class="row">
				<div class="col-lg-12 text-center mb-5">
					<button type="button" class="load-more btn btn-outline-primary text-right" action="<?php echo PATH;?>/cargar_blog" max_post="<?php echo $max;?>">Cargar más registros</button>
					<input type="hidden" id="category" value="<?php echo $category; ?>">
					<input type="hidden" id="row" value="0">
            		<input type="hidden" id="all" value="<?php echo $allcount; ?>">
				</div>
			</div>
			<?php }?>
		</div>
        <?php
			$TplClass->SetParam('svg-border_color', 'text-whitee'); // COLOR DEL BORDE
			$TplClass->AddTemplate("svg-border", "waves"); // AÑADIMOS EL BORDE
		?>
	</section>