<?php 
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	require_once '../global.php';
	$max = 6; // Número máximo de blog
	$category = $Functions->Filter($_POST['category']);
	$row = $Functions->Filter($_POST['row']);
	//PARA VERIFICAR QUE LA CATEGORIA EXISTE
	$query = $db->prepare("SELECT null FROM categorias WHERE idcategorias = ? AND status = '1'");
	$query->bind_param("i", $category);
	$query->execute();
	$result = $query->get_result();
	$query->close();

	//LOS QUERY
	if(empty($category) or $result->num_rows <= 0 or !$Functions->CheckInt($category)){
		$dn = $db->query("SELECT blog.idblog as id, blog.titulo_blog as titulo, blog.imagen as imagen, 
		categorias.titulo as categoria, categorias.bg_color as bg_color, blog.fecha as fecha
		FROM blog INNER JOIN categorias on categorias.idcategorias = blog.categorias_idcategorias 
		WHERE blog.status = '1' ORDER BY blog.idblog ASC LIMIT ".$row.",$max"); 
	}else{
		$dn = $db->query("SELECT blog.idblog as id, blog.titulo_blog as titulo, blog.imagen as imagen, 
		categorias.titulo as categoria, categorias.bg_color as bg_color, blog.fecha as fecha
		FROM blog INNER JOIN categorias on categorias.idcategorias = blog.categorias_idcategorias 
		WHERE blog.status = '1' AND categorias_idcategorias = '{$category}' ORDER BY blog.idblog ASC LIMIT ".$row.",$max"); 
	}
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
?>