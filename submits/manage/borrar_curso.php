<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	ob_start();
	require_once '../../global.php';
    $cursoid = $Functions->Filter($_GET['cursoid']);
	if(!empty($cursoid)){
		$Functions->UpdateStatus('update','cursos', 'idcursos', '0', $cursoid, null); // actualizamos la tabla cursos
		$Functions->UpdateStatus('update','lecciones', 'cursos_idcursos', '0', $cursoid, null); // actualizamos la tabla lecciones
		$_SESSION['alert_cursos'] = 'Curso eliminado correctamente';
		$_SESSION['alert_cursos_type'] = 'alert-success';
		header("LOCATION: ". HK ."/cursos.php");
		exit;
	}
	ob_end_flush();
?>
