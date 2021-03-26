<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	class Functions{	
		public function Filter($str) {
			$chars = array("update", "delete", "insert", "truncate", "select", "alter", "script");
			$charsReplace = array("up-da-te", "de-le-te", "in-ser-t", "trun-ca-te", "se-lec-t", "al-ter", "");
			return(str_replace($chars, $charsReplace, $str));
		}

		public function Date($str) {
			$chars = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "January", "February", "March" , "April", "May", "June", "July", "August", "September", "October", "November", "December");
			$charsReplace = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre" , "Diciembre");
			return(str_replace($chars, $charsReplace, $str));
		}
		
		public function PHash($password){
			$password = sha1(md5($password));
			return $password;
		}
		
		public function CheckLogged($a, $b){
			global $db;
			//BUSCAMOS EL USUARIO POR CORREO Y CONTRASEÑA
			$query = $db->prepare("SELECT null FROM usuarios_login WHERE email = ? AND password = ? AND status = '1'");
			$query->bind_param("ss", $a, $b);
			$query->execute();
			$result = $query->get_result();
			$query->close();
			//SI NO, LO BUSCAMOS CON EL FBID
			$query2 = $db->prepare("SELECT null FROM usuarios_login WHERE email = ? AND fbid = ? AND status = '1'");
			$query2->bind_param("ss", $a, $b);
			$query2->execute();
			$result2 = $query2->get_result();
			$query2->close();
			if($result->num_rows > 0 || $result2->num_rows > 0){
				$_SESSION['email'] = $a;
				$_SESSION['password'] = $b;
				return true;
			}else{
				return false;
			}
		}

		public function Logged($a){
			$b = $this->CheckLogged($_SESSION['email'], $_SESSION['password']);
			if($a == "allow"){
				if($b){
					$_SESSION['IS_LOGGED'] = true;		
				}else{
					$_SESSION['IS_LOGGED'] = false;
				}
			}
			elseif($a == "false" AND $b){
				$_SESSION['IS_LOGGED'] = true;		
				header("LOCATION: ". PATH ."/index");
				exit;	

			}elseif($a == "true" AND !$b){
				session_destroy();
				header("LOCATION: ". PATH ."/ingresar/");
				exit;

			}elseif($b){
				$_SESSION['IS_LOGGED'] = true;
			}		
		}

		public function Get($a){
			global $db;
			$query = $db->prepare("SELECT {$a} FROM `usuarios_login` INNER JOIN usuarios ON usuarios.usuarios_login_idusuarios_login =  usuarios_login.idusuarios_login WHERE usuarios_login.email = ? LIMIT 1");
			$query->bind_param("s", $_SESSION['email']);
			$query->execute();
			$data = $query->get_result()->fetch_array();
			$query->close();
			return $data[$a];
		}

		// CADENAS CON SOLO LETRAS
		public function CheckText($a) {
			return preg_match("/^[\p{L}\s]+$/iu", $a );
		}
		// CADENAS CON LETRAS Y NÚMEROS
		public function CheckTextAndNumber($a) {
			return preg_match("/^[0-9\p{L}\s]+$/iu", $a );
		}

		// CADENAS CON SOLO NÚMEROS
		public function CheckInt($a) {
			return preg_match("/^[0-9]+$/", $a );
		}
		
	   //FUNCIÓN PARA VERIFICAR EN LA BASE DE DATOS SI EXISTE EL CORREO
		public function ComprobarCorreo($a){
			global $db;
			$query = $db->prepare("SELECT * FROM usuarios_login WHERE email = ? and status = '1' LIMIT 1");
			$query->bind_param("s", $a);
			$a = strtolower($a);
			$query->execute();
			$result = $query->get_result();
			$query->close();
			if($result->num_rows > 0){
				return true;
			}else{
				return false;
			}
		}

		//VALIDACIÓN DE CORREO
		public function ValidarCorreo($a, $b){
			$a = $this->Filter($a);
			$email_check = preg_match("/^[a-z0-9_\.-]+@([a-z0-9]+([\-]+[a-z0-9]+)*\.)+[a-z]{2,7}$/i", $a);
			if(strlen($a) > 45){ 
				$error = 'El campo: "Correo" debe contener máximo 45 caracteres'; 
				$this->ARClass("error",$b);
			}elseif($email_check !== 1){
				$error = "Inserta un correo válido.";
				$this->ARClass("error",$b);
			}elseif($this->ComprobarCorreo($a)){
				$error = "El correo introducido ya está registrado.";
				$this->ARClass("error",$b);
			}else{
				$this->ARClass("success",$b);		
			}
			return $error;
		}

		//VALIDACIÓN DE CLAVE
		public function ValidarPassword($a, $b){
			$a = $this->filter($a);
			if(strlen($a) < 6 || strlen($a) > 20){ 
				$error = 'El campo: "Contraseña" debe contener mínimo 6 caracteres y máximo 20 caracteres'; 
				$this->ARClass("error",$b);
			}else{
				$this->ARClass("success",$b);		
			}
			return $error;
		}

		//VALIDACIÓN DE CAMPO DE TEXTO PARA QUE SOLO CONTENGA LETRAS
		public function ValidarCampoTexto($a, $b, $c){
			if(strlen($a) < 2 || strlen($a) > 45){ 
				$error = 'El campo: "'.$b.'" debe contener mínimo 2 caracteres y máximo 45 caracteres'; 
				$this->ARClass("error",$c);
			}elseif(!$this->CheckText($a)){
				$error = 'El campo: "'.$b.'" solo debe contener letras'; 
				$this->ARClass("error",$c);
			}else{
				$this->ARClass("success",$c);		
			}
			return $error;
		}
		//VALIDACIÓN DE CAMPO DE TEXTO PARA QUE SOLO CONTENGA LETRAS y NÚMEROS
		public function ValidarCampoTexto2($a, $b,$c){
			if(strlen($a) < 2 || strlen($a) > 45){ 
				$error = 'El campo: "'.$b.'" debe contener mínimo 2 caracteres y máximo 45 caracteres'; 
				$this->ARClass("error",$c); 
			}elseif(!$this->CheckTextAndNumber($a)){
				$error = 'El campo: "'.$b.'" solo debe contener letras y números'; 
				$this->ARClass("error",$c);
			}else{
				$this->ARClass("success",$c);		
			}
			return $error;
		}


		public function ARClass($a, $b){
			if($a=="error"){
				echo'
					<script id="script">
						$("'.$b.'").addClass("input-error");
						$("#script").remove();
					</script>
				';				
			}elseif($a=="success"){
				echo'
					<script id="script">
						$("'.$b.'").removeClass("input-error");
						$("#script").remove();
					</script>
				';				
			}
			return;
		}
		
		//FUNCIÓN PARA CALCULAR LA EDAD 
		public function CalcularEdad($e){
			$fechainicial = new DateTime($e);
			$fechafinal   = new DateTime();
			$diferencia = $fechainicial->diff($fechafinal);
			$edad = (($diferencia->y * 12) + $diferencia->m)/12;
			return (int)$edad;
		}

		//FUNCIÓN PARA VALIDAR SI LA FECHA ES REAL
		function validar_si_fecha_es_real($fecha){
			$valores = explode('-', $fecha);
			if(count($valores) == 3 && checkdate($valores[1], $valores[2], $valores[0])){
				return true;
			}
			return false;
		}

		//FUNCIÓN PARA VALIDAR FECHA Y QUE SEA MAYOR A 12 AÑOS DE EDAD
		public function ValidarFecha($a, $b){
			$a = $this->Filter($a);
			if(!$this->validar_si_fecha_es_real($a)){
				$error = "Inserta una fecha de nacimiento válida.";
				$this->ARClass("error",$b);
			}elseif($this->CalcularEdad($a) <= 12){
				$error = "Inserta una fecha de nacimiento válida. Debes tener más de 12 años.";
				$this->ARClass("error",$b);
			}elseif($this->CalcularEdad($a) > 100){
				$error = "Inserta una fecha de nacimiento válida. Debes tener menos de 100 años.";
				$this->ARClass("error",$b);	
			}else{
				$this->ARClass("success",$b);		
			}
			return $error;
		}
	
		// REGISTER SUBMIT
		public function Register() {
			if(isset($_POST['nombres']) && isset($_POST['apellidos']) && isset($_POST['birthday']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['pregunta_1']) && isset($_POST['pregunta_2']) && isset($_POST['pregunta_3']) && isset($_POST['pregunta_4'])){
				$nombres = $this->Filter($_POST['nombres']);
				$apellidos = $this->Filter($_POST['apellidos']);
				$birthday = $this->Filter($_POST['birthday']);
				$email = strtolower($this->Filter($_POST['email']));
				$password = $this->Filter($_POST['password']);
				$pregunta_1 = $this->Filter($_POST['pregunta_1']);
				$pregunta_2 = $this->Filter($_POST['pregunta_2']);
				$pregunta_3 = $this->Filter($_POST['pregunta_3']);
				$pregunta_4 = $this->Filter($_POST['pregunta_4']);
				$respuesta_1 = strtolower($this->Filter($_POST['respuesta_1']));
				$respuesta_2 = strtolower($this->Filter($_POST['respuesta_2']));
				$respuesta_3 = strtolower($this->Filter($_POST['respuesta_3']));
				$respuesta_4 = strtolower($this->Filter($_POST['respuesta_4']));
				$foto = FOTO_PERFIL;
				$fbid = NULL;
				if(empty($nombres) || empty($apellidos) || empty($birthday) || empty($email) || empty($password) || empty($password) || $pregunta_1<=0 || $pregunta_2<=0 || $pregunta_3<=0 || $pregunta_4<=0 || empty($respuesta_1) || empty($respuesta_2) || empty($respuesta_3) || empty($respuesta_4)){
					$this->Alert("error", "Por favor rellene todos los campos");
				}else{
					$ValidarNombres = $this->ValidarCampoTexto($nombres,"Nombres","#nombres");
					$ValidarApellidos = $this->ValidarCampoTexto($apellidos,"Apellidos","#apellidos");
					$ValidarFecha = $this->ValidarFecha($birthday, "#birthday");
					$ValidarCorreo = $this->ValidarCorreo($email, "#email");
					$ValidarPassword = $this->ValidarPassword($password, "#password");
					$ValidarRespuesta1 = $this->ValidarCampoTexto2($respuesta_1,"Respuesta #1","#respuesta_1");
					$ValidarRespuesta2 = $this->ValidarCampoTexto2($respuesta_2,"Respuesta #2","#respuesta_2");
					$ValidarRespuesta3 = $this->ValidarCampoTexto2($respuesta_3,"Respuesta #3","#respuesta_3");
					$ValidarRespuesta4 = $this->ValidarCampoTexto2($respuesta_4,"Respuesta #4","#respuesta_4");
					if(empty($ValidarNombres) && empty($ValidarApellidos) && empty($ValidarFecha) && empty($ValidarCorreo) && empty($ValidarPassword) && empty($ValidarRespuesta1) && empty($ValidarRespuesta2) && empty($ValidarRespuesta3) && empty($ValidarRespuesta4)){	
						//CONTRASEÑA ENCRIPTADA
						$npassword = $this->PHash($password);
						//COVERTIMOS TODAS LAS PREGUNTAS Y RESPUESTAS EN UN ARREGLO
						$pregunta = array($pregunta_1, $pregunta_2, $pregunta_3, $pregunta_4);
						$respuesta = array($respuesta_1, $respuesta_2, $respuesta_3, $respuesta_4);
						//ENVIAMOS LOS DATOS A LA FUNCION DE AGREGAR USUARIOS
						$this->AgregarUsuario($nombres, $apellidos, $birthday, $foto, $email, $npassword, $fbid);
						//ENVIAMOS LAS PREGUNTAS A LA FUNCION DE AGREGAR PREGUNTAS
						$userid = $this->SelectxU('idusuarios_login',$email);
						$this->AgregarPreguntas($pregunta, $respuesta, $userid);	
						//LUEGO DE INSERTAR LOS DATOS INICIAMOS LA SESIÓN
						$_SESSION['email'] = $email;
						$_SESSION['password'] = $npassword;
						//POR ULTIMO REDIRECCIONAMOS AL USUARIO A LA PAGINA INICIAL
						echo "
							<script type=\"text/javascript\">
								location.href = '". PATH ."/index';
							</script>
						";
						return true;
					}else{
						if(!empty($ValidarNombres)){ $error1 = "<li>". $ValidarNombres ."</li>";}
						if(!empty($ValidarApellidos)){ $error2 = "<li>". $ValidarApellidos ."</li>"; }
						if(!empty($ValidarFecha)){ $error3 = "<li>". $ValidarFecha ."</li>"; }
						if(!empty($ValidarCorreo)){ $error4 = "<li>". $ValidarCorreo ."</li>"; }
						if(!empty($ValidarPassword)){ $error5 = "<li>". $ValidarPassword ."</li>"; }
						if(!empty($ValidarRespuesta1)){ $error6 = "<li>". $ValidarRespuesta1 ."</li>"; }
						if(!empty($ValidarRespuesta2)){ $error7 = "<li>". $ValidarRespuesta2 ."</li>"; }
						if(!empty($ValidarRespuesta3)){ $error8 = "<li>". $ValidarRespuesta3 ."</li>"; }
						if(!empty($ValidarRespuesta4)){ $error9 = "<li>". $ValidarRespuesta4 ."</li>"; }
						$error = $error1 . $error2 . $error3 . $error4 . $error5 . $error6 . $error7 . $error8 . $error9;
						$this->Alert("error", $error);
					}
				}
			}
			return false;
			
		}
	
		public function AgregarUsuario($nombres, $apellidos, $fecha_nac, $foto, $email, $password, $facebook_id){
			global $db;
			//INSERTAMOS LOS DATOS DE ACCESO DEL USUARIO
			$stmt = $db->prepare("INSERT INTO usuarios_login (email, password, fbid) VALUES (?, ?, ?)");
			$stmt->bind_param("sss", $email, $password, $facebook_id);
			$stmt->execute();
			$stmt->close();
			//INSERTAMOS LA INFORMACIÓN PERSONAL DEL USUARIO
			$stmt2 = $db->prepare("INSERT INTO usuarios (nombres,apellidos,fecha_nac,foto,usuarios_login_idusuarios_login) VALUES (?, ?, ?, ?, ?)");
			$stmt2->bind_param("ssssi", $nombres, $apellidos, $fecha_nac, $foto, $this->SelectxU('idusuarios_login',$email));
			$stmt2->execute();
			$stmt2->close();
			return;
		}
		public function AgregarPreguntas($pregunta, $respuesta, $userid){
			global $db;
			// INSERTAMOS EN LA BASE DE DATOS LAS PREGUNTAS DE SEGURIDAD DEL USUARIO
			$stmt = $db->prepare("INSERT INTO usuarios_preguntas (respuesta,preguntas_idpreguntas,usuarios_login_idusuarios_login) VALUES (?, ?, ?)");
			$stmt->bind_param("sii", $a, $b, $c);
			for($i=0; $i<=3; $i++){
				$a = $this->PHash($respuesta[$i]);
				$b = $pregunta[$i];
				$c = $userid;
				$stmt->execute();
			}
			$stmt->close();
		}
		
		public function Login(){
			global $db;
			if(isset($_POST['email']) && isset($_POST['password'])){
				$email = $this->Filter($_POST['email']);
				$password = $_POST['password'];
				$result = $this->SelectxU('idusuarios_login',$email);
				if(empty($email)  || empty($password)){
					$this->Alert("error", "Por favor rellene todos los campos");
				}elseif($result <= 0){
					$this->Alert("warning", "La cuenta no existe o fue eliminada");
				}elseif($this->CheckLogged($email, $this->PHash($password)) ){
					echo "
						<script type=\"text/javascript\">
							location.href = '". PATH ."/index';
						</script>
					";
					exit;
				}else{
					$this->Alert("error", "Correo o contraseña incorrectos");
					$this->ARClass("error","#email");
					$this->ARClass("error","#password");
				}
			}
		}

		public function GetCount($a, $TableName, $AttributeName, $WhereText ){
			global $db;
			if($a == "count"){
				$c = $db->query("SELECT COUNT(*) AS count FROM `".$TableName."` WHERE status = '1' ");
			}elseif($a == "count-where"){
				$c = $db->query("SELECT COUNT(*) AS count FROM `".$TableName."` WHERE status = '1' AND `".$AttributeName."` = '".$WhereText."'");
			}
				$d = $c->fetch_array();
				return $d['count'];
		}

		public function PasswordCheck($a, $b){
			$a = $this->filter($a); // password
			$b = $this->filter($b); // password_confirm
			if(strlen($a) < 6 || strlen($a) > 20){ 
				$error = 'La contraseña debe contener mínimo 6 caracteres y máximo 20 caracteres'; 
			}elseif($a !== $b){
				$error = "Las contraseñas no coinciden";
			}
			return $error;
		}

		public function ActualizarPerfil($userid, $panel, $ruta){
			global $db;
			$nombres = $this->Filter($_POST['nombres']);
			$apellidos = $this->Filter($_POST['apellidos']);
			$fecha_nac = $this->Filter($_POST['fecha_nac']);
			$twitter = $this->Filter($_POST['twitter']);
			$instagram = $this->Filter($_POST['instagram']);
			$facebook = $this->Filter($_POST['facebook']);
			$current_email = $this->Filter($this->SelectxU2('email',$userid)); // CORREO ACTUAL 
			if($this->Filter($this->SelectxU2('fbid',$userid)) > 0){
				$email = $current_email;
			}else{
				$email = $this->Filter($_POST['email']);
			}
			if( empty($nombres) || empty($apellidos) || empty($email) || empty($fecha_nac)){
				$_SESSION['alert_profile'] = 'Por favor rellena todos los campos obligatorios';
				$_SESSION['alert_profile_type'] = 'alert-danger';
			}else{
				$ValidarNombres = $this->ValidarCampoTexto($nombres,"Nombres","#nombres");
				$ValidarApellidos = $this->ValidarCampoTexto($apellidos,"Apellidos","#apellidos");
				$ValidarFecha = $this->ValidarFecha($fecha_nac, "#fecha_nac");
				$ValidarCorreo = $this->ValidarCorreo($email, "#email");
				if($email==$current_email){
					$ValidarCorreo = "";
				}else{
					$ValidarCorreo = $this->ValidarCorreo($email, "#email");
				}	
				if(empty($ValidarNombres) && empty($ValidarApellidos) && empty($ValidarFecha) && empty($ValidarCorreo)){
					$query = $db->prepare("UPDATE usuarios_login INNER JOIN usuarios ON usuarios.usuarios_login_idusuarios_login =  usuarios_login.idusuarios_login  SET 
					usuarios_login.email = ?,
					usuarios.nombres = ?,
					usuarios.apellidos = ?,
					usuarios.fecha_nac = ?,
					usuarios.facebook = ?,
					usuarios.twitter = ?,
					usuarios.instagram = ?
					WHERE usuarios_login.idusuarios_login = ? ");
					$query->bind_param("sssssssi", $email, $nombres, $apellidos, $fecha_nac, $facebook, $twitter, $instagram, $userid);
					$query->execute();
					$query->close();
					if($_SESSION['email'] == $current_email){
						$_SESSION['email'] = $email; // ACTUALIZAMOS LA SESION DEL EMAIL POR SI HAY CAMBIOS :)
					}
					$_SESSION['alert_profile'] = 'Perfil actualizado correctamente';
					$_SESSION['alert_profile_type'] = 'alert-success';
				}else{
					if(!empty($ValidarNombres)){ $error1 = "<li>". $ValidarNombres ."</li>"; }
					if(!empty($ValidarApellidos)){ $error2 = "<li>". $ValidarApellidos ."</li>"; }
					if(!empty($ValidarFecha)){ $error3 = "<li>". $ValidarFecha ."</li>"; }
					if(!empty($ValidarCorreo)){ $error4 = "<li>". $ValidarCorreo ."</li>"; }
					$error = $error1 . $error2 . $error3 . $error4;
					$_SESSION['alert_profile'] = ''.$error.'';
					$_SESSION['alert_profile_type'] = 'alert-danger';
				}
			}
			header("LOCATION: ". $ruta ."");
			exit;
			
		} 

		public function ActualizarPassword(){
			global $db;
			$new_password = $this->Filter($_POST['new_password']);
			$new_password_confirm = $this->Filter($_POST['new_password_confirm']);
			//SI EL USUARIO SE REGISTRO CON FACEBOOK NO TIENE CONTRASEÑA
			if( empty($this->Filter($this->Get('password'))) ){
				$DB_current_password = $this->PHash($this->Filter($this->Get('fbid')));
				$current_password = $this->Filter($this->Get('fbid'));
			//SI EL USUARIO NO SE SE REGISTRO CON FB SI TIENE CONTRASEÑA
			}else{
				$DB_current_password = $this->Filter($this->Get('password')); // LA CONTRASEÑA QUE TIENEN GUARDADA EN LA BASE DE DATOS
				$current_password = $this->Filter($_POST['current_password']);
			}
			$orpassword = $this->PHash($current_password);
			$newpassword = $this->PHash($new_password);
			//VALIDACIONES
			if( empty($current_password) || empty($new_password)  || empty($new_password_confirm)){
				$_SESSION['alert'] = 'Por favor rellena todos los campos';
				$_SESSION['alert_type'] = 'alert-danger';
			}elseif($orpassword !== $DB_current_password){
				$_SESSION['alert'] = 'Tu contraseña actual no coincide';
				$_SESSION['alert_type'] = 'alert-danger';
			}elseif($orpassword == $newpassword){
				$_SESSION['alert'] = 'Tu nueva contraseña no puede ser igual a tu contraseña actual';
				$_SESSION['alert_type'] = 'alert-danger';
			}else{
				$Password_Checked = $this->PasswordCheck($new_password, $new_password_confirm);
				if(empty($Password_Checked)){
					$query = $db->prepare("UPDATE usuarios_login SET password = ? WHERE idusuarios_login = ? LIMIT 1");
					$query->bind_param("si", $newpassword, $userid);
					$userid = $this->Filter($this->Get('idusuarios_login'));
					$query->execute();
					$query->close();
					$_SESSION['password'] = $newpassword;		
					$_SESSION['alert'] = 'Contraseña actualizada correctamente';
					$_SESSION['alert_type'] = 'alert-success';
				}else{
				if(!empty($Password_Checked)){ $error1 = "". $Password_Checked ."<br>"; }
					$error = $error1;
					$_SESSION['alert'] = ''.$error.'';
					$_SESSION['alert_type'] = 'alert-danger';
				}
			}
			header("LOCATION: ". PATH ."/perfil/seguridad");
			exit;
		} 
		
		public function ActualizarPreguntas(){
			global $db;
			if(isset($_POST['pregunta_1']) && isset($_POST['pregunta_2']) && isset($_POST['pregunta_3']) && isset($_POST['pregunta_4'])){
				$pregunta_1 = $this->Filter($_POST['pregunta_1']);
				$pregunta_2 = $this->Filter($_POST['pregunta_2']);
				$pregunta_3 = $this->Filter($_POST['pregunta_3']);
				$pregunta_4 = $this->Filter($_POST['pregunta_4']);
				$respuesta_1 = $this->Filter($_POST['respuesta_1']);
				$respuesta_2 = $this->Filter($_POST['respuesta_2']);
				$respuesta_3 = $this->Filter($_POST['respuesta_3']);
				$respuesta_4 = $this->Filter($_POST['respuesta_4']);	
				if($pregunta_1<=0 || $pregunta_2<=0 || $pregunta_3<=0 || $pregunta_4<=0 || empty($respuesta_1) || empty($respuesta_2) || empty($respuesta_3) || empty($respuesta_4)){
					$this->Alert("error", "Por favor rellene todos los campos");
				}else{
					$ValidarRespuesta1 = $this->ValidarCampoTexto2($respuesta_1,"Respuesta #1","#respuesta_1");
					$ValidarRespuesta2 = $this->ValidarCampoTexto2($respuesta_2,"Respuesta #2","#respuesta_2");
					$ValidarRespuesta3 = $this->ValidarCampoTexto2($respuesta_3,"Respuesta #3","#respuesta_3");
					$ValidarRespuesta4 = $this->ValidarCampoTexto2($respuesta_4,"Respuesta #4","#respuesta_4");
					if(empty($ValidarRespuesta1) && empty($ValidarRespuesta2) && empty($ValidarRespuesta3) && empty($ValidarRespuesta4)){	
						//COVERTIMOS TODAS LAS PREGUNTAS Y RESPUESTAS EN UN ARREGLO
						$pregunta = array($pregunta_1, $pregunta_2, $pregunta_3, $pregunta_4);
						$respuesta = array($respuesta_1, $respuesta_2, $respuesta_3, $respuesta_4);
						//HACEMOS UN BORRADO LOGICO DE LAS PREGUNTAS ANTERIORES	
						$userid = $this->Filter($this->Get('idusuarios_login'));
						$this->UpdateStatus('update','usuarios_preguntas', 'usuarios_login_idusuarios_login', '0', $userid, null);
						//ENVIAMOS LAS PREGUNTAS A LA FUNCION DE AGREGAR PREGUNTAS
						$this->AgregarPreguntas($pregunta, $respuesta, $userid);
						// MENSAJE
						$_SESSION['alert'] = 'Preguntas de seguridad actualizadas correctamente';
						$_SESSION['alert_type'] = 'alert-success';
						echo "
						<script type=\"text/javascript\">
							location.href = '". PATH ."/perfil/preguntas';
						</script>
						";
					}else{
						if(!empty($ValidarRespuesta1)){ $error1 = "<li>". $ValidarRespuesta1 ."</li>"; }
						if(!empty($ValidarRespuesta2)){ $error2 = "<li>". $ValidarRespuesta2 ."</li>"; }
						if(!empty($ValidarRespuesta3)){ $error3 = "<li>". $ValidarRespuesta3 ."</li>"; }
						if(!empty($ValidarRespuesta4)){ $error4 = "<li>". $ValidarRespuesta4 ."</li>"; }
						$error = $error1 . $error2 . $error3 . $error4;
						$this->Alert("error", $error);
					}
				}
			}
		}

		public function UpdateStatus($a, $TableName, $AttributeName, $status, $WhereText, $limit){
			global $db;
			if($a == "update"){
				$query = $db->prepare("UPDATE `".$TableName."` SET status = '{$status}' WHERE `".$AttributeName."` = ?");
			}elseif($a == "update-limit"){
				$query = $db->prepare("UPDATE `".$TableName."` SET status = '{$status}' WHERE `".$AttributeName."` = ? LIMIT $limit");
			}
			$query->bind_param("i", $WhereText);
			$query->execute();
			$query->close();
			return;
		}

		public function Alert($a, $b){
			if($a=="error"){
				echo '
					<div class="alert alert-danger mt-2">
						'. $b .'
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>';
			}elseif($a=="warning"){
				echo '
					<div class="alert alert-warning mt-2">
						'. $b .'
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>			
					</div>';
			}
			return;
		}

		public function SelectxU($a, $b){
			global $db;
			$query = $db->prepare("SELECT {$a} FROM usuarios_login WHERE email = ? AND status = '1' LIMIT 1");
			$query->bind_param("s", $b);
			$query->execute();
			$data = $query->get_result()->fetch_array();
			$query->close();
			return $data[$a];
		}


		public function SelectxU2($a, $b){
			global $db;
			$query = $db->prepare("SELECT {$a} FROM usuarios_login INNER JOIN usuarios ON usuarios.usuarios_login_idusuarios_login =  usuarios_login.idusuarios_login WHERE usuarios_login.idusuarios_login = ? AND usuarios_login.status = '1' LIMIT 1");
			$query->bind_param("s", $b);
			$query->execute();
			$data = $query->get_result()->fetch_array();
			$query->close();
			return $data[$a];
		}

// NUEVAS FUNCIONESSSSSSSSSSSSSSSSSSSSSSSSSSS
		public function Redireccionar($ruta){
			if("http://". $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] == $ruta){ 
				header("LOCATION: ". PATH ."/index");
				exit;	  
			}
			return;
		}
		public function ActualizarFotoPerfil($userid, $ruta){
			global $db;
			if(isset($_FILES['uploadImage']['name'])){
				$maxsize    = 5242880; // TAMAÑO MÁXIMO DEL ARCHIVO (5MB)
				$acceptable = array('image/jpeg','image/jpg','image/png'); // SOLO SE PERMITEN JPEG JPG Y PNG
				$filename = $_FILES['uploadImage']['name']; //nombre del archivo
				$filesize = $_FILES['uploadImage']['size']; // tamaño de imagen
				$filetype = $_FILES['uploadImage']['type']; // tipo de imagen
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$nombre_archivo = time();
				$nombre_real = $nombre_archivo.".".$ext;
				if( empty($filename)){
					$_SESSION['alert_uploadImage'] = 'Por favor seleccione una imagen';
					$_SESSION['alert_uploadImage_type'] = 'alert-danger';
				}elseif(($filesize >= $maxsize) || ($filesize == 0)) {
						$_SESSION['alert_uploadImage'] = 'Imagen demasiada grande. La imagen debe pesar menos de 5MB.';
						$_SESSION['alert_uploadImage_type'] = 'alert-danger';
				}elseif((!in_array($filetype, $acceptable)) && (!empty($filetype))) {
						$_SESSION['alert_uploadImage'] = 'Imagen no permitida. Solo se permiten imagenes JPG, PNG y JPEG.';
						$_SESSION['alert_uploadImage_type'] = 'alert-danger';
				}else{
					//$url = $_SERVER['DOCUMENT_ROOT'].'/resources/img/profiles/'.$nombre_real; // LA RUTA DE LA CARPETA SERVIDOR
					$url = '../../resources/img/profiles/'.$nombre_real; // LA RUTA DE LA CARPETA LOCALHOST
					if (move_uploaded_file($_FILES['uploadImage']['tmp_name'], $url)) {
						$url2 = ''.PATHI.'/img/profiles/'.$nombre_real.'';
						$query = $db->prepare("UPDATE usuarios SET foto = ? WHERE usuarios_login_idusuarios_login = ? LIMIT 1");
						$query->bind_param("si", $url2, $userid);
						$query->execute();
						$query->close();
						$_SESSION['alert_uploadImage'] = 'Foto de perfil actualizada correctamente';
						$_SESSION['alert_uploadImage_type'] = 'alert-success';
					}else{
						//Si no se ha podido subir la imagen, mostramos un mensaje de error
						$_SESSION['alert_uploadImage'] = 'Ocurrió un error al subir la imagen';
						$_SESSION['alert_uploadImage_type'] = 'alert-danger';
					}			
				}
			}		
			header("LOCATION: ". $ruta ."");
			exit;
		} 

		public function PasswordRecovery1(){
			global $db;
			if(isset($_POST['email'])){
				$email = $this->Filter($_POST['email']);	
				if(empty($email)){
					$this->Alert("error", "Por favor rellene todos los campos");
				}elseif( $this->SelectxU('idusuarios_login',$email)){ 
					$_SESSION['user_lost_email'] = $email;
					$url = ''.PATH.'/templates/password-recovery/step2.php'; // LA RUTA DEL SIGUIENTE PASO 
					echo'
						<script id="script">
							$("#step").load("'.$url.'");
						</script>
					';
				}else{
					$this->Alert("error", "Dirección de correo electrónico no encontrada");
				}
			}
			return;
		}


		public function PasswordRecovery2(){
			global $db;
			$respuesta_1 = strtolower($this->Filter($_POST['respuesta_1']));
			$respuesta_2 = strtolower($this->Filter($_POST['respuesta_2']));
			$respuesta_3 = strtolower($this->Filter($_POST['respuesta_3']));
			$respuesta_4 = strtolower($this->Filter($_POST['respuesta_4']));
			if(empty($respuesta_1) || empty($respuesta_2) || empty($respuesta_3) || empty($respuesta_4)){
				$this->Alert("error", "Por favor rellene todos los campos");
			}else{
				$preguntas = array($this->PHash($respuesta_1), $this->PHash($respuesta_2),$this->PHash( $respuesta_3), $this->PHash($respuesta_4));
				//CONSULTA A LA BASE DE DATOS
				$query = $db->prepare("SELECT * FROM usuarios_preguntas INNER JOIN preguntas ON preguntas.idpreguntas =  usuarios_preguntas.preguntas_idpreguntas  WHERE usuarios_preguntas.usuarios_login_idusuarios_login = ? AND usuarios_preguntas.status = '1'");
				$query->bind_param("i", $userid);
				$userid = $this->SelectxU('idusuarios_login',$_SESSION['user_lost_email']);
				$query->execute();
				$result = $query->get_result();
				$query->close();	
				$x = 0;
				while($row = $result->fetch_array()){
					$respuestas[$x] = $row['respuesta'];
					$x++;
				}
				if($preguntas[0]==$respuestas[0] && $preguntas[1]==$respuestas[1] && $preguntas[2]==$respuestas[2] &&$preguntas[3]==$respuestas[3]){
					$url = ''.PATH.'/templates/password-recovery/step3.php'; // LA RUTA DEL SIGUIENTE PASO
					echo'
						<script id="script">
							$("#step").load("'.$url.'");
						</script>
					';
				}else{ 
					$this->Alert("error", "Los datos introducidos no son correctos");
				}			
			}
		}

		public function PasswordRecovery3(){
			global $db;
			$new_password = $this->Filter($_POST['new_password']);
			$new_password_confirm = $this->Filter($_POST['new_password_confirm']);
			$newpassword = $this->PHash($new_password);
			$orpassword = $this->SelectxU('password',$_SESSION['user_lost_email']);
			if(empty($new_password) || empty($new_password_confirm)){
				$this->Alert("error", "Por favor rellene todos los campos");
			}elseif($orpassword == $newpassword){
				$this->Alert("error", "Tu nueva contraseña no puede ser igual a tu contraseña actual");
			}else{
				$Password_Checked = $this->PasswordCheck($new_password, $new_password_confirm);
				if(empty($Password_Checked)){
					$query = $db->prepare("UPDATE usuarios_login SET password = ? WHERE idusuarios_login = ? LIMIT 1");
					$query->bind_param("si", $newpassword, $userid);
					$userid = $this->SelectxU('idusuarios_login',$_SESSION['user_lost_email']);
					$query->execute();
					$query->close();
					$email = $_SESSION['user_lost_email'];
					$_SESSION['email'] = $email;		
					$_SESSION['password'] = $newpassword;		
					echo "
					<script type=\"text/javascript\">
						location.href = '". PATH ."/index';
					</script>
					";
				}else{
				if(!empty($Password_Checked)){ $error1 = "". $Password_Checked ."<br>"; }
					$error = $error1;
					$this->Alert("error", $error);
				}
			}
		}

		public function hk_login(){
			$pin = $this->Filter($_POST['security_pin']);
			if($this->Get("usuarios_niveles_idusuarios_niveles") >= NIVEL_MINIMO_PANEL){
				if($_SESSION['HK_CHECKED'] == true){
					header("LOCATION: ". HK ."/main.php");
					return true;
				}elseif(isset($_POST['security_pin'])){
					if(empty($pin)){
					$_SESSION['alert_hk'] = "Por favor coloque su PIN de seguridad";
					return false;
				}
				elseif($this->PHash($pin) == $this->Get("pin")){
					$_SESSION['HK_CHECKED'] = true;
					return true;
				}else{
					$_SESSION['alert_hk'] = "PIN incorrecto";
					return false;
				}
				}else{
					return false;
				}
			}else{
				header("LOCATION: ". PATH ."/index");
				return false;
			}
		}

		public function LoggedAdmin(){	
			if($this->Get('usuarios_niveles_idusuarios_niveles') >= NIVEL_MINIMO_PANEL){
				if($_SESSION['HK_CHECKED'] == true){
					return true;
				}else{
					header("LOCATION: ". HK ."/index");
					return false;
				}
			}else{
				header("LOCATION: ". HK ."/index");
				return false;
			}
		}

		public function UpdateNivelUsuario(){
			global $db;
			if( isset($_POST['email'])  && isset($_POST['niveles_usuario']) ){
				$email = $this->Filter($_POST['email']);
				$niveles_usuario = $this->Filter($_POST['niveles_usuario']);	
				$result = $this->SelectxU('idusuarios_login',$email); //verificamos si el usuario existe buscando el id
				$result2 = $this->SelectxU('usuarios_niveles_idusuarios_niveles',$email); //
				if($niveles_usuario<=0 || empty($email) ){
					$_SESSION['alert_niveles'] = 'Por favor rellene todos los campos';
					$_SESSION['alert_niveles_type'] = 'alert-danger';
				}elseif($result <= 0){
					$_SESSION['alert_niveles'] = 'El usuario no existe';
					$_SESSION['alert_niveles_type'] = 'alert-danger';
				}elseif($this->Get('usuarios_niveles_idusuarios_niveles') < $niveles_usuario AND $this->Get('usuarios_niveles_idusuarios_niveles')  != ID_FUNDADOR){
					$_SESSION['alert_niveles'] = 'No puedes dar un nivel de usuario mayor al tuyo';
					$_SESSION['alert_niveles_type'] = 'alert-danger';
				}elseif($niveles_usuario <= $result2 AND $this->Get('usuarios_niveles_idusuarios_niveles')  != ID_FUNDADOR){
					$_SESSION['alert_niveles'] = 'No puedes quitar a otro usuario con tu mismo nivel de acceso';
					$_SESSION['alert_niveles_type'] = 'alert-danger';
				}else{
					$query = $db->prepare("UPDATE usuarios_login SET usuarios_niveles_idusuarios_niveles = ? WHERE idusuarios_login = ? LIMIT 1");
					$query->bind_param("ii", $niveles_usuario, $result);
					$query->execute();
					$query->close();
					$_SESSION['alert_niveles'] = 'Nivel de usuario actualizado correctamente';
					$_SESSION['alert_niveles_type'] = 'alert-success';					
				}
			}	
			header("LOCATION: ". HK ."/cambiarnivel.php");
			exit;	
		}

		public function UpdatePinSeguridad(){
			global $db;
			if( isset($_POST['email'])  && isset($_POST['pin']) ){
				$email = $this->Filter($_POST['email']);
				$pin = $this->Filter($_POST['pin']);	
				$result = $this->SelectxU('idusuarios_login',$email); //verificamos si el usuario existe buscando el id
				$result2 = $this->SelectxU('usuarios_niveles_idusuarios_niveles',$email); 
				if(empty($email) || empty($pin) ){
					$_SESSION['alert_pin'] = 'Por favor rellene todos los campos';
					$_SESSION['alert_pin_type'] = 'alert-danger';
				}elseif($result <= 0){
					$_SESSION['alert_pin'] = 'El usuario no existe';
					$_SESSION['alert_pin_type'] = 'alert-danger';
				}elseif(strlen($pin) != 6 || !$this->CheckInt($pin)){ 
					$_SESSION['alert_pin'] = 'El pin de seguridad solo debe contener números y 6 digitos';
					$_SESSION['alert_pin_type'] = 'alert-danger';
				}elseif($this->Get('usuarios_niveles_idusuarios_niveles') < $result2 ){
					$_SESSION['alert_niveles'] = 'No puedes cambiarle el PIN a un usuario con un nivel de acceso mayor al tuyo';
					$_SESSION['alert_niveles_type'] = 'alert-danger';
				}else{
					$query = $db->prepare("UPDATE usuarios_login SET pin = ? WHERE idusuarios_login = ? LIMIT 1");
					$query->bind_param("si", $this->PHash($pin), $result);
					$query->execute();
					$query->close();
					$_SESSION['alert_pin'] = 'PIN de seguridad actualizado correctamente';
					$_SESSION['alert_pin_type'] = 'alert-success';					
				}
			}	
			header("LOCATION: ". HK ."/cambiarpin.php");
			exit;	
		}

		public function ChangePassPanel(){
			global $db;
			if( isset($_POST['email'])  && isset($_POST['new_password']) && isset($_POST['new_password_confirm'])){
				$email = $this->Filter($_POST['email']);	
				$new_password = $this->Filter($_POST['new_password']);
				$new_password_confirm = $this->Filter($_POST['new_password_confirm']);
				$result = $this->SelectxU('idusuarios_login',$email); //verificamos si el usuario existe buscando el id
				if(empty($email) || empty($new_password)  || empty($new_password_confirm) ){
					$_SESSION['alert_pass'] = 'Por favor rellene todos los campos';
					$_SESSION['alert_pass_type'] = 'alert-danger';
				}elseif($result <= 0){
					$_SESSION['alert_pass'] = 'El usuario no existe';
					$_SESSION['alert_pass_type'] = 'alert-danger';
				}else{
					$Password_Checked = $this->PasswordCheck($new_password, $new_password_confirm);
					if(empty($Password_Checked)){
						$query = $db->prepare("UPDATE usuarios_login SET password = ? WHERE idusuarios_login = ? LIMIT 1");
						$query->bind_param("si", $newpassword, $result);
						$newpassword = $this->PHash($new_password); // encriptamos la contraseña
						$query->execute();
						$query->close();	
						$_SESSION['alert_pass'] = 'Contraseña actualizada correctamente';
						$_SESSION['alert_pass_type'] = 'alert-success';
					}else{
					if(!empty($Password_Checked)){ $error1 = "". $Password_Checked ."<br>"; }
						$error = $error1;
						$_SESSION['alert_pass'] = ''.$error.'';
						$_SESSION['alert_pass_type'] = 'alert-danger';
					}
				}
			}	
			header("LOCATION: ". HK ."/changepass.php");
			exit;	
		}

		public function RedireccionarSiNoHayPermiso($nivel, $ruta){
			if($this->Get('usuarios_niveles_idusuarios_niveles') >= $nivel){ }else{
				header("LOCATION: ". $ruta ."");
				exit;
			}
		}


		########################################################
		#                                                      #
		#                    LOS CURSOS                        #
		#                      CD    cd                        #
		########################################################
		
		public function CheckRegistro($a){
			global $db;
			$q = $db->query("SELECT COUNT(*) AS count FROM registro WHERE cursos_idcursos = '{$a}' 
			AND usuarios_login_idusuarios_login = '{$this->Get("idusuarios_login")}' ");
			$data = $q->fetch_array();
			if($data['count'] > 0){
				return true;
			}else{
				return false;
			}
		}

		public function SelectRegistro($a, $b){
			global $db;
			$q = $db->query("SELECT * FROM registro WHERE cursos_idcursos = '{$a}'
			AND usuarios_login_idusuarios_login = '{$this->Get("idusuarios_login")}' ");
			$data = $q->fetch_array();
			return $data[$b];
		}

		public function LeccionDisponible($a,$b){
			global $db;
			if(!$this->CheckRegistro($b)){
				if($a==1){
					return true;
				}else{
					return false;
				}
			}elseif($this->CheckRegistro($b)){
				$q = $db->query("SELECT COUNT(*) AS count FROM registro_lecciones 
				INNER JOIN lecciones on lecciones.idlecciones = registro_lecciones.lecciones_idlecciones 
				WHERE lecciones.cursos_idcursos = '{$b}' AND lecciones.status = '1'
				AND registro_lecciones.usuarios_login_idusuarios_login = '{$this->Get("idusuarios_login")}' ");
				$data = $q->fetch_array();
				if($data['count'] >= ($a-1) ){
					return true;
				}else{
					return false;
				}
			}
		}


		public function FinalizarLeccion($leccion,$curso,$id){
			global $db;
			$f = date('Y-m-d');
			if(!$this->CheckRegistro($curso)){				
				$this->InscribirCurso($curso,$id);
			}elseif($this->CheckRegistro($curso)){
				$q = $db->query("SELECT * FROM cursos WHERE idcursos = '{$curso}' AND status = '1'");
				$data = $q->fetch_array();				
				if($leccion == $data['lecciones'] ){
					if($this->SelectRegistro($curso,"nivel_progreso") == "En curso" ){
						$query = $db->prepare("UPDATE registro SET  nivel_progreso = ?, fecha_fin = ?  
						WHERE cursos_idcursos = ? AND usuarios_login_idusuarios_login = ? LIMIT 1");						
						$np = "Aprobado";
						$query->bind_param("ssii", $np, $f, $curso, $this->Get("idusuarios_login"));
						$query->execute();
						$query->close();
						$this->RegistrarLeccion($id);
					}
					header("LOCATION: ". PATH ."/curso/".$curso);
					exit;
				}else{
					$q = $db->query("SELECT COUNT(*) AS count FROM registro_lecciones 
					INNER JOIN lecciones on lecciones.idlecciones = registro_lecciones.lecciones_idlecciones 
					WHERE lecciones.cursos_idcursos = '{$curso}' AND lecciones.status = '1'
					AND registro_lecciones.usuarios_login_idusuarios_login = '{$this->Get("idusuarios_login")}' ");
					$data = $q->fetch_array();
					if($leccion > $data['count']){
						$this->RegistrarLeccion($id);
					}
					header("LOCATION: ". PATH ."/curso/".$curso);
					exit;
				}
			}
		}

		public function InscribirCurso($a,$b){
			global $db;
			$query = $db->prepare("INSERT INTO registro (nivel_progreso, fecha_inicio, cursos_idcursos, 
			usuarios_login_idusuarios_login) VALUES (?, ?, ?, ?)");
			$f = date('Y-m-d');
			$na = 1;
			$np = "En curso";
			$query->bind_param("ssii", $np, $f, $a, $this->Get("idusuarios_login"));
			$query->execute();
			$query->close();
			$this->RegistrarLeccion($b);
			header("LOCATION: ". PATH ."/curso/".$a);
			exit;
		}

		public function RegistrarLeccion($a){
			global $db;
			$query = $db->prepare("INSERT INTO registro_lecciones (fecha, lecciones_idlecciones, 
			usuarios_login_idusuarios_login) VALUES (?, ?, ?)");
			$f = date('Y-m-d');
			$query->bind_param("sii", $f, $a, $this->Get("idusuarios_login"));
			$query->execute();
			$query->close();
			return;
		}

		public function AgregarCurso(){
			global $db;
			if(isset($_POST['nombre']) && isset($_POST['desc']) && isset($_POST['categoria']) && isset($_POST['nivel']) && isset($_FILES['uploadImage']['name'])){
				$nombre = $this->Filter($_POST['nombre']);
				$desc = $this->Filter($_POST['desc']);
				$categoria = $this->Filter($_POST['categoria']);
				$nivel = $this->Filter($_POST['nivel']);
				$n = 0;
				$maxsize    = 5242880; // TAMAÑO MÁXIMO DEL ARCHIVO (5MB)
				$acceptable = array('image/jpeg','image/jpg','image/png'); // SOLO SE PERMITEN JPEG JPG Y PNG
				$filename = $_FILES['uploadImage']['name']; //nombre del archivo
				$filesize = $_FILES['uploadImage']['size']; // tamaño de imagen
				$filetype = $_FILES['uploadImage']['type']; // tipo de imagen
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$nombre_archivo = time();
				$nombre_real = $nombre_archivo.".".$ext;
				if(!empty($nombre) && !empty($desc) && !empty($categoria) && !empty($nivel) && !empty($filename) && $categoria > 0 && $nivel > 0){
					if(strlen($nombre) > 4 && strlen($nombre) < 45){
						if(!$this->Existe('cursos','nombre',$nombre,'val')){
							if($filesize <= $maxsize && $filesize > 0){
								if(in_array($filetype, $acceptable) && !empty($filetype)){
									
									//$url = $_SERVER['DOCUMENT_ROOT'].'/resources/img/images2/'.$nombre_real; // LA RUTA DE LA CARPETA SERVIDOR
									$url = '../../resources/img/images2/'.$nombre_real; // LA RUTA DE LA CARPETA LOCALHOST
									if(move_uploaded_file($_FILES['uploadImage']['tmp_name'], $url)){
										
										$url2 = PATHI.'/img/images2/'.$nombre_real;
										$query = $db->prepare("INSERT INTO cursos (nombre, nivel, lecciones, descripcion, img, categorias_idcategorias) VALUES (?, ?, ?, ?, ?, ?)");
										$query->bind_param("siissi", $nombre, $nivel, $n, $desc, $url2, $categoria);
										$query->execute();
										$query->close();
										$_SESSION['alert_cursos'] = 'Curso agregada correctamente';
										$_SESSION['alert_cursos_type'] = 'alert-success';	
										header("LOCATION: ". HK ."/cursos.php");
										exit;
									}else{
										$_SESSION['alert_cursos'] = 'Ocurrió un error al subir la imagen';
										$_SESSION['alert_cursos_type'] = 'alert-danger';
									}
								}else{
									$_SESSION['alert_cursos'] = 'Imagen no permitida. Solo se permiten imagenes JPG, PNG y JPEG.';
									$_SESSION['alert_cursos_type'] = 'alert-danger';
								}
							}else{							
								$_SESSION['alert_cursos'] = 'Imagen demasiada grande. La imagen debe pesar menos de 5MB.';
								$_SESSION['alert_cursos_type'] = 'alert-danger';
							}
						}else{
							$_SESSION['alert_cursos'] = 'El curso ingresado ya existe';
							$_SESSION['alert_cursos_type'] = 'alert-danger';
						}	
					}else{
						$_SESSION['alert_cursos'] = 'El campo: "Nombre" debe contener mínimo 5 caracteres y máximo 45 caracteres';
						$_SESSION['alert_cursos_type'] = 'alert-danger';
					}					
				}else{
					$_SESSION['alert_cursos'] = 'Por favor rellene todos los campos ';
					$_SESSION['alert_cursos_type'] = 'alert-danger';
				}
			}
			header("LOCATION: ". HK ."/agregar_curso.php");
			exit;
		}

		public function AgregarCategoria(){
			global $db;
			if(isset($_POST['nombre']) && isset($_POST['desc']) && isset($_POST['bgcolor'])  && isset($_POST['p_blog']) && isset($_FILES['uploadImage']['name'])){
				$nombre = $this->Filter($_POST['nombre']);
				$desc = $this->Filter($_POST['desc']);
				$bgcolor = $this->Filter($_POST['bgcolor']);
				$p_blog = $this->Filter($_POST['p_blog']);
				$maxsize    = 5242880; // TAMAÑO MÁXIMO DEL ARCHIVO (5MB)
				$acceptable = array('image/svg+xml'); // SOLO SE PERMITEN SVG
				$filename = $_FILES['uploadImage']['name']; //nombre del archivo
				$filesize = $_FILES['uploadImage']['size']; // tamaño de imagen
				$filetype = $_FILES['uploadImage']['type']; // tipo de imagen
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$nombre_archivo = time();
				$nombre_real = $nombre_archivo.".".$ext;
				if(!empty($nombre) && !empty($desc) && !empty($bgcolor) && !empty($filename) && p_blog>=0){
					if(strlen($nombre) > 2 && strlen($nombre) < 45){
						if(!$this->Existe('categorias','titulo',$nombre,'val')){						
							if($filesize <= $maxsize && $filesize > 0){
								if(in_array($filetype, $acceptable) && !empty($filetype)){								
									//$url = $_SERVER['DOCUMENT_ROOT'].'/resources/img/icons/'.$nombre_real; // LA RUTA DE LA CARPETA SERVIDOR
									$url = '../../resources/img/icons/'.$nombre_real; // LA RUTA DE LA CARPETA LOCALHOST
									if(move_uploaded_file($_FILES['uploadImage']['tmp_name'], $url)){
										$url2 = PATHI.'/img/icons/'.$nombre_real;
										$query = $db->prepare("INSERT INTO categorias (titulo, bg_color, descripcion, img, p_blog) VALUES (?, ?, ?, ?)");
										$query->bind_param("sssss", $nombre, $bgcolor, $desc, $url2, $p_blog);
										$query->execute();
										$query->close();
										$_SESSION['alert_categorias'] = 'Categoría agregada correctamente';
										$_SESSION['alert_categorias_type'] = 'alert-success';	
										header("LOCATION: ". HK ."/categorias.php");
										exit;
									}else{
										$_SESSION['alert_categorias'] = 'Ocurrió un error al subir la imagen ';
										$_SESSION['alert_categorias_type'] = 'alert-danger';
									}
								}else{
									$_SESSION['alert_categorias'] = 'Imagen no permitida. Solo se permiten imagenes JPG, PNG, SVG y JPEG.';
									$_SESSION['alert_categorias_type'] = 'alert-danger';
								}
							}else{							
								$_SESSION['alert_categorias'] = 'Imagen demasiada grande. La imagen debe pesar menos de 5MB.';
								$_SESSION['alert_categorias_type'] = 'alert-danger';
							}
						}else{
							$_SESSION['alert_categorias'] = 'La categoría ingresada ya existe';
							$_SESSION['alert_categorias_type'] = 'alert-danger';
						}
					}else{
						$_SESSION['alert_categorias'] = 'El campo: "Nombre" debe contener mínimo 3 caracteres y máximo 45 caracteres';
						$_SESSION['alert_categorias_type'] = 'alert-danger';
					}					
				}else{
					$_SESSION['alert_categorias'] = 'Por favor rellene todos los campos obligatorios';
					$_SESSION['alert_categorias_type'] = 'alert-danger';
				}
			}
			header("LOCATION: ". HK ."/agregar_categoria.php");
			exit;
		}

		public function Existe($a,$b,$c,$e){
			global $db;
			$query = $db->prepare("SELECT null FROM {$a} WHERE {$b} = ? AND status = '1'");
			if(is_numeric($c)){
				$query->bind_param("i", $c);
			}else{
				$query->bind_param("s", $c);
			}
			$query->execute();
			$result = $query->get_result()->num_rows;
			$r;
			if($result > 0){
				$r = ['val' => true, 'num' => $result ];
				
			}else{
				$r = ['val' => false, 'num' => $result ];
			}
			return $r[$e];
		}

		public function Sele($a,$b,$c,$e){
			global $db;
			$query = $db->prepare("SELECT * FROM {$a} WHERE {$b} = ? AND status = '1'");
			if(is_numeric($c)){
				$query->bind_param("i", $c);
			}else{
				$query->bind_param("s", $c);
			}
			$query->execute();
			$data = $query->get_result()->fetch_array();
			$query->close();
			return $data[$e];
		}

		public function AgregarLeccion(){
			global $db;
			if(isset($_POST['nombre']) && isset($_POST['desc']) && isset($_POST['curso']) ){
				$nombre = $this->Filter($_POST['nombre']);
				$desc = $this->Filter($_POST['desc']);
				$curso = $this->Filter($_POST['curso']);
				if(!empty($nombre) && !empty($desc) && !empty($curso) && $curso > 0 ){
					if(strlen($nombre) > 5 && strlen($nombre) < 45){

						$num = $this->Existe('lecciones','cursos_idcursos',$curso,'num');
						if( $num > 0){
							$numero = $num + 1;
						}else{
							$numero = 1;
						}
						
						$query = $db->prepare("INSERT INTO lecciones (titulo, numero, contenido, cursos_idcursos) VALUES (?, ?, ?, ?)");
						$query->bind_param("sisi", $nombre, $numero,$desc, $curso);
						$query->execute();
						$query->close();
						$this->UpdateNumeroLecciones($curso,$numero);
						$_SESSION['alert_lecciones'] = 'Lección agregada correctamente';
						$_SESSION['alert_lecciones_type'] = 'alert-success';	
						header("LOCATION: ". HK ."/lecciones.php");
						exit;
								
					}else{
						$_SESSION['alert_lecciones'] = 'El campo: "Nombre" debe contener mínimo 5 caracteres y máximo 45 caracteres';
						$_SESSION['alert_lecciones_type'] = 'alert-danger';
					}					
				}else{
					$_SESSION['alert_lecciones'] = 'Por favor rellene todos los campos ';
					$_SESSION['alert_lecciones_type'] = 'alert-danger';
				}
			}
			header("LOCATION: ". HK ."/agregar_leccion.php");
			exit;
		}

		public function UpdateNumeroLecciones($a,$b){
			global $db;
			
			$query = $db->prepare("UPDATE cursos SET lecciones = ? 
			WHERE idcursos = ? LIMIT 1");
			$query->bind_param("ii", $b, $a);
			$query->execute();
			$query->close();
			return;
		}

		public function EditCategoria($id){
			global $db;
			if(isset($_POST['nombre']) && isset($_POST['desc']) && isset($_POST['bgcolor'])  && isset($_POST['p_blog']) && isset($_FILES['uploadImage']['name'])){
				$nombre = $this->Filter($_POST['nombre']);
				$desc = $this->Filter($_POST['desc']);
				$bgcolor = $this->Filter($_POST['bgcolor']);
				$p_blog = $this->Filter($_POST['p_blog']);
				$maxsize    = 5242880; // TAMAÑO MÁXIMO DEL ARCHIVO (5MB)
				$acceptable = array('image/svg+xml'); // SOLO SE PERMITEN SVG
				$filename = $_FILES['uploadImage']['name']; //nombre del archivo
				$filesize = $_FILES['uploadImage']['size']; // tamaño de imagen
				$filetype = $_FILES['uploadImage']['type']; // tipo de imagen
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$nombre_archivo = time();
				$nombre_real = $nombre_archivo.".".$ext;
				if(!empty($nombre) && !empty($desc) && !empty($bgcolor) && $p_blog>=0 ){
					if(strlen($nombre) > 2 && strlen($nombre) < 45){
						if(!$this->Existe('categorias','titulo',$nombre,'val') || $this->Sele('categorias','idcategorias',$id,'titulo') == $nombre){
							if(!empty($filename)){
								if($filesize <= $maxsize && $filesize > 0){
									if(in_array($filetype, $acceptable) && !empty($filetype)){								
										//$url = $_SERVER['DOCUMENT_ROOT'].'/resources/img/icons/'.$nombre_real; // LA RUTA DE LA CARPETA SERVIDOR
										$url = '../../resources/img/icons/'.$nombre_real; // LA RUTA DE LA CARPETA LOCALHOST
										if(move_uploaded_file($_FILES['uploadImage']['tmp_name'], $url)){
											$url2 = PATHI.'/img/icons/'.$nombre_real;
											$query = $db->prepare("UPDATE categorias set titulo = ?, bg_color = ?, descripcion = ?, img = ?, p_blog = ?
											WHERE idcategorias = ?");
											$query->bind_param("sssssi", $nombre, $bgcolor, $desc, $url2, $p_blog, $id);
											$query->execute();
											$query->close();
											$_SESSION['alert_categorias'] = 'Categoría actualizada correctamente';
											$_SESSION['alert_categorias_type'] = 'alert-success';
											header("LOCATION: ". HK ."/categorias.php");
											exit;
										}else{
											$_SESSION['alert_categorias'] = 'Ocurrió un error al subir la imagen ';
											$_SESSION['alert_categorias_type'] = 'alert-danger';
										}
									}else{
										$_SESSION['alert_categorias'] = 'Imagen no permitida. Solo se permiten imagenes JPG, PNG, SVG y JPEG.';
										$_SESSION['alert_categorias_type'] = 'alert-danger';
									}
								}else{							
									$_SESSION['alert_categorias'] = 'Imagen demasiada grande. La imagen debe pesar menos de 5MB.';
									$_SESSION['alert_categorias_type'] = 'alert-danger';
								}
							}else{
								$query = $db->prepare("UPDATE categorias set titulo = ?, bg_color = ?, descripcion = ?, p_blog = ?
								WHERE idcategorias = ?");
								$query->bind_param("ssssi", $nombre, $bgcolor, $desc, $p_blog, $id);
								$query->execute();
								$query->close();
								$_SESSION['alert_categorias'] = 'Categoría actualizada correctamente';
								$_SESSION['alert_categorias_type'] = 'alert-success';
								header("LOCATION: ". HK ."/categorias.php");
								exit;
							}						
							
						}else{
							$_SESSION['alert_categorias'] = 'La categoría ingresada ya existe';
							$_SESSION['alert_categorias_type'] = 'alert-danger';
						}
					}else{
						$_SESSION['alert_categorias'] = 'El campo: "Nombre" debe contener mínimo 3 caracteres y máximo 45 caracteres';
						$_SESSION['alert_categorias_type'] = 'alert-danger';
					}					
				}else{
					$_SESSION['alert_categorias'] = 'Por favor rellene todos los campos obligatorios';
					$_SESSION['alert_categorias_type'] = 'alert-danger';
				}
			}
			header("LOCATION: ". HK ."/editar_categoria.php?categoriaid=".$id);
			exit;		
		}

		public function EditCurso($id){
			global $db;
			if(isset($_POST['nombre']) && isset($_POST['desc']) && isset($_POST['categoria']) && isset($_POST['nivel']) && isset($_FILES['uploadImage']['name'])){
				$nombre = $this->Filter($_POST['nombre']);
				$desc = $this->Filter($_POST['desc']);
				$categoria = $this->Filter($_POST['categoria']);
				$nivel = $this->Filter($_POST['nivel']);				
				$maxsize    = 5242880; // TAMAÑO MÁXIMO DEL ARCHIVO (5MB)
				$acceptable = array('image/jpeg','image/jpg','image/png'); // SOLO SE PERMITEN JPEG JPG Y PNG
				$filename = $_FILES['uploadImage']['name']; //nombre del archivo
				$filesize = $_FILES['uploadImage']['size']; // tamaño de imagen
				$filetype = $_FILES['uploadImage']['type']; // tipo de imagen
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$nombre_archivo = time();
				$nombre_real = $nombre_archivo.".".$ext;
				if(!empty($nombre) && !empty($desc) && !empty($categoria) && !empty($nivel) && $categoria > 0 && $nivel > 0 ){
					if(strlen($nombre) > 4 && strlen($nombre) < 45){
						if(!$this->Existe('cursos','nombre',$nombre,'val') || $this->Sele('cursos','idcursos', $id,'nombre') == $nombre){
							if(!empty($filename)){
								if($filesize <= $maxsize && $filesize > 0){
									if(in_array($filetype, $acceptable) && !empty($filetype)){
										
										//$url = $_SERVER['DOCUMENT_ROOT'].'/resources/img/images2/'.$nombre_real; // LA RUTA DE LA CARPETA SERVIDOR
										$url = '../../resources/img/images2/'.$nombre_real; // LA RUTA DE LA CARPETA LOCALHOST
										if(move_uploaded_file($_FILES['uploadImage']['tmp_name'], $url)){							
											$url2 = PATHI.'/img/images2/'.$nombre_real;
											$query = $db->prepare("UPDATE cursos set nombre = ?, nivel = ?, descripcion = ?, img = ?, categorias_idcategorias = ?
											WHERE idcursos = ?");
											$query->bind_param("sissii", $nombre, $nivel, $desc, $url2, $categoria, $id);
											$query->execute();
											$query->close();
											$_SESSION['alert_cursos'] = 'Curso actualizado correctamente';
											$_SESSION['alert_cursos_type'] = 'alert-success';
											header("LOCATION: ". HK ."/cursos.php");
											exit;
										}else{
											$_SESSION['alert_cursos'] = 'Ocurrió un error al subir la imagen';
											$_SESSION['alert_cursos_type'] = 'alert-danger';
										}
									}else{
										$_SESSION['alert_cursos'] = 'Imagen no permitida. Solo se permiten imagenes JPG, PNG y JPEG.';
										$_SESSION['alert_cursos_type'] = 'alert-danger';
									}
								}else{							
									$_SESSION['alert_cursos'] = 'Imagen demasiada grande. La imagen debe pesar menos de 5MB.';
									$_SESSION['alert_cursos_type'] = 'alert-danger';
								}
							}else{
								$query = $db->prepare("UPDATE cursos set nombre = ?, nivel = ?, descripcion = ?, categorias_idcategorias = ?
								WHERE idcursos = ?");
								$query->bind_param("sisii", $nombre, $nivel, $desc, $categoria, $id);
								$query->execute();
								$query->close();
								$_SESSION['alert_cursos'] = 'Curso actualizado correctamente';
								$_SESSION['alert_cursos_type'] = 'alert-success';
								header("LOCATION: ". HK ."/cursos.php");
								exit;
							}
							
						}else{
							$_SESSION['alert_cursos'] = 'El curso ingresado ya existe';
							$_SESSION['alert_cursos_type'] = 'alert-danger';
						}	
					}else{
						$_SESSION['alert_cursos'] = 'El campo: "Nombre" debe contener mínimo 5 caracteres y máximo 45 caracteres';
						$_SESSION['alert_cursos_type'] = 'alert-danger';
					}					
				}else{
					$_SESSION['alert_cursos'] = 'Por favor rellene todos los campos ';
					$_SESSION['alert_cursos_type'] = 'alert-danger';
				}
			}
			header("LOCATION: ". HK ."/editar_curso.php?cursoid=".$id);
			exit;
		}

		public function EditLeccion($id){
			global $db;
			if(isset($_POST['nombre']) && isset($_POST['desc'])  ){
				$nombre = $this->Filter($_POST['nombre']);
				$desc = $this->Filter($_POST['desc']);
				if(!empty($nombre) && !empty($desc)  ){
					if(strlen($nombre) > 5 && strlen($nombre) < 45){	
						$query = $db->prepare("UPDATE lecciones set titulo = ?, contenido = ?
						WHERE idlecciones = ?");
						$query->bind_param("ssi", $nombre, $desc, $id);
						$query->execute();
						$query->close();				
						$_SESSION['alert_lecciones'] = 'Lección actualizada correctamente';
						$_SESSION['alert_lecciones_type'] = 'alert-success';		
						header("LOCATION: ". HK ."/lecciones.php");
						exit;
								
					}else{
						$_SESSION['alert_lecciones'] = 'El campo: "Nombre" debe contener mínimo 5 caracteres y máximo 45 caracteres';
						$_SESSION['alert_lecciones_type'] = 'alert-danger';
					}					
				}else{
					$_SESSION['alert_lecciones'] = 'Por favor rellene todos los campos ';
					$_SESSION['alert_lecciones_type'] = 'alert-danger';
				}
			}
			header("LOCATION: ". HK ."/editar_leccion.php?leccionid=".$id);
			exit;
		}

		public function AgregarBlog(){
			global $db;
			if(isset($_POST['nombre']) && isset($_POST['desc']) && isset($_POST['categoria']) && isset($_FILES['uploadImage']['name'])){
				$nombre = $this->Filter($_POST['nombre']);
				$desc = $this->Filter($_POST['desc']);
				$categoria = $this->Filter($_POST['categoria']);
				$maxsize    = 5242880; // TAMAÑO MÁXIMO DEL ARCHIVO (5MB)
				$acceptable = array('image/jpeg','image/jpg','image/png'); // SOLO SE PERMITEN JPEG JPG Y PNG
				$filename = $_FILES['uploadImage']['name']; //nombre del archivo
				$filesize = $_FILES['uploadImage']['size']; // tamaño de imagen
				$filetype = $_FILES['uploadImage']['type']; // tipo de imagen
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$nombre_archivo = time();
				$nombre_real = $nombre_archivo.".".$ext;
				if(!empty($nombre) && !empty($desc) && !empty($categoria) && !empty($filename) && $categoria > 0){
					if(strlen($nombre) > 4 && strlen($nombre) < 45){
						if(!$this->Existe('blog','titulo_blog',$nombre,'val')){
							if($filesize <= $maxsize && $filesize > 0){
								if(in_array($filetype, $acceptable) && !empty($filetype)){
									//$url = $_SERVER['DOCUMENT_ROOT'].'/resources/img/images2/'.$nombre_real; // LA RUTA DE LA CARPETA SERVIDOR
									$url = '../../resources/img/images2/'.$nombre_real; // LA RUTA DE LA CARPETA LOCALHOST
									if(move_uploaded_file($_FILES['uploadImage']['tmp_name'], $url)){
										$userid = $this->Filter($this->Get('idusuarios_login'));
										$fecha = date('Y-m-d');
										$url2 = PATHI.'/img/images2/'.$nombre_real;
										$query = $db->prepare("INSERT INTO blog (titulo_blog, contenido, imagen, fecha, categorias_idcategorias, usuarios_login_idusuarios_login) VALUES (?, ?, ?, ?, ?, ?)");
										$query->bind_param("ssssii", $nombre, $desc, $url2, $fecha, $categoria, $userid);
										$query->execute();
										$query->close();
										$_SESSION['alert_blog'] = 'Blog agregado correctamente';
										$_SESSION['alert_blog_type'] = 'alert-success';	
										header("LOCATION: ". HK ."/blog.php");
										exit;
									}else{
										$_SESSION['alert_blog'] = 'Ocurrió un error al subir la imagen';
										$_SESSION['alert_blog_type'] = 'alert-danger';
									}
								}else{
									$_SESSION['alert_blog'] = 'Imagen no permitida. Solo se permiten imagenes JPG, PNG y JPEG.';
									$_SESSION['alert_blog_type'] = 'alert-danger';
								}
							}else{							
								$_SESSION['alert_blog'] = 'Imagen demasiada grande. La imagen debe pesar menos de 5MB.';
								$_SESSION['alert_blog_type'] = 'alert-danger';
							}
						}else{
							$_SESSION['alert_blog'] = 'El blog ingresado ya existe';
							$_SESSION['alert_blog_type'] = 'alert-danger';
						}	
					}else{
						$_SESSION['alert_blog'] = 'El campo: "Nombre" debe contener mínimo 5 caracteres y máximo 45 caracteres';
						$_SESSION['alert_blog_type'] = 'alert-danger';
					}					
				}else{
					$_SESSION['alert_blog'] = 'Por favor rellene todos los campos ';
					$_SESSION['alert_blog_type'] = 'alert-danger';
				}
			}
			header("LOCATION: ". HK ."/agregar_blog.php");
			exit;
		}
		public function EditBlog($id){
			global $db;
			if(isset($_POST['nombre']) && isset($_POST['desc']) && isset($_POST['categoria']) && isset($_FILES['uploadImage']['name'])){
				$nombre = $this->Filter($_POST['nombre']);
				$desc = $this->Filter($_POST['desc']);
				$categoria = $this->Filter($_POST['categoria']);			
				$maxsize    = 5242880; // TAMAÑO MÁXIMO DEL ARCHIVO (5MB)
				$acceptable = array('image/jpeg','image/jpg','image/png'); // SOLO SE PERMITEN JPEG JPG Y PNG
				$filename = $_FILES['uploadImage']['name']; //nombre del archivo
				$filesize = $_FILES['uploadImage']['size']; // tamaño de imagen
				$filetype = $_FILES['uploadImage']['type']; // tipo de imagen
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$nombre_archivo = time();
				$nombre_real = $nombre_archivo.".".$ext;
				if(!empty($nombre) && !empty($desc) && !empty($categoria) && $categoria > 0){
					if(strlen($nombre) > 4 && strlen($nombre) < 45){
						if(!$this->Existe('blog','titulo_blog',$nombre,'val') || $this->Sele('blog','idblog', $id,'titulo_blog') == $nombre){
							if(!empty($filename)){
								if($filesize <= $maxsize && $filesize > 0){
									if(in_array($filetype, $acceptable) && !empty($filetype)){					
										//$url = $_SERVER['DOCUMENT_ROOT'].'/resources/img/images2/'.$nombre_real; // LA RUTA DE LA CARPETA SERVIDOR
										$url = '../../resources/img/images2/'.$nombre_real; // LA RUTA DE LA CARPETA LOCALHOST
										if(move_uploaded_file($_FILES['uploadImage']['tmp_name'], $url)){
											
											$url2 = PATHI.'/img/images2/'.$nombre_real;
											$query = $db->prepare("UPDATE blog set titulo_blog = ?, contenido = ?, imagen = ?, categorias_idcategorias = ?
											WHERE idblog = ?");
											$query->bind_param("sssii", $nombre, $desc, $url2, $categoria, $id);
											$query->execute();
											$query->close();
											$_SESSION['alert_blog'] = 'Blog actualizado correctamente';
											$_SESSION['alert_blog_type'] = 'alert-success';
											header("LOCATION: ". HK ."/blog.php");
											exit;
										}else{
											$_SESSION['alert_blog'] = 'Ocurrió un error al subir la imagen';
											$_SESSION['alert_blog_type'] = 'alert-danger';
										}
									}else{
										$_SESSION['alert_blog'] = 'Imagen no permitida. Solo se permiten imagenes JPG, PNG y JPEG.';
										$_SESSION['alert_blog_type'] = 'alert-danger';
									}
								}else{							
									$_SESSION['alert_blog'] = 'Imagen demasiada grande. La imagen debe pesar menos de 5MB.';
									$_SESSION['alert_blog_type'] = 'alert-danger';
								}
							}else{
								$query = $db->prepare("UPDATE blog set titulo_blog = ?, contenido = ?, categorias_idcategorias = ?
								WHERE idblog = ?");
								$query->bind_param("ssii", $nombre, $desc, $categoria, $id);
								$query->execute();
								$query->close();
								$_SESSION['alert_blog'] = 'Blog actualizado correctamente';
								$_SESSION['alert_blog_type'] = 'alert-success';
								header("LOCATION: ". HK ."/blog.php");
								exit;
							}
							
						}else{
							$_SESSION['alert_blog'] = 'El blog ingresado ya existe';
							$_SESSION['alert_blog_type'] = 'alert-danger';
						}	
					}else{
						$_SESSION['alert_blog'] = 'El campo: "Nombre" debe contener mínimo 5 caracteres y máximo 45 caracteres';
						$_SESSION['alert_blog_type'] = 'alert-danger';
					}					
				}else{
					$_SESSION['alert_blog'] = 'Por favor rellene todos los campos';
					$_SESSION['alert_blog_type'] = 'alert-danger';
				}
			}
			header("LOCATION: ". HK ."/editar_blog.php?blogid=".$id);
			exit;
		}

	}
?>