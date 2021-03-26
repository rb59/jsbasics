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
    $TplClass->SetParam('title', 'Cursos');
    $TplClass->SetParam('icon', '<i data-feather="book"></i>');
    $TplClass->SetParam('color', 'bg-gradient-primary-to-secondary-3');
    $TplClass->SetParam('desc', 'Aquí podrás ver, editar y borrar los cursos');
	//AÑADIMOS LAS SECCIONES GLOBALES
    $TplClass->AddTemplate("manage","head");
    $TplClass->AddTemplate("manage","header");
	//AÑADIMOS LAS SECCIONES ESPECÍFICAS
	$TplClass->AddTemplate("manage","cursos");
    //AÑADIMOS EL FOOTER
	$TplClass->AddTemplate("manage","footer");
?>