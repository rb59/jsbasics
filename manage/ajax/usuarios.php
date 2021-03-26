<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	require_once '../../global.php';
	$requestData = $_REQUEST;
	$columns = array( 
		0 => 'nombres', 
		1 => 'apellidos',
		2 => 'email',
		3 => 'nivel',
		4 => 'fecha_nac',
	);
	$sql = "SELECT * FROM `usuarios_login` INNER JOIN usuarios ON usuarios.usuarios_login_idusuarios_login =  usuarios_login.idusuarios_login  INNER JOIN usuarios_niveles ON usuarios_niveles.idusuarios_niveles = usuarios_login.usuarios_niveles_idusuarios_niveles WHERE usuarios_login.status = '1'"; 
	$dn = $db->query($sql); 
	$totalData = mysqli_num_rows($dn);
	$totalFiltered = $totalData; 

	$value = $Functions->Filter($requestData['search']['value']);
	$sql = "SELECT * FROM `usuarios_login` INNER JOIN usuarios ON usuarios.usuarios_login_idusuarios_login =  usuarios_login.idusuarios_login  INNER JOIN usuarios_niveles ON usuarios_niveles.idusuarios_niveles = usuarios_login.usuarios_niveles_idusuarios_niveles WHERE usuarios_login.status = '1' AND 1=1";
	if( !empty($value) ) {
		$sql.=" AND nombres LIKE '".$value."%' ";
		$sql.=" OR apellidos LIKE '".$value."%' ";
		$sql.=" OR email LIKE '".$value."%' ";
		$sql.=" OR nivel LIKE '".$value."%' ";
		$sql.=" OR fecha_nac LIKE '".$value."%' ";
		
	}
	$dn = $db->query($sql); 
	$totalFiltered = mysqli_num_rows($dn);
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$dn = $db->query($sql); 
	
	$data = array();
	while($d = $dn->fetch_array()){
		if(empty($Functions->Filter($d["fecha_nac"]))){
			$fecha = '
			<div class="tooltip2" data-tooltip="Usuario registrado con facebook">
				<label>N/E <i class="fas fa-info-circle"></i></label>
			</div>
			';
		}else{
			$fecha = ''.$Functions->Filter($d['fecha_nac']).' <p class="small">Edad: '.$Functions->CalcularEdad($d["fecha_nac"]).' años</p>';
		}
        $nestedData = array();
        $nestedData[] = $Functions->Filter(ucwords($d["nombres"]));
		$nestedData[] = $Functions->Filter(ucwords($d["apellidos"]));
		$nestedData[] = $Functions->Filter(strtolower($d["email"]));
		$nestedData[] = $Functions->Filter(ucfirst($d["nivel"]));
		$nestedData[] = $fecha;
		$nestedData[] = '<div class="box rounded-circle">
		<img src="'.$Functions->Filter($d["foto"]).'"></div>';
		$nestedData[] = '

    	<a href="'.HK.'/usuario.php?userid='.$Functions->Filter($d["idusuarios_login"]).'" class="btn btn-datatable btn-icon btn-transparent-dark"><i class="fas fa-edit"></i></a>
		';		
        $data[] = $nestedData;
	} 
	$json_data = array(
		"draw" => intval($requestData['draw']),
		"recordsTotal" => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data" => $data
	);
	echo json_encode($json_data);
?>