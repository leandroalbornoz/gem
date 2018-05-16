<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Escritorio Administrador de Juntas
		</h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-home"></i> Inicio</li>
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
			<div class="col-sm-12 col-md-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Estadísticas</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php foreach ($indices as $indice_titulo => $indice): ?>
							<div class="progress-group">
								<span class="progress-text"><a style="color: black" href="<?= $indice->link ?>"><?php echo $indice_titulo; ?></a></span>
								<span class="progress-number"><b><?php echo ($indice->cantidad); ?></b>/<?php echo $indice->total; ?></span>
								<div class="progress sm">
									<?php $cumplimiento = $indice->total == 0 ? 0 : (round($indice->cantidad / $indice->total * 100)); ?>
									<div class="progress-bar progress-bar-<?php echo $cumplimiento >= 100 ? 'green' : ($cumplimiento >= 50 ? 'yellow' : 'red'); ?>" style="line-height:10px;font-size:10px; min-width: 2em; width: <?php echo $cumplimiento; ?>%"><?php echo $cumplimiento; ?>%</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Alertas</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<?php foreach ($alertas as $alerta): ?>
							<div class="progress-group">
								<table class="table table-bordered table-condensed table-striped table-hover">
									<tbody>
										<tr>
											<td class="progress-text"><?= $alerta->indice; ?></td>
											<td class="pull-right"><b><?= $alerta->cantidad; ?></b></td>
										</tr>
									</tbody>
								</table>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
</div>
</section>
</div>

