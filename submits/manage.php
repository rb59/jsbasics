<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                                        #|
#|         HKGCMS - Sitio web y sistema de gestión de contenidos.         #|
#|    Copyright © 2020 Daniel Quintero. Todos los derechos reservados.    #|
#|																		  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	ob_start();
	require_once '../global.php';
	if($Functions->hk_login()){
		header("Location: ".HK."/main.php");
		exit;
	}else{
		header("Location: ".HK."/index");
		exit;
	}
	ob_end_flush(); 
?>