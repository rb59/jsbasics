<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|

	class MysqlClass{
		
		public static $config;
	
		final function __construct(){
			try{
				self::getconfig();
				self::definevars();
			}
			catch(Exception $e)
			{
				trigger_error('Error in '. __FUNCTION__ . ' more info: '. $e->getMessage);
			}
		}
		final function getconfig(){
			
			if (file_exists( __DIR__ . DIRECTORY_SEPARATOR . 'class.config.php')){
				require_once( __DIR__ . DIRECTORY_SEPARATOR . 'class.config.php');
				self::$config = $config;
			}
			else{
				die('No se a podido encontrar el archivo class.config.php');
				
			}
		}
		final function definevars(){
			foreach(self::$config as $var => $value){
				if(!defined($var))
					define($var, $value);
			}
		}
		final function database(){
			$xx = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE, DB_PORT); //Servidor, usuario, contraseña, nombre de la base de datos.
			if ($xx -> connect_errno) {
				echo "Failed to connect to MySQL: " . $xx -> connect_error;
				exit();
			}else{
				return $xx;
			}
		}
	}
?>