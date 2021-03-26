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
    $TplClass->SetParam('title', 'Inicio');
    $TplClass->SetParam('icon', '<i data-feather="home"></i>');
    $TplClass->SetParam('color', 'bg-gradient-primary-to-secondary-3');
    $date = $Functions->Date(date("l - F d, Y"));
    $TplClass->SetParam('desc', $date);
	//AÑADIMOS LAS SECCIONES GLOBALES
    $TplClass->AddTemplate("manage","head");
    $TplClass->AddTemplate("manage","header");
    //AÑADIMOS LAS SECCIONES ESPECÍFICAS
    $TplClass->DivClass("container mt-n10");
    $TplClass->AddTemplate("manage","welcome");
    $TplClass->AddTemplate("manage","estadisticas");
    $TplClass->AddTemplate("manage","ultimos_usuarios_registrados");
    $TplClass->DivClosed();
    //AÑADIMOS EL FOOTER
	$TplClass->AddTemplate("manage","footer");
?>