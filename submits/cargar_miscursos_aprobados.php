<?php 
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	require_once '../global.php';
	$max = 2; // Número máximo de cursos
	$userid = $Functions->Filter($Functions->Get('idusuarios_login'));
	$category = (int)$Functions->Filter($_POST['category']);
	$row = (int)$Functions->Filter($_POST['row']);
	//PARA VERIFICAR QUE LA CATEGORIA EXISTE
	$query = $db->prepare("SELECT null FROM categorias WHERE idcategorias = ? AND status = '1'");
	$query->bind_param("i", $category);
	$query->execute();
	$result = $query->get_result();
	$query->close();

	//LOS QUERY
	if(empty($category) or $result->num_rows <= 0 or !$Functions->CheckInt($category)){
		$dn = $db->query("SELECT cursos.idcursos as id, cursos.nombre as titulo, cursos.nivel as nivel, cursos.lecciones as lecciones,
		categorias.titulo as categoria, categorias.bg_color as bg_color, categorias.img as img
		FROM registro INNER JOIN cursos ON registro.cursos_idcursos = cursos.idcursos INNER JOIN categorias on categorias.idcategorias = cursos.categorias_idcategorias 
		WHERE registro.nivel_progreso = 'Aprobado' AND registro.usuarios_login_idusuarios_login = '{$userid}' ORDER BY cursos.idcursos ASC LIMIT ".$row.",$max"); 
	}else{
		$dn = $db->query("SELECT cursos.idcursos as id, cursos.nombre as titulo, cursos.nivel as nivel, cursos.lecciones as lecciones,
		categorias.titulo as categoria, categorias.bg_color as bg_color, categorias.img as img
		FROM registro INNER JOIN cursos ON registro.cursos_idcursos = cursos.idcursos INNER JOIN categorias on categorias.idcategorias = cursos.categorias_idcategorias 
		WHERE registro.nivel_progreso = 'Aprobado' AND registro.usuarios_login_idusuarios_login = '{$userid}' AND cursos.categorias_idcategorias = '{$category}' ORDER BY cursos.idcursos ASC LIMIT ".$row.",$max");
	}
	while($d = $dn->fetch_array()){
		$q = $db->query("SELECT COUNT(*) AS count FROM registro_lecciones 
				INNER JOIN lecciones on lecciones.idlecciones = registro_lecciones.lecciones_idlecciones 
				WHERE lecciones.cursos_idcursos = '{$d["id"]}' AND lecciones.status = '1'
				AND registro_lecciones.usuarios_login_idusuarios_login = '{$Functions->Get("idusuarios_login")}' ");
		$data = $q->fetch_array();
		$calculo_progreso = ($data['count']/$Functions->Filter($d["lecciones"]))*100;
		$progreso = round($calculo_progreso, 0, PHP_ROUND_HALF_UP);
?>  
	<div class="post col-lg-4 mb-5">
		<div class="card card-header-actions h-100">
			<div class="card-body">
				<h4 class="small mb-4">
					<span class="float-right badge badge-primary" style="background-color: <?php echo $Functions->Filter($d["bg_color"]);?> !important;"><?php echo $Functions->Filter($d['categoria']);?></span>
				</h4>
				<div class="row no-gutters align-items-center">
					<div class="col-md-3 mb-md-0 mb-3 text-center">
						<div class="icon-stack icon-stack-xl text-white flex-shrink-0" style="background-color: <?php echo $Functions->Filter($d["bg_color"]);?> !important;">
							<img src="<?php echo $Functions->Filter($d["img"]);?>" width="60" height="60">
						</div>
					</div>
					<div class="col-md-9">
						<div class="ml-4">
							<h5><?php echo $Functions->Filter($d["titulo"]);?></h5>
						</div>
					</div>
				</div>
				<h4 class="small mt-3">
					Progreso
					<span class="float-right font-weight-bold"><?php echo $progreso;?>%</span>
				</h4>
				<div class="progress">
					<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: <?php echo $progreso;?>%;" aria-valuenow="<?php echo $progreso;?>" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<hr>
				<div class="row align-items-center ml-1">
					<div class="col-sm-6 col-md-4 col-lg-6 col-xl-6 mb-xl-0 mb-md-0 mb-lg-3 mb-3">
						<h5>Lecciones</h5>
						<p class="card-text text-gray-600"><?php echo $data['count'];?>/<?php echo $Functions->Filter($d["lecciones"]);?></p>
					</div>
					<div class="col-sm-6 col-md-4 col-lg-6 col-xl-6 mb-xl-0 mb-md-0 mb-lg-3 mb-3">
						<h5>Nivel</h5>
						<p class="card-text text-gray-600"><?php if($Functions->Filter($d['nivel'])==1){echo "Fácil";}elseif($Functions->Filter($d['nivel'])==2){echo "Intermedio";}else{echo "Difícil";}?></p>
					</div>
				</div>
				<hr>								   
			</div>
			<a class="card-footer" href="<?php echo PATH;?>/curso/<?php echo $Functions->Filter($d["id"]);?>">
				<div class="d-flex align-items-center justify-content-between small text-body">
					Ver Curso
					<i data-feather="arrow-right"></i>
				</div>
			</a>
		</div>
	</div>
<?php
	}
?>