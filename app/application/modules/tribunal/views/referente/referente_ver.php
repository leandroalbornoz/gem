<script>
	$(document).ready(function() {
		$('#cuil').inputmask('99-99999999-9');
	});
</script>
<style>
	td.child>ul{
		width:100%;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Referente<?php if (!empty($referente_vigencia->fecha_hasta)): ?><span style="color: red"> - Período cerrado - </span><?php endif; ?>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="<?php echo "tribunal/escritorio/listar/"; ?>">Escuelas</a></li>
			<li><a href="<?php echo "tribunal/escritorio/escuela/$escuela->id"; ?>">Tribunales</a></li>
			<li><a href="<?php echo "tribunal/referente/ver/$referente_vigencia->id"; ?>">Referente</a></li>
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
					<?php echo form_open(uri_string(), array('data-toggle' => 'validator')); ?>
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta <?php echo $class['ver']; ?>" href="tribunal/referente/ver/<?php echo $referente->id; ?>">
							<i class="fa fa-search"></i> Ver
						</a>
						<?php if (empty($referente_vigencia->fecha_hasta)): ?>
							<a class="btn  bg-red btn-app btn-app-zetta <?php echo $class['editar']; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"  href="tribunal/referente/cerrar_periodo/<?php echo $referente_vigencia->id; ?>">
								<i class="fa fa-lock"></i> Cerrar Período
							</a>
						<?php endif; ?>
						<hr style="margin: 10px 0;">
						<table class="table table-condensed table-bordered table-striped table-hover">
							<tr>
								<th colspan="9" style="text-align:center;">Datos del referente</th>
							</tr>
							<tr>
								<th>Apellido y nombre</th>
								<th>DNI</th>
								<th>CUIL</th>
								<th>Email</th>
								<th>Teléfono</th>
								<th>Domicilio real</th>
								<th>Domicilio legal</th>
							</tr>
							<tr>
								<td><?php echo "$persona->apellido, $persona->nombre"; ?></td>
								<td><?php echo "$persona->documento_tipo $persona->documento"; ?></td>
								<td><?php echo "$persona->cuil"; ?></td>
								<td><?php echo "$persona->email"; ?></td>
								<td><?php echo "$persona->telefono_movil"; ?></td>
								<td></td>
								<td></td>
							</tr>
						</table>
						<br>
						<table class="table table-condensed table-bordered table-striped table-hover">
							<tr>
								<th colspan="9" style="text-align:center;">Datos del periodo</th>
							</tr>
							<tr>
								<th>Escuela Nº</th>
								<th>Cargo</th>
								<th>Periodo desde</th>
								<th>Periodo hasta</th>
							</tr>
							<tr>
								<td><?php echo "$escuela->numero - $escuela->nombre"; ?></td>
								<td><?php echo "$regimen->descripcion"; ?></td>
								<td><?php echo empty($referente_vigencia->fecha_desde) ? "" : (new DateTime($referente_vigencia->fecha_desde))->format('d/m/Y'); ?></td>
								<td><?php echo empty($referente_vigencia->fecha_hasta) ? "" : (new DateTime($referente_vigencia->fecha_hasta))->format('d/m/Y'); ?></td>
							</tr>
						</table>
						<hr style="margin: 10px 0;">
						<h3><u>Último cheque</u>
							<?php if (empty($referente_vigencia->fecha_hasta)): ?>
								<a class="btn btn btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="tribunal/referente/modal_ultimo_cheque/<?php echo $referente_vigencia->id; ?>"><i class="fa fa-plus"></i>&nbsp; Cargar</a>
							<?php endif; ?> 
						</h3>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields_vc['numero']['label']; ?>
								<?php echo $fields_vc['numero']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields_vc['importe']['label']; ?>
								<?php echo $fields_vc['importe']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields_vc['fecha']['label']; ?>
								<?php echo $fields_vc['fecha']['form']; ?>
							</div>
						</div>
						<h3><u>Saldo de cuenta bancaria</u>
							<?php if (empty($referente_vigencia->fecha_hasta)): ?>
								<a class="btn btn btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="tribunal/referente/modal_saldo_cuenta/<?php echo $referente_vigencia->id; ?>"><i class="fa fa-plus"></i>&nbsp; Cargar</a>
							<?php endif; ?> 
						</h3>
						<div class="row">
							<div class="form-group col-md-3">
								<?php echo $fields_vs['saldo']['label']; ?>
								<?php echo $fields_vs['saldo']['form']; ?>
							</div>
							<div class="form-group col-md-3">
								<?php echo $fields_vs['fecha']['label']; ?>
								<?php echo $fields_vs['fecha']['form']; ?>
							</div>
						</div>
						<h3><u>Fondos pendientes</u> 
							<?php if (empty($referente_vigencia->fecha_hasta)): ?>
								<a class="btn btn btn-primary" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="tribunal/referente/modal_fondos_pendientes/<?php echo $referente_vigencia->id; ?>"><i class="fa fa-plus"></i>&nbsp; Cargar</a>
							<?php endif; ?> 
						</h3>
						<div class="row">
							<div class="col-xs-12">
								<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
									<thead>
										<tr>
											<th>Fecha de transferencia</th>
											<th>Concepto</th>
											<th>Importe</th>
											<?php if (empty($referente_vigencia->fecha_hasta)): ?>
												<th></th>
											<?php endif; ?>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($fondos_pendientes)): ?>
											<?php foreach ($fondos_pendientes as $fondo): ?>
												<tr>
													<td><?= empty($fondo->fecha_transferencia) ? '' : (new DateTime($fondo->fecha_transferencia))->format('d/m/Y'); ?></td>
													<td><?= $fondo->concepto; ?></td>
													<td><?= $fondo->importe; ?></td>
													<?php if (empty($referente_vigencia->fecha_hasta)): ?>
														<td class="text-center">
																<a class="btn btn-xs btn-warning" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="tribunal/referente/modal_fondos_pendientes_editar/<?php echo $fondo->id; ?>" title="Editar"><i class="fa fa-edit"></i></a>
																<a class="btn btn-xs btn-danger" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="tribunal/referente/modal_fondos_pendientes_eliminar/<?php echo $fondo->id; ?>" title="Eliminar"><i class="fa fa-remove"></i></a>
														</td>
													<?php endif; ?>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td style="text-align: center;" colspan="4">
													-- Sin fondos pendientes --
												</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<a class="btn btn-default" href="tribunal/escritorio/escuela/<?php echo $escuela->id; ?>" title="Volver">Volver</a>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		$('#servicio_table,#alumno_table,#reporte_horario_table').DataTable({
			dom: 't',
			autoWidth: false,
			paging: false,
			ordering: false
		});
	});
</script>
