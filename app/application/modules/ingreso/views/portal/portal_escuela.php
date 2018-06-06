<style>
	.nav-tabs-custom {
    margin-bottom: none;
    background: #fff;
    box-shadow: none; 
    border-radius: none; 
	}
	.nav-tabs-custom>.tab-content {
		padding: 0px; 
	}
	.nav-tabs-custom>.nav-tabs {
    border-bottom-color: #3c8dbc;
	}
	.nav-tabs-custom>.nav-tabs>li {
    border: 3px solid transparent;
		border-top-color: #3c8dbc4f;
    border-right-color: #3c8dbc4f;
    border-left-color: #3c8dbc4f;
    border-radius: 4px;
	}
	.nav-tabs-custom>.nav-tabs>li>a{
		color: #4444449e;
	}
	.nav-tabs-custom>.nav-tabs>li>a>.fa{
		color: #c2dcea !important;
	}
	.nav-tabs-custom>.nav-tabs>li.active {
    border-top-color: #3c8dbc;
    border-right-color: #3c8dbc;
    border-left-color: #3c8dbc;
    border-bottom-color: #fff;
    border-radius: 4px;
	}
	.nav-tabs-custom>.nav-tabs>li.active>a>.fa {
		color: #3c8dbc !important;
	}
</style>
<style>
	.table>tbody+tbody {
    border-top: 1px solid #ddd;
	}
	.dia_0,.dia_6{
		background-color: lightgray;
	}
	.mes_asistencia{
		width: 50px;
		height: 50px;
		border: darkgray thin solid;
		border-radius: 5px;
		margin-right: 5px;
		text-align: center;
		font-weight: bold;
	}
	.mes_asistencia.active{
		background-color: #00c0ef;
		border-color: #0044cc;
	}
	.info-box-icon{
		float: left;
    height: 40px;
    width: 40px;
    text-align: center;
    font-size: 32px;
    line-height: 40px;
	}
	.badge{
		width:40px;
	}
	.cchild {
		display: none;
	}
	.open>.cchild {
		display: table-row;
	}
	.parent {
		cursor: pointer;
	}
	.parent > *:last-child {
		width: 30px;
	}
	.parent i {
		transform: rotate(0deg);
		transition: transform .3s cubic-bezier(.4,0,.2,1);
		margin: -.5rem;
		padding: .5rem;
	}
	.open>.parent i {
		transform: rotate(180deg);
	}
	.open>.parent>td>span>.fa-minus{
		display: inline !important;
	}
	.open>.parent>td>span>.fa-plus{
		display: none;
	}
	.nav-tabs-custom>.nav-tabs {
    border-bottom-color: #3c8dbc;
	}
	.nav-tabs-custom>.nav-tabs>li {
    border: 3px solid transparent;
		border-top-color: #3c8dbcb8;
    border-right-color: #3c8dbcb8;
    border-left-color: #3c8dbcb8;
    border-radius: 4px;
	}
	.nav-tabs-custom>.nav-tabs>li>a>.fa{
		color: #3c8dbcb8 !important;
	}
	.nav-tabs-custom>.nav-tabs>li.active {
    border-top-color: #3c8dbc;
    border-right-color: #3c8dbc;
    border-left-color: #3c8dbc;
    border-bottom-color: #fff;
    border-radius: 4px;
	}
	.nav-tabs-custom>.nav-tabs>li>a {
    color: #444444b5;
	}
	.nav-tabs-custom>.nav-tabs>li.active>a>.fa {
		color: #3c8dbc !important;
	}
	.text-muted span{
		color: #f39c12 !important;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<b>Feria Educativa - Escuela</b>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo $controlador; ?>">Escuelas</a></li>
			<li class="active"><?php echo ucfirst($metodo); ?></li>
		</ol>
	</section>
	<section class="content">
		<?php if (!empty($error)) : ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-ban"></i> Error!</h4>
				<?php echo $error; ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($message)) : ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> OK!</h4>
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-md-3">
				<div class="box box-primary">
					<div class="box-body box-profile">
						<img class="profile-user-img img-responsive img-circle" src="img/generales/usuario.png" alt="User profile picture">
						<h3 class="profile-username text-center"><strong><?= "$escuela->numero - $escuela->nombre."; ?></strong></h3>
						<div class="box-body" style="font-size: 16px;">
							<strong><i class="fa fa-building"></i> Datos generales</strong>
							<p class="text-muted">
								<?= (!empty($escuela->numero)) ? "Número: $escuela->numero" : "<br>Cuil: <span>No cargado</span>"; ?>
								<?= (!empty($escuela->documento_tipo) || !empty($escuela->documento)) ? "<br>Documento: $escuela->documento_tipo $escuela->documento" : "<br>Documento: <span>No cargado</span>"; ?>
								<?= (!empty($escuela->fecha_nacimiento)) ? "<br>Fecha de nacimiento: " . (new DateTime($escuela->fecha_nacimiento))->format('d/m/Y') : "<br>Fecha de nacimiento: <span>No cargado</span>"; ?>
								<?= (!empty($escuela->sexo)) ? "<br>Sexo: $escuela->sexo" : "<br>Sexo: <span>No cargado</span>"; ?>
								<?= (!empty($escuela->nacionalidad)) ? "<br>Nacionalidad: $escuela->nacionalidad" : "<br>Nacionalidad: <span>No cargado</span>"; ?>
							<hr>	
							<strong><i class="fa fa-map-marker margin-r-5"></i> Datos domicilio</strong>
							<p class="text-muted">

								<?php if (isset($escuela->calle) && !empty($escuela->calle)): ?>
									<?= (!empty($escuela->calle)) ? "Calle: $escuela->calle" : "Calle: <span>No cargado</span>" ?>
									<?= (!empty($escuela->calle_numero)) ? "<br>Número: $escuela->calle_numero" : "<br>Número: <span>No cargado</span>"; ?>
								<?php endif; ?>
								<?php if (isset($escuela->barrio) && !empty($escuela->barrio)): ?>
									<?= (!empty($escuela->calle) ? "<br>" : "" ); ?>
									<?= (!empty($escuela->barrio)) ? "Barrio: $escuela->barrio" : "<br>Barrio: <span>No cargado</span>"; ?>
									<?= (!empty($escuela->manzana)) ? "<br>M: $escuela->manzana" : "<br>M: <span>No cargado</span>"; ?> 
									<?=
									(!empty($escuela->casa)) ? " C: $escuela->casa" : " C: <span>No cargado</span>";
								endif;
								?>

								<?php if (isset($escuela->departamento) && !empty($escuela->departamento)) : ?>
									<?= (!empty($escuela->departamento)) ? "<br>Depto: $escuela->departamento" : "<br>Depto: <span>No cargado</span>"; ?><?=
									(!empty($escuela->piso)) ? " Piso: $escuela->piso" : " Piso: <span>No cargado</span>";
								endif;
								?>
								<?= (!empty($escuela->localidad)) ? "<br>Localidad: $escuela->localidad" : "<br>Localidad: <span>No cargado</span>"; ?>
							</p>
							<hr>
							<strong><i class="fa fa-phone"></i> Datos contacto</strong>
							<p class="text-muted">
								<?= (!empty($escuela->telefono)) ? "Teléfono Fijo: $escuela->telefono" : "Teléfono: <span>No cargado</span>"; ?>
							</p>
							<hr>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><b>Feria</b></h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="nav-tabs-custom" style="margin-bottom: 0px;">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab_texto" data-toggle="tab"><i class="fa fa-file-text-o" aria-hidden="true" style="float: left; margin-top: 3px; margin-right: 8px;"></i>Galería de Texto</a></li>
							<li class=""><a href="#tab_imagines" data-toggle="tab"><i class="fa fa-picture-o" aria-hidden="true" style="float: left; margin-top: 3px; margin-right: 8px;"></i>Galería de Imagines</a></li>
							<li class=""><a href="#tab_videos" data-toggle="tab"><i class="fa fa-video-camera" aria-hidden="true" style="float: left; margin-top: 3px; margin-right: 8px;"></i>Galería de Videos</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="tab_texto">
								<div class="row">
									<div class="col-sm-12">
										<div class="tab-pane active" id="activity">
											<br>
											<?php if (!empty($lista_texto)): ?>
												<?php foreach ($lista_texto as $texto): ?>
													<div class="col-md-12">
														<h1><?php echo $texto->encabezado; ?></h1>
														<hr style="margin-top: 10px;">
														<?php echo $texto->texto; ?>
														<br>
													</div>
												<?php endforeach; ?>
											<?php else: ?>
												<h4 class="text-center">No hay textos agregadas.</h4>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_imagines">
								<div class="row">
									<div class="col-sm-12">
										<div class="tab-pane active" id="activity">
											<br>
											<?php if (!empty($lista_imagen)): ?>
												<?php foreach ($lista_imagen as $imagen): ?>
													<div class="col-md-2">
														<div class="box box-solid">
															<div class="box-body">
																<a rel="prettyPhoto[galleryImagenes]" title="<?php echo $imagen->pie; ?>" href="uploads/feria/989/fullscreen/<?php echo $imagen->path; ?>">
																	<img class="img-fluid" src="uploads/feria/989/thumbnails/<?php echo $imagen->path; ?>" alt="<?php echo $imagen->pie; ?>" height="100px"> 
																</a>
															</div>
														</div>
													</div>
												<?php endforeach; ?>
											<?php else: ?>
												<h4 class="text-center">No hay imagenes agregadas.</h4>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab_videos">
								<div class="row">
									<div class="col-sm-12">
										<div class="tab-pane active" id="activity">
											<br>
											<?php if (!empty($lista_video)): ?>
												<?php foreach ($lista_video as $video): ?>
													<div class="col-md-2">
														<div class="box box-solid">
															<div class="box-body">
																<a rel="prettyPhoto[galleryVideos]" title="<?php echo $video->pie; ?>" href="<?php echo $video->path; ?>">
																	<img class="img-fluid" src="https://img.youtube.com/vi/<?php echo $video->thumbnail; ?>/default.jpg" alt="<?php echo $video->pie; ?>" height="100px"> 
																</a>  
															</div>
														</div>
													</div>
												<?php endforeach; ?>
											<?php else: ?>
												<h4 class="text-center">No hay imagenes agregadas.</h4>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>			
				</div>
			</div>
			<div class="col-md-5">
				<div class="box-body" style="padding: 0px;">
					<div class="box-group">
						<div class="panel box box-success">
							<div class="box-header with-border">
								<h4 class="box-title">
									<b>Áreas de Interés</b>
								</h4>
							</div>
							<div>
								<div class="box-body">
									<ul>
										<?php foreach ($lista_areas as $areas): ?>
											<li><?php echo $areas->descripcion; ?></li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="box-body" style="padding: 0px;">
					<div class="box-group">
						<div class="panel box box-warning">
							<div class="box-header with-border">
								<h4 class="box-title">
									<b>Ubicación Geográfica</b>
								</h4>
							</div>
							<div>
								<div class="box-body">
									<div id="googleMap" style="width:100%;height:200px;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$("a[rel^='prettyPhoto']").prettyPhoto();

		//		$(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: true});
		//		$(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
		//
		//		$("#custom_content a[rel^='prettyPhoto']:first").prettyPhoto({
		//			custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
		//			changepicturecallback: function(){ initialize(); }
		//		});
		//
		//		$("#custom_content a[rel^='prettyPhoto']:last").prettyPhoto({
//					custom_markup: '<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
		//			changepicturecallback: function(){ _bsap.exec(); }
		//		});
	});
</script>
<script>
function myMap() {
var mapProp= {
    center:new google.maps.LatLng(-32.898356, -68.846466),
    zoom:17,
};
var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCM8xlHt7N6VZuYbb5W8TIl8vFMnDA796k&callback=myMap"></script>
