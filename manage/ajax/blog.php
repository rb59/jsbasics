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
		0 => 'titulo_blog',
		1 => 'titulo'
	);
	$sql = "SELECT * FROM `blog` INNER JOIN categorias ON categorias.idcategorias = blog.categorias_idcategorias INNER JOIN usuarios ON usuarios.usuarios_login_idusuarios_login = blog.usuarios_login_idusuarios_login WHERE blog.status = '1'"; 
	$dn = $db->query($sql); 
	$totalData = mysqli_num_rows($dn);
	$totalFiltered = $totalData; 

	$value = $Functions->Filter($requestData['search']['value']);
	$sql = "SELECT * FROM `blog` INNER JOIN categorias ON categorias.idcategorias = blog.categorias_idcategorias INNER JOIN usuarios ON usuarios.usuarios_login_idusuarios_login = blog.usuarios_login_idusuarios_login WHERE 1=1 AND blog.status = '1'";
	if( !empty($value) ) {
		$sql.=" AND titulo_blog LIKE '".$value."%' ";
		$sql.=" OR titulo LIKE '".$value."%' ";
		
	}
	$dn = $db->query($sql); 
	$totalFiltered = mysqli_num_rows($dn);
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$dn = $db->query($sql); 
	
	$data = array();
	while($d = $dn->fetch_array()){ 
		$nestedData = array();
		$nestedData[] = $Functions->Filter(ucfirst($d["titulo_blog"]));
		$nestedData[] = $Functions->Filter($d["titulo"]);
		$nestedData[] = $Functions->Date(date("F d, Y", strtotime($Functions->Filter($d['fecha']))));
		$nestedData[] = "".$Functions->Filter($d["nombres"])." ".$Functions->Filter($d["apellidos"])."";
		$nestedData[] = "
			<a href='".HK."/editar_blog.php?blogid=".$Functions->Filter($d["idblog"])."' class='btn btn-datatable btn-icon btn-transparent-dark'><i class='fas fa-edit'></i></a>
			<a class='btn btn-datatable btn-icon btn-transparent-dark' onclick='eliminar(\"JsBasics\",\"¿Seguro que quieres eliminar este blog?\", \"". PATH ."/submits/manage/borrar_blog.php?blogid=".$Functions->Filter($d["idblog"])."\")'><i class='fas fa-trash'></i></a>
		";
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