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
    $Functions->LoggedAdmin();
    $Functions->RedireccionarSiNoHayPermiso("".ID_ADM."", "".HK."");
    $TplClass->SetParam('title', 'Cambiar Pin de seguridad');
    $TplClass->SetParam('icon', '<i data-feather="key"></i>');
    $TplClass->SetParam('color', 'bg-gradient-primary-to-secondary-3');
    $TplClass->SetParam('desc', 'Aquí podrás cambiar el pin de un usuario');
	//AÑADIMOS LAS SECCIONES GLOBALES
    $TplClass->AddTemplate("manage","head");
    $TplClass->AddTemplate("manage","header");
	//AÑADIMOS LAS SECCIONES ESPECÍFICAS
	$TplClass->AddTemplate("manage","cambiarpin");
    //AÑADIMOS EL FOOTER
	$TplClass->AddTemplate("manage","footer");
?>