<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<label>Área </label> <?php echo "$area->codigo - $area->descripcion"; ?>
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
						<h3 class="box-title">Información Área</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12"><label>Repartición:</label> <?php echo $area->reparticion; ?></div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-primary" href="areas/area/editar/<?php echo $area->id; ?>">
							<i class="fa fa-edit" id="btn-editar"></i> Editar
						</a>
						<a class="btn btn-primary" href="areas/personal/listar/<?php echo $area->id; ?>">
							<i class="fa fa-users" id="btn-personal"></i> Personal
						</a>
					</div>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Sub Áreas</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<?php echo $js_table; ?>
								<?php echo $html_table; ?>
							</div>
						</div>
					</div>
					<div class="box-footer">
					</div>
				</div>
			</div>
		</div>
	</section>
</div>