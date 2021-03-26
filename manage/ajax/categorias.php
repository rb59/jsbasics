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
		0 => 'titulo'
	);
	$sql = "SELECT * FROM `categorias` WHERE status = '1'"; 
	$dn = $db->query($sql); 
	$totalData = mysqli_num_rows($dn);
	$totalFiltered = $totalData; 

	$value = $Functions->Filter($requestData['search']['value']);
	$sql = "SELECT * FROM `categorias` WHERE 1=1 AND status = '1'";
	if( !empty($value) ) {
		$sql.=" AND titulo LIKE '".$value."%' ";
		
	}
	$dn = $db->query($sql); 
	$totalFiltered = mysqli_num_rows($dn);
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$dn = $db->query($sql); 
	
	$data = array();
	while($d = $dn->fetch_array()){
        $nestedData = array();
        $nestedData[] = $Functions->Filter(ucwords($d["titulo"]));
		$nestedData[] = $Functions->Filter($d["descripcion"]);
		$nestedData[] = '<input type="color" value="'.$Functions->Filter(strtolower($d["bg_color"])).'" disabled /><span class="small text-black">'.$Functions->Filter(strtolower($d["bg_color"])).'</span>';
		$nestedData[] = '<div class="box rounded-circle" style="background-color:'.$Functions->Filter(strtolower($d["bg_color"])).'">
		<img src="'.$Functions->Filter($d["img"]).'"></div>';	
		$nestedData[] = "
			<a href='".HK."/editar_categoria.php?categoriaid=".$Functions->Filter($d["idcategorias"])."' class='btn btn-datatable btn-icon btn-transparent-dark'><i class='fas fa-edit'></i></a>
			<a class='btn btn-datatable btn-icon btn-transparent-dark' onclick='eliminar(\"JsBasics\",\"¿Seguro que quieres eliminar esta categoría?\", \"". PATH ."/submits/manage/borrar_categoria.php?categoriaid=".$Functions->Filter($d["idcategorias"])."\")'><i class='fas fa-trash'></i></a>
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