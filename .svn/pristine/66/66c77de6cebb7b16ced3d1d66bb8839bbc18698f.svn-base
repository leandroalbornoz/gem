<!DOCTYPE html>
<html lang="es">
	<head>
		<base href="<?php echo base_url(); ?>" />
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo TITLE; ?></title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="description" content="">
		<link rel="apple-touch-icon" href="apple-touch-icon.png">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="plugins/bootstrap-3.3.6/css/bootstrap.min.css">
		<!-- Font Awesome Icons -->
		<link rel="stylesheet" href="plugins/font-awesome-4.7/css/font-awesome.min.css" />
		<!-- Ionicons -->
		<link rel="stylesheet" href="plugins/ionicons-2.0.1/css/ionicons.min.css" />
		<!-- DATA TABLES -->
		<link rel="stylesheet" href="css/AdminLTE.min.css" />
		<link rel="stylesheet" href="css/skins/skin-blue-light.min.css" />
		<link rel="stylesheet" href="<?php echo auto_version('css/dge.css'); ?>" />

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- jQuery 2.1.4 -->
		<script src="js/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>
		<script src="plugins/inputmask/jquery.inputmask.bundle.js" type="text/javascript"></script>
		<script src="plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
	</head>
	<body class="hold-transition skin-blue-light sidebar-mini">
		<div class="wrapper">
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
								</li>
								<?php if (ENVIRONMENT !== 'production'): ?>
									<li>
										<a href="#" class="label-warning"><span >Entorno de Pruebas</span></a>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</nav>
			</header>
			<aside class="main-sidebar">
				<section class="sidebar">
					<div class="user-panel">
						<div class="pull-left image">
							<img src="img/generales/logo-dge-sm.png" class="img-circle" alt="Usuario" />
						</div>
						<div class="pull-left info">
						</div>
					</div>
					<ul class="sidebar-menu">
						<li class="header">MENU PRINCIPAL</li>
						<li><a href="escritorio"><i class="fa fa-laptop"></i> <span>Escritorio</span></a></li>
					</ul>
				</section>
			</aside>
			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						<label>Error general</label>
					</h1>
					<ol class="breadcrumb">
						<li class="active"><i class="fa fa-home"></i> Inicio</li>
					</ol>
				</section>
				<section class="content">
					<div class="row">
						<div class="col-xs-12">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title"><?php echo $heading; ?></h3>
								</div>
								<div class="box-body">
									<a class="btn btn-app btn-app-zetta" href="escritorio">
										<i class="fa fa-laptop"></i> Volver al escritorio
									</a>
									<?php echo $message; ?>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<div class="modal fade" id="remote_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
					</div>
				</div>
			</div>
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<b>Version</b> 1.0
				</div>
				<strong>Copyright &copy; 2016-<?= date('Y'); ?> DGE Todos los derechos reservados.</strong>
			</footer>
		</div>
		<!-- Bootstrap 3.3.6 -->
		<script src="plugins/bootstrap-3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
		<!-- AdminLTE App -->
		<script src="js/adminlte/app.min.js" type="text/javascript"></script>
		<script src="<?php echo auto_version('js/main.js'); ?>" type="text/javascript"></script>
	</body>
</html>