<?php 
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|							cd 							  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
global $Functions; 
global $TplClass;
global $db;
$id = $Functions->Filter($_GET['id']);
$dn = $db->query("SELECT cursos.idcursos as id, cursos.nombre as titulo, cursos.nivel as nivel, cursos.lecciones as lecciones,
cursos.descripcion as descripcion, categorias.titulo as categoria, categorias.bg_color as bg_color, cursos.img as img 
FROM cursos INNER JOIN categorias on categorias.idcategorias = cursos.categorias_idcategorias WHERE idcursos = '{$id}' AND cursos.status = '1'");
$d = $dn->fetch_array();
if(empty($d['id']) or !$Functions->CheckInt($id)){
	header("LOCATION: ". PATH ."/cursos");
	exit;	  
}
?> 
	<section class="bg-light py-1">
		<div class="container">
			<div class="row justify-content-center" data-aos="fade-up" data-aos-delay="50">
				<div class="col-xl-8 padding-0_75r mt-4 mb-4">
					<div class="card bg-transparent22 post-preview post-preview-featured mb-5">
					<div class="row no-gutters">
                    	<div class="col-lg-5">
							<div class="post-preview-featured-img" style="background-image: url(<?php echo $Functions->Filter($d["img"]);?>);background-size: contain; "></div>
						</div>
						<div class="col-lg-7">
							<div class="card-body bg-light4">
								<div class="d-flex align-items-center">
									<div class="col-md-6">
										<h5>Nivel</h5>
										<p class="card-text text-gray-cfd"><?php if($Functions->Filter($d['nivel'])==1){echo "Fácil";}elseif($Functions->Filter($d['nivel'])==2){echo "Intermedio";}else{echo "Difícil";}?></p>
									</div>
									<div class="col-md-6">
										<h5>Categoría</h5>
										<p class="card-text text-gray-cfd"><?php echo $Functions->Filter($d['categoria']);?></p>
									</div>
								</div>
								<hr>
								<div class="py-3">
									<h5 class="card-title"><?php echo $Functions->Filter($d['titulo']);?></h5>
									<p class="card-text text-gray-cfd"><?php echo $Functions->Filter($d['descripcion']);?></p>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body bg-white">
						<div class="courseModule">
							<h5 class="text-dark2">Lecciones: <span class="text-primary22"><?php echo $Functions->Filter($d["lecciones"]);?></span>  </h5>
						</div>
						<div class="course-section">
							<div class="panel-group">
							<?php   
							$dn2 = $db->query("SELECT * FROM lecciones WHERE cursos_idcursos = '{$d['id']}' AND status = '1' ORDER BY numero "); 
							while($d2 = $dn2->fetch_array()){
							?>
								<div class="course-panel-heading" >
								<?php if($Functions->LeccionDisponible($d2['numero'],$d['id'])){ ?>
									<div class="panel-heading-left" onclick="window.location.href='<?php echo PATH;?>/leccion/<?php echo $Functions->Filter($d2['idlecciones']);?>'">
										<div class="course-lesson-icon">
											<i class="far fa-sticky-note"></i>
										</div>
										<div class="title">
											<h4> <?php echo $Functions->Filter($d2['titulo']);?> <span class="badge-item practice">lección</span></h4>
											<p class="subtitle"></p>
										</div>
									</div>
									
									<div class="panel-heading-right"> 
										<a class="video-lesson-preview preview-button" href="<?php echo PATH;?>/leccion/<?php echo $Functions->Filter($d2["idlecciones"]);?>">
										<i class="fas fa-arrow-right"></i>Ver lección</a>
									</div>
									<?php }else{?>
									<div class="panel-heading-left">
									<div class="course-lesson-icon">
										<i class="far fa-sticky-note"></i>
									</div>
									<div class="title">
										<h4> <?php echo $Functions->Filter($d2['titulo']);?> <span class="badge-item practice">lección</span></h4>
										<p class="subtitle"></p>
										</div>
									</div>
									<div class="panel-heading-right">
										<div class="private-lesson">
											<i class="fa fa-lock"></i>
											<span>Privado</span>
										</div>
									</div>	

									<?php }?>
								</div>

							<?php }?>
								

					
								
							</div> 
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		<?php
			$TplClass->SetParam('svg-border_color', 'text-whitee'); // COLOR DEL BORDE
			$TplClass->AddTemplate("svg-border", "waves"); // AÑADIMOS EL BORDE
		?>
	</section>