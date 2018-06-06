<?php if (!empty($escuelas_p)): ?>
	<?php if ($supervision->nivel_id === '2' && $supervision->dependencia_id === '1'/* && ENVIRONMENT !== 'production' */): ?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Ingreso a 1° Grado - Ciclo Lectivo 2018</h3>
					&nbsp;
					<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/modal_consultar_estado"><i class="fa fa-search"></i> Consulta de Alumnos</a>
					<a class="btn btn-primary btn-xs" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="preinscripciones/escuela/modal_vacantes_generales"><i class="fa fa-search"></i> Consulta de Escuelas con Vacantes</a>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
				<div class="box-body ">
					<table class="table table-bordered table-condensed table-striped table-hover">
						<?php $fecha = date('Y-m-d'); ?>
						<tr>
							<th rowspan="2" style="text-align:center;">Escuela</th>
							<th colspan="2" style="text-align:center;">1° Grado 2017</th>
							<th colspan="6" style="text-align:center;">Preinscripción 2018</th>
							<th style="text-align:right;">
								<?php if ($fecha >= $preinscripcion_instancias[2]->desde): ?>
									<a class="btn btn-xs bg-green" href="preinscripciones/escuela/anexo4_imprimir_pdf/<?php echo $supervision->id ?>" title="Exportar Pdf Anexo IV" target="_blank"><i class="fa fa-file-pdf-o"></i> Anexo IV
									</a>
								<?php endif; ?>
							</th>
						</tr>
						<tr>
							<th style="text-align:center;">Divisiones</th>
							<th style="text-align:center;">Alumnos</th>
							<th style="text-align:center;">Vacantes declaradas<br>(<?php echo(new DateTime($preinscripcion_instancias[0]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[0]->hasta))->format('d/m'); ?>)</th>
							<th style="text-align:center;">Inscriptos</th>
							<th style="text-align:center;">Vacantes finales</th>
							<th style="text-align:center;">1° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[1]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[1]->hasta))->format('d/m'); ?>)</th>
							<th style="text-align:center;">2° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[2]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[2]->hasta))->format('d/m'); ?>)</th>
							<th style="text-align:center;">3° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[3]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[3]->hasta))->format('d/m'); ?>)</th>
							<th style="text-align:center;">4° Inst.<br>(<?php echo(new DateTime($preinscripcion_instancias[4]->desde))->format('d/m'); ?> - <?php echo(new DateTime($preinscripcion_instancias[4]->hasta))->format('d/m'); ?>)</th>
							<th style="text-align:center;">Excedentes/<br>Derivados</th>
						</tr>
						<?php foreach ($escuelas_p as $escuela): ?>
							<tr>
								<td style="width: 40%;"><?php echo $escuela->numero . ($escuela->anexo === '0' ? '' : "/$escuela->anexo") . " $escuela->nombre"; ?></td>
								<td style="width: 5%;"class="text-center"><?php echo $escuela->divisiones; ?></td>
								<td style="width: 5%;" class="text-center"><?php echo $escuela->alumnos; ?></td>
								<?php if (empty($escuela->vacantes)): ?>
									<td class="text-center" colspan="7"> -- Sin vacantes cargadas -- <a data-remote="false" data-toggle="modal" class="pull-right" data-target="#remote_modal" href="preinscripciones/escuela/instancia_0/2018/<?php echo $escuela->id; ?>/supervision"><i class="fa fa-edit"></i></a>
									</td>
								<?php else: ?>
									<td style="width: 10%;" class="text-center">
										<?php if ($fecha >= $preinscripcion_instancias[0]->desde): ?>
											<?php echo $escuela->vacantes; ?><a data-remote="false" data-toggle="modal" class="pull-right" data-target="#remote_modal" href="preinscripciones/escuela/<?php echo (!empty($escuela->preinscripcion_id)) ? 'editar_vacantes/' . $escuela->preinscripcion_id : '' ?>/supervision"><i class="fa fa-edit"></i></a>
										<?php endif; ?>
									</td>
									<td style="width: 5%;" class="text-center"><?php echo!empty($escuela->inscriptos) ? $escuela->inscriptos : 0; ?></td>
									<td style="width: 5%;" class="text-center"><?php echo $escuela->vacantes - $escuela->inscriptos; ?></td>
									<td style="width: 10%;" class="text-center">
										<?php if ($fecha >= $preinscripcion_instancias[1]->desde): ?>
											<?php if ($fecha <= $preinscripcion_instancias[1]->hasta): ?>
												<?php echo!empty($escuela->instancia_1) ? $escuela->instancia_1 : 0; ?>
												<?php if (isset($this->instancias['1'])): ?>
													<a class="pull-right" href="preinscripciones/escuela/instancia_<?php echo "1/2018/$escuela->id"; ?>/supervision"><i class="fa fa-edit"></i></a>
												<?php endif; ?>
											<?php else: ?>
												<?php echo!empty($escuela->instancia_1) ? $escuela->instancia_1 : 0; ?>
												<?php if (isset($this->instancias['1'])): ?>
													<a class="pull-right" href="preinscripciones/escuela/instancia_<?php echo "1/2018/$escuela->id"; ?>/supervision"><i class="fa fa-search"></i></a>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
									<td style="width: 10%;" class="text-center">
										<?php if ($fecha >= $preinscripcion_instancias[2]->desde): ?>
											<?php if ($fecha <= $preinscripcion_instancias[2]->hasta): ?>
												<?php echo!empty($escuela->instancia_2_i) ? $escuela->instancia_2_i : 0; ?>
												<?php if (isset($this->instancias['1'])): ?>
													<a class="pull-right" href="preinscripciones/escuela/instancia_<?php echo "2/2018/$escuela->id"; ?>/supervision"><i class="fa fa-edit"></i></a>
												<?php endif; ?>
											<?php else: ?>
												<?php echo!empty($escuela->instancia_2_i) ? $escuela->instancia_2_i : 0; ?>
												<?php if (isset($this->instancias['1'])): ?>
													<a class="pull-right" href="preinscripciones/escuela/instancia_<?php echo "2/2018/$escuela->id"; ?>/supervision"><i class="fa fa-search"></i></a>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
									<td style="width: 10%;" class="text-center">
										<?php if ($fecha >= $preinscripcion_instancias[3]->desde): ?>
											<?php if ($fecha <= $preinscripcion_instancias[3]->hasta): ?>
												<?php echo!empty($escuela->instancia_3) ? $escuela->instancia_3 : 0 - !empty($escuela->instancia_3_d) ? $escuela->instancia_3_d : 0; ?>
												<?php if (isset($this->instancias['1'])): ?>
													<a class="pull-right" href="preinscripciones/escuela/instancia_<?php echo "3/2018/$escuela->id"; ?>/supervision"><i class="fa fa-edit"></i></a>
												<?php endif; ?>
											<?php else: ?>
												<?php echo!empty($escuela->instancia_3) ? $escuela->instancia_3 : 0 - !empty($escuela->instancia_3_d) ? $escuela->instancia_3_d : 0; ?>
												<?php if (isset($this->instancias['1'])): ?>
													<a class="pull-right" href="preinscripciones/escuela/instancia_<?php echo "3/2018/$escuela->id"; ?>/supervision"><i class="fa fa-search"></i></a>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
									<td style="width: 10%;" class="text-center">
										<?php if ($fecha >= $preinscripcion_instancias[4]->desde): ?>
											<?php if ($fecha <= $preinscripcion_instancias[4]->hasta): ?>
												<?php echo!empty($escuela->instancia_4) ? $escuela->instancia_4 : 0; ?>
												<?php if (isset($this->instancias['4'])): ?>
													<a class="pull-right" href="preinscripciones/escuela/instancia_<?php echo "4/2018/$escuela->id"; ?>/supervision"><i class="fa fa-edit"></i></a>
												<?php endif; ?>
											<?php else: ?>
												<?php echo!empty($escuela->instancia_4) ? $escuela->instancia_4 : 0; ?>
												<?php if (isset($this->instancias['4'])): ?>
													<a class="pull-right" href="preinscripciones/escuela/instancia_<?php echo "4/2018/$escuela->id"; ?>/supervision"><i class="fa fa-search"></i></a>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
									<td style="width: 5%;" class="text-center">
										<?php echo!empty($escuela->instancia_2_p) ? $escuela->instancia_2_p : 0; ?>/<?php echo!empty($escuela->instancia_3_d) ? $escuela->instancia_3_d : 0; ?>
									</td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
	<?php endif; ?>
<?php endif; ?>