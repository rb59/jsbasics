<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|							cd							  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	ob_start();
	require_once '../global.php';
    $Functions->Logged("true");
    $Functions->LoggedAdmin();
    $getid = $Functions->Filter($_GET['leccionid']);
    $TplClass->SetParam('title', 'Editando Lección (ID: '.$getid.')');
    $TplClass->SetParam('tab_hk', '4');
    $TplClass->SetParam('icon', '<i data-feather="book-open"></i>');
    $TplClass->SetParam('color', 'bg-gradient-primary-to-secondary-3');
    $TplClass->SetParam('desc', 'Aquí podrás editar la lección (ID: '.$getid.') de la página');
	//AÑADIMOS LAS SECCIONES GLOBALES
    $TplClass->AddTemplate("manage","head");
    $TplClass->AddTemplate("manage","header");
	//AÑADIMOS LAS SECCIONES ESPECÍFICAS
	$TplClass->AddTemplate("manage","editar_leccion");
    //AÑADIMOS EL FOOTER
	$TplClass->AddTemplate("manage","footer");
?>