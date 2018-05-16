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
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<img src="img/generales/logo-login.png" alt="<?php echo TITLE; ?>" />
			</div>
			<div class="login-box-body">
				<p class="login-box-msg">Por favor, introduce tu usuario y contraseña.</p>
				<?php if (!empty($error)) : ?>
					<p class="login-box-msg error">
						<?php echo $error; ?>
					</p>
				<?php endif; ?>
				<?php if (!empty($message)) : ?>
					<p class="login-box-msg message">
						<?php echo $message; ?>
					</p>
				<?php endif; ?>
				<?php echo form_open(current_url()); ?>
				<div class="form-group has-feedback">
					<?php echo form_input($identity); ?>
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<?php echo form_input($password); ?>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<?php echo form_submit('submit', 'Ingresar', 'class="btn btn-primary btn-block btn-flat"'); ?>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
		<!-- jQuery 2.1.4 -->
		<script src="js/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="plugins/bootstrap-3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
		<!-- iCheck -->
		<script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
		<script>
			$(function () {
				$('input').iCheck({
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%' // optional
				});
			});
		</script>
	</body>
</html>