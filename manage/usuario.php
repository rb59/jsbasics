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
    $result = $Functions->SelectxU2('email',$getid); //verificamos si el usuario existe buscando el email
    if(empty($result) or !$Functions->CheckInt($getid)){
        header("LOCATION: ". HK ."/usuarios.php");
        exit;	  
    }
    // OBTENEMOS NOMBRES Y APELLIDOS
    $_SESSION['user_id_hk'] = $getid;
    $nombres = $Functions->SelectxU2('nombres',$getid); // NOMBRES
    $apellidos = $Functions->SelectxU2('apellidos',$getid); // APELLIDOS
    $TplClass->SetParam('tab_hk', '5');
    $TplClass->SetParam('title', 'Usuario: '.$nombres.' '.$apellidos.'');
    $TplClass->SetParam('icon', '<i data-feather="user"></i>');
    $TplClass->SetParam('color', 'bg-gradient-primary-to-secondary-3');
    $TplClass->SetParam('desc', 'Aquí podrás editar los detalles de la cuenta del usuario');
	//AÑADIMOS LAS SECCIONES GLOBALES
    $TplClass->AddTemplate("manage","head");
    $TplClass->AddTemplate("manage","header");
	//AÑADIMOS LAS SECCIONES ESPECÍFICAS
    $TplClass->AddTemplate("manage","usuario_editar");
    //AÑADIMOS EL FOOTER
	$TplClass->AddTemplate("manage","footer");
?>