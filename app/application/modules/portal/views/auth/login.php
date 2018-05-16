<!DOCTYPE html>
<html>
	<head>
		<base href="<?php echo base_url(); ?>" />
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo empty($title) ? TITLE : $title; ?></title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="description" content="">
		<link rel="apple-touch-icon" href="apple-touch-icon.png">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="plugins/bootstrap-3.3.6/css/bootstrap.min.css">
		<!-- Font Awesome Icons -->
		<link rel="stylesheet" href="plugins/font-awesome-4.7/css/font-awesome.min.css" />
		<!-- Theme style -->
		<link rel="stylesheet" href="css/AdminLTE.min.css" />
		<!-- App style -->
		<link rel="stylesheet" href="css/skins/skin-zetta.css" />
		<!-- iCheck -->
		<link rel="stylesheet" href="plugins/iCheck/square/blue.css" />

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>	
	<style>
		.form {
			position: relative;
			z-index: 1;
			background: #FFFFFF;
			max-width: 360px;
			margin: 0 auto 100px;
			padding: 30px;
			text-align: center;
			box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
		}

		.form-control {
			width: 100%;
			height: 45px;
			border: none;
			padding: 5px 7px 5px 15px;
			background: #fff;
			color: #666;
			border: 2px solid #ddd;
			-moz-border-radius: 4px;
			-webkit-border-radius: 4px;
			border-radius: 4px;
		}
		.form-control:focus, .form-control:focus + .fa {
			border-color: #3c8dbc;
		}	
		.form-control:focus + .fa {
			color: #3c8dbc;
		}
		.form-group .fa {
			position: absolute;
			right: 10px;
			top: 6px;
			color: #999;
		}
		.form-group {
			margin-bottom: 20px;
		}
		.btn {
			width: 100%;
			font-size: 16px;
			height: 40px;
			border-radius: 4px;
		}
		.btn.btn-flat {
			margin-top: 4px;
			border-radius: 4px;
		}
		.login-box-msg + .error{
			margin: 0;
			text-align: center;
			color: #a94442;
			padding: 0 20px 10px 20px;
		}
		.login-box-msg + .message{
			margin: 0;
			text-align: center;
			color: #008d4c;
			padding: 0 20px 10px 20px;
		}
		.log-status.wrong-entry {
			-moz-animation: wrong-log 0.3s;
			-webkit-animation: wrong-log 0.3s;
			animation: wrong-log 0.3s;
		}

		.log-status.wrong-entry .form-control, .wrong-entry .form-control + .fa {
			border-color: #ed1c24;
			color: #ed1c24;
		}
		.alert{
			display:none;
			padding: 0px;
			margin-bottom: -19px;
			font-size:12px;
			color:#f00;
			text-align: center;
		}
		.alert_pass{
			display:none;
			padding: 0px;
			margin-bottom: -17px;
			font-size:12px;
			color:#f00;
			text-align: center;
		}
	</style>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<img src="img/generales/logo-login.png" alt="<?php echo TITLE; ?>" />
			</div>
			<div class="form">
				<p class="login-box-msg">Por favor, introduce tu usuario y contraseña.</p>
				<?php if (!empty($error)) : ?>
					<div class="login-box-msg error">
						<?php echo $error; ?>
					</div>
				<?php endif; ?>
				<?php if (!empty($message)) : ?>
					<div class="login-box-msg message">
						<?php echo $message; ?>
					</div>
				<?php endif; ?>
				<?php echo form_open(current_url(), array('id' => 'form_login')); ?>
				<div class="form-group has-feedback" id="verificar">
					<?php echo form_input($usuario); ?>
					<span class="fa fa-user form-control-feedback"></span>
					<div class="alert">El campo usuario no puede estar vacío.</div>
				</div>
				<div class="form-group has-feedback" id="verificar_pass">
					<?php echo form_input($password); ?>
					<span class="fa fa-lock form-control-feedback"></span>
					<div class="alert_pass">El campo contraseña no puede estar vacío.</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<?php echo form_submit('submit', 'Ingresar', 'class="btn btn-primary btn-block btn-flat"'); ?>
						<?php if (PORTAL): ?>
							<a class="btn btn-success btn-block btn-flat" href="portal/auth/verificar_usuario_padre/">¿Usuario nuevo? Padres, Cliquea aquí.</a>
							<a class="btn btn-success btn-block btn-flat" href="portal/auth/verificar_usuario_alumno/">¿Usuario nuevo? Alumnos, Cliquea aquí.</a>
						<?php endif; ?>
					</div>
				</div>
				<br>
				<label style="font-size: 12px; text-align: center; width: 100%">
					<a class="text-center need-help" href="portal/auth/recuperar_password">Recuperar contraseña.</a>
					<a class="text-center need-help" style="color: #ff0000;" href="portal/auth/info">Ayuda.</a>
				</label>
				<?php echo form_close(); ?>
				<label style="font-size: 12px; text-align: center; width: 100%">
					<a href="uploads/ayuda/portal/manual_padres.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i> <u>Manual portal padres</u></a> /
					<a href="uploads/ayuda/portal/manual_alumnos.pdf" target="_blank"><i class="fa fa-file-pdf-o"></i> <u>Manual portal alumnos</u></a>
				</label>
			</div>
		</div>
		<!-- jQuery 2.1.4 -->
		<script src="js/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="plugins/bootstrap-3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
		<!-- iCheck -->
		<script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
		<script>
			$(function() {
				$('input').iCheck({
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%' // optional
				});
			});
			function verificar_datos(event) {
				console.log(event);
				var usuario = $('#usuario').val();
				var password = $('#password').val();
				if (usuario == '' || password == '') {
					if (usuario == '') {
						$('#verificar').addClass('log-status wrong-entry');
						$('.alert').fadeIn(500);
						setTimeout("$('.alert').fadeOut(1500);", 3000);
						setTimeout("$('#verificar').removeClass('log-status wrong-entry')", 3500);
					}
					if (password == '') {
						$('#verificar_pass').addClass('log-status wrong-entry');
						$('.alert_pass').fadeIn(500);
						setTimeout("$('.alert_pass').fadeOut(1500);", 3000);
						setTimeout("$('#verificar_pass').removeClass('log-status wrong-entry')", 3500);
					}
					event.preventDefault();
					return false;
				}
			}
			$(document).ready(function() {
				$('#form_login').submit(verificar_datos);
			});
		</script>
	</body>
</html>