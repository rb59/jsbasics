<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	ob_start();
	require_once '../../global.php';
    $categoriaid = $Functions->Filter($_GET['categoriaid']);
	if(!empty($categoriaid)){
		$Functions->UpdateStatus('update','categorias', 'idcategorias', '0', $categoriaid, null); // actualizamos la tabla categoria
		$Functions->UpdateStatus('update','cursos', 'categorias_idcategorias', '0', $categoriaid, null); // actualizamos la tabla cursos
		$Functions->UpdateStatus('update','blog', 'categorias_idcategorias', '0', $categoriaid, null); // actualizamos la tabla blog
		//BORRAMOS TODOS LAS LECCIONES CON ESA CATEGORIA 
		$query = $db->prepare("UPDATE lecciones 
		INNER JOIN cursos ON cursos.idcursos  = lecciones.cursos_idcursos
		INNER JOIN categorias ON categorias.idcategorias = cursos.categorias_idcategorias
		 SET lecciones.status = '0' WHERE categorias.idcategorias = ?");
		$query->bind_param("i", $categoriaid);
		$query->execute();
		$query->close();
		$_SESSION['alert_categoria'] = 'Categoría eliminada correctamente';
		$_SESSION['alert_categoria_type'] = 'alert-success';
		header("LOCATION: ". HK ."/categorias.php");
		exit;
	}
	ob_end_flush();
?>
