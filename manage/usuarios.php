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
    $getid = $Functions->Filter($_GET['userid']);
    $TplClass->SetParam('title', 'Usuarios');
    $TplClass->SetParam('tab_hk', '5');
    $TplClass->SetParam('icon', '<i data-feather="users"></i>');
    $TplClass->SetParam('color', 'bg-gradient-primary-to-secondary-3');
    $TplClass->SetParam('desc', 'Aquí podrás ver y editar a los usuarios registrados');
	//AÑADIMOS LAS SECCIONES GLOBALES
    $TplClass->AddTemplate("manage","head");
    $TplClass->AddTemplate("manage","header");
	//AÑADIMOS LAS SECCIONES ESPECÍFICAS
	$TplClass->AddTemplate("manage","usuarios");
    //AÑADIMOS EL FOOTER
	$TplClass->AddTemplate("manage","footer");
?>