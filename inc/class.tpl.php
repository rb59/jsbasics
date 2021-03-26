<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|

	class TplClass{

		private $outputData;
		private $params = Array();
		private $tplName = '';
		public $user = Array();

		public function DivId($a){
			echo '<div id="'.$a.'">';
		}
		
		public function DivClass($a){
			echo '<div class="'.$a.'">';
		}

		public function DivClosed(){
			echo '</div>';
		}
		public function MainDisplay(){
			echo '<main>';
		}
		
		public function MainClosed(){
			echo '</main>';
		}
		
		public function DisplayError($a, $b){
			echo '<h2>'. $a .'</h2>';
			echo $b;
		}

		public function AddTemplate($Dir, $Name){
			echo $this->GetHtml($Dir, $Name);
		}

		public function SetParam($param, $value){
			$this->params[$param] = $value;
		}
		
		public function UnsetParam($param){
			unset($this->params[$param]);
		}		

		public function FilterParams($str){
			foreach ($this->params as $param => $value){
				$str = str_ireplace('{' . $param . '}', $value, $str);
			}
			return $str;
		}

		public function GetHTML($a, $b){	
			extract($this->params);
			$file = DIR . SEPARATOR .'templates/' . $a . '/' . $b . '.php';
			if(!file_exists($file)){
				$this->DisplayError('Archivo PHP no encontrado', 'No se ha podido cargar el siguiente PHP: <b>' . $b .'.php</b>');
			}else{
				ob_start();
				include($file);
				$data = ob_get_contents();
				ob_end_clean();	
				return $this->FilterParams($data);
			}
		}
	}

?>