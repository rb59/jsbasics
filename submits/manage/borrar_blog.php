<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	ob_start();
	require_once '../../global.php';
    $blogid = $Functions->Filter($_GET['blogid']);
	if(!empty($blogid)){
		$Functions->UpdateStatus('update','blog', 'idblog', '0', $blogid, null); // actualizamos la tabla blog
		$_SESSION['alert_blog'] = 'Lección eliminada correctamente';
		$_SESSION['alert_blog_type'] = 'alert-success';
		header("LOCATION: ". HK ."/blog.php");
		exit;
	}
	ob_end_flush();
?>
