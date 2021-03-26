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
	$query = $db->prepare("SELECT null FROM categorias WHERE idcategorias = ? AND status = '1'  AND p_blog != '1'");
	$query->bind_param("i", $category);
	$query->execute();
	$result = $query->get_result();
	$query->close();
	$max = 4; // Número máximo de cursos

?>  
	<section class="bg-light py-1">
		<div class="container mt-4">
			<div class="row">
			<div class="col-md-6"></div>
				<div class="col-md-6 mb-3" data-aos="fade-up" data-aos-delay="50">
					<select class="sel form-control input" id="select_post" action="<?php echo PATH;?>/cursos" onchange="url(this.value);">
						<option value="">Ordenar por: todas las categorías</option>
						<?php
							$queryy = $db->query("SELECT * FROM categorias WHERE status = '1' AND p_blog != '1' ORDER BY idcategorias DESC");
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
					$dn = $db->query("SELECT cursos.idcursos as id, cursos.nombre as titulo, cursos.nivel as nivel, cursos.lecciones as lecciones,
					cursos.descripcion as descripcion, categorias.titulo as categoria, categorias.bg_color as bg_color, categorias.img as img 
					FROM cursos INNER JOIN categorias on categorias.idcategorias = cursos.categorias_idcategorias 
					WHERE cursos.status = '1' ORDER BY cursos.idcursos ASC LIMIT 0,$max"); 
					$allcount = $Functions->Filter($Functions->GetCount('count', 'cursos', null, null));
				}else{
					$dn = $db->query("SELECT cursos.idcursos as id, cursos.nombre as titulo, cursos.nivel as nivel, cursos.lecciones as lecciones,
					cursos.descripcion as descripcion, categorias.titulo as categoria, categorias.bg_color as bg_color, categorias.img as img 
					FROM cursos INNER JOIN categorias on categorias.idcategorias = cursos.categorias_idcategorias 
					WHERE cursos.status = '1' AND cursos.categorias_idcategorias = '{$category}' ORDER BY cursos.idcursos ASC LIMIT 0,$max"); 
					$allcount = $Functions->Filter($Functions->GetCount('count-where', 'cursos', 'categorias_idcategorias', $category));
				}
				if($dn->num_rows > 0){
					while($d = $dn->fetch_array()){
			?>
				<div class="post col-lg-6 mb-5">
					<a class="card text-decoration-none h-100 lift" href="<?php echo PATH;?>/curso/<?php echo $Functions->Filter($d["id"]);?>"><div class="card-body py-5">
						<div class="row align-items-center">
							<div class="col-md-2 mb-md-0 mb-3 text-center">
								<div class="icon-stack icon-stack-xl text-white flex-shrink-0" style="background-color: <?php echo $Functions->Filter($d["bg_color"]);?> !important;">
									<img src="<?php echo $Functions->Filter($d["img"]);?>" width="60" height="60">
								</div>
							</div>
							<div class="col-md-10">
								<div class="ml-4">
									<h5><?php echo $Functions->Filter($d["titulo"]);?></h5>
									<p class="card-text text-gray-600"><?php echo $Functions->Filter($d["descripcion"]);?></p>
								</div>
							</div>
						</div>
						<hr>
						<div class="row align-items-center ml-1">
							<div class="col-sm-6 col-md-4 col-lg-6 col-xl-4 mb-xl-0 mb-md-0 mb-lg-3 mb-3">
								<h5>Lecciones</h5>
								<p class="card-text text-gray-600"><?php echo $Functions->Filter($d['lecciones']);?></p>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-6 col-xl-4 mb-xl-0 mb-md-0 mb-lg-3 mb-3">
								<h5>Categoría</h5>
								<p class="card-text text-gray-600"><?php echo $Functions->Filter($d['categoria']);?></p>
							</div>
							<div class="col-sm-6 col-md-4 col-lg-6 col-xl-4 mb-xl-0 mb-md-0 mb-lg-3 mb-3">
								<h5>Nivel</h5>
								<p class="card-text text-gray-600"><?php if($Functions->Filter($d['nivel'])==1){echo "Fácil";}elseif($Functions->Filter($d['nivel'])==2){echo "Intermedio";}else{echo "Difícil";}?></p>
							</div>
						</div>
						<hr>
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
					<button type="button" class="load-more btn btn-outline-primary text-right" action="<?php echo PATH;?>/cargar_cursos" max_post="<?php echo $max;?>">Cargar más</button>
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