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
		0 => 'nombre',
		1 => 'titulo'
	);
	$sql = "SELECT * FROM `cursos` INNER JOIN categorias ON categorias.idcategorias = cursos.categorias_idcategorias WHERE cursos.status = '1' AND categorias.p_blog != '1'"; 
	$dn = $db->query($sql); 
	$totalData = mysqli_num_rows($dn);
	$totalFiltered = $totalData; 

	$value = $Functions->Filter($requestData['search']['value']);
	$sql = "SELECT * FROM `cursos` INNER JOIN categorias ON categorias.idcategorias = cursos.categorias_idcategorias WHERE 1=1 AND cursos.status = '1' AND categorias.p_blog != '1'";
	if( !empty($value) ) {
		$sql.=" AND nombre LIKE '".$value."%' ";
		$sql.=" OR titulo LIKE '".$value."%' ";
		
	}
	$dn = $db->query($sql); 
	$totalFiltered = mysqli_num_rows($dn);
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$dn = $db->query($sql); 
	
	$data = array();
	while($d = $dn->fetch_array()){ 
        $nestedData = array();
        $nestedData[] = $Functions->Filter(ucwords($d["nombre"]));
		$nestedData[] = $Functions->Filter(ucwords($d["titulo"]));
		if($Functions->Filter($d['nivel'])==1){
			$nivel = "Fácil";
		}elseif($Functions->Filter($d['nivel'])==2){
			$nivel = "Intermedio";
		}else{
			$nivel = "Difícil";
		}
		$nestedData[] = $nivel;
		$nestedData[] = $Functions->Filter(ucfirst($d["lecciones"]));
		$nestedData[] = "
			<a href='".HK."/editar_curso.php?cursoid=".$Functions->Filter($d["idcursos"])."' class='btn btn-datatable btn-icon btn-transparent-dark'><i class='fas fa-edit'></i></a>
			<a class='btn btn-datatable btn-icon btn-transparent-dark' onclick='eliminar(\"JsBasics\",\"¿Seguro que quieres eliminar este curso?\", \"". PATH ."/submits/manage/borrar_curso.php?cursoid=".$Functions->Filter($d["idcursos"])."\")'><i class='fas fa-trash'></i></a>
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