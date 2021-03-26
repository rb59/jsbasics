<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	require_once 'class.mysql.php';
	require_once 'class.tpl.php';
	require_once 'class.functions.php';
	$MysqlClass  = new MysqlClass();
	$TplClass    = new TplClass();
	$Functions   = new Functions();
	// STARTING
	$db = $MysqlClass->database();
	
?>