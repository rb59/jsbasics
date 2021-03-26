<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	ob_start();
	require_once '../global.php';
	$Functions->Logged("true");
	$Functions->hk_login();
	$TplClass->SetParam('title', 'Iniciar sesión');
	$TplClass->SetParam('tab', 'hk');
	//AÑADIMOS LAS SECCIONES GLOBALES
	$TplClass->AddTemplate("global","head");
	//AÑADIMOS LAS SECCIONES ESPECÍFICAS
	$TplClass->AddTemplate("manage","index");
    //AÑADIMOS EL FOOTER
	$TplClass->AddTemplate("manage","footer");
?>