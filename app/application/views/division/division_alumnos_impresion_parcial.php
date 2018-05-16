<div class="content-wrapper">
	<section class="content-header">
		<div role="tabpanel" class="tab-pane" id="tab_alumnos">
			<div style="margin-top: 3%">
				<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
					<thead>
						<tr style="background-color: #f4f4f4" >
							<th style="text-align: center;" colspan="6">
								Alumnos
						</tr>
						<tr>
							<th>C. lectivo</th>
							<th>Documento</th>
							<th>Persona</th>
							<th>F. Nacimiento</th>
							<th>Sexo</th>
							<th>Desde</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($alumnos)): ?>
							<?php foreach ($alumnos as $campo): ?>
								<tr>
									<td><?= $campo->ciclo_lectivo; ?></td>
									<td><?php echo "$campo->documento_tipo $campo->documento"; ?></td>
									<td><?= $campo->persona; ?></td>
									<td><?= empty($campo->fecha_nacimiento) ? '' : (new DateTime($campo->fecha_nacimiento))->format('d/m/Y'); ?></td>
									<td><?php if($campo->sexo === 'Masculino'){
																	echo "M";}else{ echo "F";}?>
									</td>
									<td><?= empty($campo->fecha_desde) ? '' : (new DateTime($campo->fecha_desde))->format('d/m/Y'); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td>-- No tiene --</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>