<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	ob_start();
	require_once '../../global.php';
    $leccionid = $Functions->Filter($_GET['leccionid']);
	if(!empty($leccionid)){
		$Functions->UpdateStatus('update','lecciones', 'idlecciones', '0', $leccionid, null); // actualizamos la tabla lecciones
		$query = $db->prepare("UPDATE lecciones SET numero = ? WHERE idlecciones = ?");
		$dn = $db->query("SELECT * FROM lecciones WHERE idlecciones = '{$leccionid}' ");
		$d = $dn->fetch_array();
		$dn2 = $db->query("SELECT * FROM lecciones WHERE cursos_idcursos = '{$d['cursos_idcursos']}' 
		AND numero > '{$d['numero']}' AND status = '1' ");
		while($d2 = $dn2->fetch_array()){
			$a = $d2['numero'] - 1;			
			$query->bind_param("ii", $a, $d2['idlecciones']);
			$query->execute();
		}		
		$query->close();
		$dn3 = $db->query("SELECT * FROM cursos WHERE idcursos = '{$d['cursos_idcursos']}' ");
		$d3 = $dn3->fetch_array();	
		$query2 = $db->prepare("UPDATE cursos SET lecciones = ? WHERE idcursos = ?");	
		$b = $d3['lecciones'] - 1;
		$query2->bind_param("ii", $b, $d['cursos_idcursos']);
		$query2->execute();
		$query2->close();
		$_SESSION['alert_lecciones'] = 'Lección eliminada correctamente';
		$_SESSION['alert_lecciones_type'] = 'alert-success';
		header("LOCATION: ". HK ."/lecciones.php");
		exit;
	}
	ob_end_flush();
?>
