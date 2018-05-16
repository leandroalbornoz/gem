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
					<li class="dropdown messages-menu <?php echo isset($this->rol->codigo) && (count($mensajes) > 0 && ($this->rol->codigo !== ROL_ADMIN && $this->rol->codigo !== ROL_USI)) ? 'open' : ''; ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
							<?php if (es_rol_bono($this->rol)): ?>
								<span class="label label-success"><?php echo $mensajes_sin_leer->sin_leer; ?></span>
							<?php else: ?>
								<span class="label label-success"><?php echo count($mensajes); ?></span>
							<?php endif; ?>
            </a>
						<ul class="dropdown-menu">
							<?php if (es_rol_bono($this->rol)): ?>
								<li class="header">Últimos <?php echo count($mensajes); ?> mensaje(s) sin leer</li>
							<?php else: ?>
								<li class="header">Hay <?php echo count($mensajes); ?> mensaje(s) sin leer</li>
							<?php endif; ?>
							<li>
								<ul class="menu">
									<?php if (!es_rol_bono($this->rol)): ?>
										<?php foreach ($mensajes as $mensaje): ?>
											<li>
												<a href="mensaje/modal_ver/<?php echo $mensaje->id; ?>/1" data-remote="false" data-toggle="modal" data-target="#remote_modal">
													<h4 style="padding-right: 60px; overflow: hidden; text-overflow: ellipsis;">
														<span class="text-sm text-bold text-uppercase"><?php echo $mensaje->de_rol; ?></span>
														<small><i class="fa fa-clock-o"></i> <?php echo (new DateTime($mensaje->fecha))->format('d/m H:i'); ?></small>
													</h4>
													<p><?php echo $mensaje->asunto; ?></p>
												</a>
											</li>
										<?php endforeach; ?>
										<?php
									else:
										foreach ($mensajes as $mensaje):
											?>
											<li>
												<a href="bono_secundario/mensaje/modal_ver/<?php echo $mensaje->id; ?>/1" data-remote="false" data-toggle="modal" data-target="#remote_modal">
													<h4 style="padding-right: 60px; overflow: hidden; text-overflow: ellipsis;">
														<span class="text-sm text-bold text-uppercase"><?php echo "(BONO SECUNDARIO) - " . $mensaje->de_usuario; ?></span>
														<small><i class="fa fa-clock-o"></i> <?php echo (new DateTime($mensaje->fecha))->format('d/m H:i'); ?></small>
													</h4>
													<p><?php echo $mensaje->asunto; ?></p>
												</a>
											</li>
										<?php endforeach; ?>
									<?php endif; ?>

								</ul>
							</li>
							<li class="footer">
								<?php if (es_rol_bono($this->rol)): ?>
									<a href="bono_secundario/mensaje/bandeja">Ir a bandeja de mensajes</a>
								<?php else: ?>
									<a href="mensaje/bandeja">Ir a bandeja de mensajes</a>
								<?php endif; ?>
							</li>
						</ul>
          </li>
					<li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bullhorn"></i>
              <span class="label label-warning"><?php echo count($mensajes_masivos); ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Hay <?php echo count($mensajes_masivos); ?> Msj(s) de difusión sin leer</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
									<?php foreach ($mensajes_masivos as $mensaje_masivo): ?>
										<li>
											<a href="mensaje/modal_ver_difusion/<?php echo $mensaje_masivo->id; ?>/0" data-remote="false" data-toggle="modal" data-target="#remote_modal">
												<h4 style="padding-right: 60px; overflow: hidden; text-overflow: ellipsis;">
													<span class="text-sm text-bold text-uppercase"><?php echo $mensaje_masivo->de_rol; ?></span>
													<small><i class="fa fa-clock-o"></i> <?php echo (new DateTime($mensaje_masivo->fecha))->format('d/m H:i'); ?></small>
												</h4>
												<p><?php echo $mensaje_masivo->asunto; ?></p>
											</a>
										</li>
									<?php endforeach; ?>
                </ul>
              </li>
              <li class="footer"><a href="mensaje/mensajes_difusion">Ir a bandeja de Msj.Difusión</a></li>
            </ul>
          </li>
					<li class="dropdown tasks-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-flag-o"></i>
							<span class="label label-danger"><?php echo count($alertas); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="header">Hay <?php echo count($alertas); ?> alerta(s)</li>
							<li>
								<ul class="menu">
									<?php foreach ($alertas as $alerta): ?>
										<li>
											<a href="<?php echo $alerta->url; ?>">
												<h3>
													<span class="label label-danger"><?php echo $alerta->value; ?></span>
													<?php echo $alerta->label; ?>
												</h3>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</li>
							<li class="footer">
								<a href="#">Ver todas las alertas</a>
							</li>
						</ul>
					</li>
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