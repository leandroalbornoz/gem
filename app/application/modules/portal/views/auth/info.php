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
		.btn {
			width: 100%;
			font-size: 16px;
			height: 40px;
			border-radius: 4px;
		}
		.btn.btn-flat {
			border-radius: 4px;
		}
		.login-box, .register-box {
			width: 1000px;
			margin: 7% auto;
		}
		.panel-default>.panel-heading {
			color: #fff;
			background-color: #3c8dbc;
			border-color: #3c8dbc;
		}
		.panel-heading {
			padding: 1px 15px;
		}
		.panel-default {
			border-color: #3c8dbc;
			box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
		}
		.btn {
			width: 28%;
			font-size: 12px;
			height: 29px;
			border-radius: 4px;
		}		
		.btn-primary {
			width: 12%;
			font-size: 12px;
			height: 29px;
			border-radius: 4px;
		}
	</style>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<img src="img/generales/logo-login.png" alt="<?php echo TITLE; ?>" />
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3>Sobre el Sistema de Portal G.E.M.</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-10 col-lg-offset-1">
							<h3><u>Crear Usuario, padres</u>: </h3>
							<p>Para la creación de cuenta para padres, debe seleccionar el botón indicado <a type="button" class="btn btn-success">¿Usuario nuevo? Padres, Cliquea aquí</a>. El sistema le pedirá ingresar su documento, el documento de su hijo y el número de escuela a cual su hijo asiste. Luego deberá presionar en "verificar" y el sistema verificara los datos, si los datos son correctos y coinciden podrá continuar. Se verificara si posee cuenta ya creada, en caso de poseer cuenta el sistema solo le pedirá asignarse el rol y luego podrá acceder al Portal. En caso de no poseer cuenta el sistema le pedirá su CUIL y un mail valido para la creación de la cuenta. Una vez generado el usuario nuevo podrá continuar con el siguiente paso de "Activar Usuario". Si los datos no coinciden o no son correctos, deberá comunicarse con la escuela para que ella pueda verificar los datos.</p>
						</div>
						<div class="col-lg-10 col-lg-offset-1">
							<h3><u>Crear Usuario, alumnos</u>:</h3>
							<p>Para la creación de cuenta para alumnos, debe seleccionar el botón indicado <a type="button" class="btn btn-success">¿Usuario nuevo? Alumnos, Cliquea aquí.</a>. El sistema le pedirá ingresar su documento, la clave alfanumérica correspondiente al curso división asignada y el número de escuela a la cual asiste. Luego deberá presionar en "verificar" y el sistema verificara los datos, si los datos son correctos y coinciden podrá continuar. Se verificara si posee cuenta ya creada, en caso de poseer cuenta el sistema solo le pedirá asignarse el rol y luego podrá acceder al Portal. En caso de no poseer cuenta el sistema le pedirá su CUIL y un mail valido para la creación de la cuenta. Una vez generado el usuario nuevo podrá continuar con el siguiente paso de "Activar Usuario". Si los datos no coinciden o no son correctos, deberá comunicarse con la escuela para que ella pueda verificar los datos.</p>
						</div>
						<div class="col-lg-10 col-lg-offset-1">
							<h3><u>Activar Usuario</u>:</h3>
							<p>Para la activación de usuario, una vez creado el usuario. El sistema le enviara un mail a su casilla de correo con el asusto "Creación de cuenta", en el cual encontrara detalladamente el nombre de usuario y una contraseña inicial que le brinda el sistema para poder ingresar por primera vez. Para poder ingresar al sistema deberá si o si activar su cuenta presionando en el boton <a type="button" class="btn btn-primary">Activar cuenta</a></p> o usando la URL (link) que se encuentra detallada en el mail, la cual deberá copiar y pegar en la barra de direcciones de su navegado. De esta forma el sistema le informara que su cuenta se ha activado correctamente y podra acceder al Portal. 
						</div>
						<div class="col-lg-10 col-lg-offset-1">
							<h3><u>Ingreso</u>:</h3>
							<p>Para ingresar al sistema, una vez creada la cuenta y activada. Solamente debe ingresar su usuario y/o mail en el campo de usuario y la contraseña correspondiente. Luego solo oprimir el botón ingresar.</p>
						</div>
						<div class="col-lg-10 col-lg-offset-1">
							<h3><u>Recuperar Contraseña/Clave</u>:</h3>
							<p>Para la recuperación de contraseña o clave, deberá seleccionar el la opción "Recuperar contraseña". Una vez seleccionada esta opción, el sistema le pedirá ingresar el correo electrónico o usuario correspondiente. Al ingresar el dato correspondiente automáticamente el sistema el enviara un mail a su casilla de correo con el asusto "Recuperación de contraseña", el cual deberá revisar y a continuación seguir los pasos que salen detallados.</p>
						</div>
					</div>
					<label style="font-size: 13px; text-align: right; width: 98%; margin-top: 20px;">
						<a class="text-center need-help" href="portal/auth/login/">Volver</a>
					</label>
				</div>
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
		</script>
	</body>
</html>