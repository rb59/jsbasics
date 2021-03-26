<?php 
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
global $Functions; 
?>                   
	<nav id="stickyNav" class="navbar navbar-marketing navbar-expand-lg <?php if($tab == "1"){ echo 'bg-transparent '; }else{echo 'bg-light2 shadow';} ?> navbar-light2 fixed-top">
		<div class="container">
			<a class="navbar-brand" href="<?php echo PATH;?>"> 
				<div class="logo"></div>
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i data-feather="menu"></i></button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item <?php if($tab == "1"){ echo 'active'; } ?>">
						<a class="nav-link" href="<?php echo PATH;?>/index">Inicio</a>
					</li>
					<li class="nav-item <?php if($tab == "5"){ echo 'active'; } ?>">
							<a class="nav-link" href="<?php echo PATH;?>/cursos">Cursos</a>
					</li>    
					<li class="nav-item <?php if($tab == "6"){ echo 'active'; } ?>">
							<a class="nav-link" href="<?php echo PATH;?>/blog">Blog</a>
					</li>
					<li class="nav-item <?php if($tab == "7"){ echo 'active mb-3 mb-md-0'; } ?>">
						<a class="nav-link" href="<?php echo PATH;?>/info">Información</a>
					</li>
					<?php if ($tab == "9"){?>
						<li class="nav-item <?php if($tab == "9"){ echo 'active mb-3 mb-md-0'; } ?>">
							<a class="nav-link" href="<?php echo PATH;?>/term">Términos</a>
						</li>
					<?php }?>
					<?php if ($Functions->Filter($Functions->Get('idusuarios') >= "1")){?>
					<li class="color-grey separator"></li>
					<li class="nav-item <?php if($tab == "4" OR $tab == "8"){ echo 'active mt-2 mt-md-0'; } ?> dropdown dropdown-user">
                    	<a class="nav-link dropdown-toggle <?php if($tab == "4" OR $tab == "8"){ echo 'pb-0_7'; } ?>" id="navbarDropdownDemos" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<div class="box rounded-circle position-absolute">
								<img src="<?php echo $Functions->Filter($Functions->Get('foto'));?>">
							</div>
							<div class="ml-5">Perfil</div>						
						</a>
						<div class="dropdown-menu dropdown-menu-right border-0 mt-3 mt-lg-0" aria-labelledby="navbarDropdownUserImage">
							<h6 class="dropdown-header d-flex align-items-center">
								<div class="box rounded-circle mr-3">
									<img src="<?php echo $Functions->Filter($Functions->Get('foto'))?>">
								</div>
								<div class="dropdown-user-details">
									<div class="dropdown-user-details-name"><?php echo "".$Functions->Filter($Functions->Get('nombres'))." ".$Functions->Filter($Functions->Get('apellidos'))."";?></div>
									<div class="dropdown-user-details-email"><?php echo $Functions->Filter($Functions->Get('email'))?></div>
								</div>
							</h6>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item <?php if($tab == "4"){ echo 'active'; } ?> " href="<?php echo PATH;?>/perfil">
								<div class="dropdown-item-icon"><i data-feather="settings"></i></div>
								Perfil
							</a>
							<a class="dropdown-item <?php if($tab == "8"){ echo 'active'; } ?> " href="<?php echo PATH;?>/miscursos">
								<div class="dropdown-item-icon"><i class="fas fa-graduation-cap"></i></div>
								Mis cursos
							</a>
							<?php if ($Functions->Filter($Functions->Get('usuarios_niveles_idusuarios_niveles') >= NIVEL_MINIMO_PANEL)){?>
							<a class="dropdown-item" href="<?php echo HK;?>">
								<div class="dropdown-item-icon"><i class="fas fa-tools"></i></div>
								Panel administrativo
							</a>
							<?php }?>
							<a class="dropdown-item" href="<?php echo PATH;?>/logout">
								<div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
								Cerrar Sesión
							</a>
						</div>
					</li>
					<?php }?>
					<?php if ($Functions->Filter($Functions->Get('idusuarios') <= "0") AND $tab !== "1"){?>
						<li class="nav-item <?php if($tab == "2"){ echo 'active'; } ?>">
							<a class="nav-link" href="<?php echo PATH;?>/ingresar">Ingresar</a>
						</li>
						<li class="nav-item <?php if($tab == "3"){ echo 'active'; } ?>">
							<a class="nav-link" href="<?php echo PATH;?>/registrar">Registrar</a>
						</li>
					<?php }?>
				</ul>		
				<?php if ($Functions->Filter($Functions->Get('idusuarios') <= "0") AND $tab == "1"){?>
					<a class="btn-navbar lift lift-sm btn rounded-pill px-4 ml-lg-4" href="<?php echo PATH;?>/ingresar">Ingresar</i></a>
				<?php }?>
			</div>
		</div>
	</nav>