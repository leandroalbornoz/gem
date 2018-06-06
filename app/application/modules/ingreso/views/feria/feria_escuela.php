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
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Feria Educativa - Escuelas
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
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="ingreso/feria/escuela/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<a class="btn btn-app btn-app-zetta" href="ingreso/feria/escuela_editar/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-edit"></i> Editar
						</a>
						<a class="btn btn-app btn-app-zetta" href="ingreso/feria/escuela_editar_area_interes/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-edit"></i> Áreas de Interés
						</a>
						<a class="btn btn-app btn-app-zetta" href="ingreso/feria/escuela_asignaciones/<?php echo (!empty($escuela->id)) ? $escuela->id : ''; ?>">
							<i class="fa fa-user"></i> Asignaciones
						</a>
						<hr style="margin: 10px 0;">
						<div class="row">

							<div class="col-md-9">
								<div class="row">
									<div class="form-group col-md-2">
										<?php echo $fields['numero']['label']; ?>
										<?php echo $fields['numero']['form']; ?>
									</div>
									<div class="form-group col-md-1">
										<?php echo $fields['anexo']['label']; ?>
										<?php echo $fields['anexo']['form']; ?>
									</div>
									<div class="form-group col-md-5">
										<?php echo $fields['nombre']['label']; ?>
										<?php echo $fields['nombre']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['linea']['label']; ?>
										<?php echo $fields['linea']['form']; ?>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-3">
										<?php echo $fields['calle']['label']; ?>
										<?php echo $fields['calle']['form']; ?>
									</div>
									<div class="form-group col-md-1">
										<?php echo $fields['calle_numero']['label']; ?>
										<?php echo $fields['calle_numero']['form']; ?>
									</div>
									<div class="form-group col-md-3">
										<?php echo $fields['barrio']['label']; ?>
										<?php echo $fields['barrio']['form']; ?>
									</div>
									<div class="form-group col-md-4">
										<?php echo $fields['localidad']['label']; ?>
										<?php echo $fields['localidad']['form']; ?>
									</div>
									<div class="form-group col-md-1">
										<?php echo $fields['codigo_postal']['label']; ?>
										<?php echo $fields['codigo_postal']['form']; ?>
									</div>
									<div class="form-group col-md-2">
										<?php echo $fields['telefono']['label']; ?>
										<?php echo $fields['telefono']['form']; ?>
									</div>
									<div class="form-group col-md-4">
										<?php echo $fields['email']['label']; ?>
										<?php echo $fields['email']['form']; ?>
									</div>
									<div class="form-group col-md-4">
										<?php echo $fields['email2']['label']; ?>
										<?php echo $fields['email2']['form']; ?>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="box-body" style="padding: 0px;">
									<div class="box-group">
										<div class="panel box box-success">
											<div class="box-header with-border">
												<h4 class="box-title">
													Áreas de Interés
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
						</div>
<!--						<br>
						<div id="googleMap" style="width:100%;height:400px;"></div>-->
						<br>
						<div class="nav-tabs-custom" style="margin-bottom: 0px;">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_videos" data-toggle="tab"><i class="fa fa-video-camera" aria-hidden="true" style="float: left; margin-top: 3px; margin-right: 8px;"></i>Galería de Videos</a></li>
								<li class=""><a href="#tab_texto" data-toggle="tab"><i class="fa fa-file-text-o" aria-hidden="true" style="float: left; margin-top: 3px; margin-right: 8px;"></i>Galería de Texto</a></li>
								<li class=""><a href="#tab_imagines" data-toggle="tab"><i class="fa fa-picture-o" aria-hidden="true" style="float: left; margin-top: 3px; margin-right: 8px;"></i>Galería de Imagines</a></li>
							</ul>
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="tab_videos">
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
								<div role="tabpanel" class="tab-pane" id="tab_texto">
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
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="ingreso/feria/listar" title="<?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?>"><?php echo empty($txt_btn) ? 'Volver' : 'Cancelar'; ?></a>
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
<!--<script>
function myMap() {
var mapProp= {
    center:new google.maps.LatLng(-32.898356, -68.846466),
    zoom:17,
};
var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCM8xlHt7N6VZuYbb5W8TIl8vFMnDA796k&callback=myMap"></script>-->
