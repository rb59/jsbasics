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
    $id = $Functions->Filter($_GET['cursoid']);
    $TplClass->SetParam('tab_hk', '2');
    $TplClass->SetParam('title', 'Editando Curso (ID: '.$id.')');
    $TplClass->SetParam('icon', '<i data-feather="book"></i>');
    $TplClass->SetParam('color', 'bg-gradient-primary-to-secondary-3');
    $TplClass->SetParam('desc', 'Aquí podrás editar el curso (ID: '.$id.') de la página');
	//AÑADIMOS LAS SECCIONES GLOBALES
    $TplClass->AddTemplate("manage","head");
    $TplClass->AddTemplate("manage","header");
	//AÑADIMOS LAS SECCIONES ESPECÍFICAS
	$TplClass->AddTemplate("manage","editar_curso");
    //AÑADIMOS EL FOOTER
	$TplClass->AddTemplate("manage","footer");
?>