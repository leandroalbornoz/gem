<div class="content-wrapper">
	<section class="content-header">
		<div role="tabpanel" class="tab-pane" id="tab_alumnos">
			<div style="margin-top: 3%">
				<table class="table table-hover  table-condensed dt-responsive dataTable no-footer dtr-inline">
					<tr>
						<td style="width:70px; text-align: center;"><img style="font-family: sans-serif; font-size:20px; color: #3c8dbc;" alt="Logo DGE" src="<?php echo BASE_URL; ?>img/generales/logo-dge-sm.png" height="70" width="70"></td>
						<th style="text-align: center;" colspan="4"><u><?php echo "Esc. Nº: $escuela->numero $escuela->nombre";?></u><br><u>Clave de cursos y divisiones para el Portal Alumnos</u></th>
						<td style="width:70px; text-align: center;"><img style="font-family: sans-serif; font-size:20px; color: #3c8dbc;" alt="Logo GEM" src="<?php echo BASE_URL; ?>img/generales/logo-login.png" height="70" width="70"></td>
					</tr>
				</table>
				<table class="table table-hover table-bordered table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
					<thead>
						<tr>
							<th>Curso</th>
							<th>División</th>
							<th>Turno</th>
							<th>Clave portal</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($divisiones)): ?>
							<?php foreach ($divisiones as $division): ?>
								<tr>
									<td><?= $division->curso; ?></td>
									<td><?= $division->division; ?></td>
									<td><?= $division->turno; ?></td>
									<td style="text-align: center"><?= $division->clave; ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="4">-- No tiene --</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>