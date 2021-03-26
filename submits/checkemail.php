<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
	ob_start();
	require_once '../global.php';
	$email = $Functions->Filter($_POST['name']);

	$Email_Checked = $Functions->ValidarCorreo($email, "#email");
	if(!empty($Email_Checked)){ 
		echo '<p class="mt-2 mb-0 small input-error"><i class="icon fa fa-times-circle"></i> '.$Email_Checked.'</p>';
	}else{
		echo '<p class="mt-2 mb-0 small text-success"><i class="icon fa fa-check-circle"></i> Correo electrónico disponible.</p>';
		echo'
			<script id="script">
				$("#email").addClass("input-good");
				$("#script").remove();
			</script>
		';
	}
	ob_end_flush();
?>