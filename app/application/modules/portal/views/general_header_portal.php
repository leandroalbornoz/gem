<header class="main-header">
	<a href="escritorio" class="logo">
		<span class="logo-mini"><?php echo LOGO_TITLE_SM; ?></span>
		<span class="logo-lg"><b><?php echo LOGO_TITLE; ?></b></span>
	</a>
	<nav class="navbar navbar-static-top" role="navigation">
		<div class="container-fluid">
			<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<li>
						<a style="font-size: 130%; font-weight: bold;" href="usuarios/rol/modal_seleccionar?redirect_url=<?php echo urlencode(str_replace(base_url(), '', current_url())); ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal">
							<i class="fa fa-refresh"></i> <span>Rol: <?php echo empty($rol) ? 'Sin Rol Seleccionado' : "$rol->rol" . (empty($rol->entidad) ? '' : " $rol->entidad"); ?></span>
						</a>
					</li>
					<?php if (ENVIRONMENT !== 'production'): ?>
						<li>
							<a href="#" class="label-warning"><span >Entorno de Pruebas</span></a>
						</li>
					<?php endif; ?>
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="data:image/png;base64,<?php echo base64_encode($imagen); ?>" class="user-image" alt="Usuario" />
							<span class="hidden-xs"><?php echo "$apellido, $nombre"; ?></span>
						</a>
				
						<ul class="dropdown-menu">
							<li class="user-header" style="height: 80px;">
								<div style="float:left;">
									<img width="50px" src="data:image/png;base64,<?php echo base64_encode($imagen); ?>" class="img-circle" alt="Usuario" />
								</div>
								<div class="">
									<?php echo "$apellido, $nombre<br>CUIL: $cuil"; ?>
									<?php if (ENVIRONMENT === 'development'): ?>
										<a href='javascript:(function(){var s=document.createElement("script");s.onload=function(){bootlint.showLintReportForCurrentDocument([]);};s.src="https://maxcdn.bootstrapcdn.com/bootlint/latest/bootlint.min.js";document.body.appendChild(s)})();'>Check Errors</a>
									<?php endif; ?>
									<?php if (!empty($login_actual)) : ?>
										<small>Ingreso actual <?php echo date_format(new DateTime($login_actual), 'd/m/Y H:i:s'); ?></small>
									<?php endif; ?>
								</div>
								<p>
								</p>
							</li>
							<li class="user-footer">
								<div clas="pull-left"> 
								<a class="btn btn-success navbar-btn" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="portal/escritorio/modal_cambiar_password" style="float: left;">Cambiar Contraseña</a>
								</div>
								<div class="pull-right">
									<form action="usuarios/auth/logout">
										<button type="submit" class="btn btn-danger navbar-btn">Salir</button>
									</form>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</header>