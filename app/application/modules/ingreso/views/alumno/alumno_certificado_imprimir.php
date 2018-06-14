<div id="print_area">
	<div style="width: 100%; position: relative;" >
		<?php foreach ($alumnos_certificados as $alumno_certificado): ?>
			<div class="" style="font-size: 11px; position: absolute; width: 47%;margin-left: 1%; margin-right: 1%; border: 1px solid; padding: 5px; margin-bottom: 10px; display: table-row; float: left; height: 250px;">
				<img src="<?php echo base_url('/img/banner_ingreso_new.png'); ?>" width="100%">
				<div>
					<h4 style="text-align: center; font-size: 14px"><b>CONSTANCIA DE ALUMNO REGULAR <br> 7º AÑO <?= date('Y'); ?>.</b></h4>
				</div>
				<div style="margin-bottom: 5px;">
					<b>ESCUELA:</b> N°<?php echo $alumno_certificado[0]->numero; ?> -		<?php echo $alumno_certificado[0]->escuela_nombre; ?>
				</div>
				<div style="margin-bottom: 5px;">
					<b>DEPARTAMENTO:</b> <?php echo $alumno_certificado[0]->localidad_escuela; ?>
				</div>
				<div style="margin-bottom: 5px;">
					<b>ALUMNO:</b> <?php echo mb_substr($alumno_certificado[0]->apellido . ', ' . $alumno_certificado[0]->nombre, 0, 35); ?>
				</div>
				<div style="margin-bottom: 5px;">
					<b>DNI:</b> <?php echo $alumno_certificado[0]->documento; ?> &nbsp;&nbsp;&nbsp;<b>PROMEDIO 6º:</b> <?php echo $alumno_certificado[0]->promedio; ?>
				</div>
				<div style="margin-bottom: 5px;">
					<b>DOMICILIO:</b> <?php echo mb_substr($alumno_certificado[0]->calle, 0, 33); ?><br>
				</div>
				<div style="margin-bottom: 5px;">
					<b>LOCALIDAD:</b> <?php echo $alumno_certificado[0]->localidad_persona; ?> - <?php echo $alumno_certificado[0]->localidad_persona; ?>
				</div>
				<?php if ($alumno_certificado[0]->abanderado == 'Si'): ?>
					<div style="margin-bottom: 5px;">
						<b>ABANDERADO NACIONAL / PROVINCIAL / ESCOLTA TITULAR</b>
						<p style="text-align: center;margin-bottom: 0px;"><small>(tachar lo que no corresponda)</small></p>
					</div>
				<?php else: ?>
					<div style="margin-bottom: 5px;">
						<b>&nbsp;</b>
						<p style="text-align: center;margin-bottom: 0px;">&nbsp;</p>
					</div>
				<?php endif; ?>
				<?php if ($alumno_certificado[0]->verificar == 'Si'): ?>
					<div style="margin-bottom: 5px;">
						<b>Acompañamiento de la Modalidad de Educación Especial</b>
					</div>
				<?php else: ?>
					<div style="margin-bottom: 5px;">
						<b>&nbsp;</b>
					</div>
				<?php endif; ?>
				<br>
				<div style="width: 100%; position: relative;">
					<div style="position: absolute; float: left; display: inline-block; width: 28%; margin-right: 2%; text-align: center;">
						<div>........................</div>
						<div style="font-size: 10px">FIRMA DOCENTE</div>
					</div>
					<div style="position: absolute; float: left; display: inline-block; width: 28%; margin-left: 30px; text-align: center;">
						<div>........................</div>
						<div style="font-size: 10px">SELLO INSTITUCIÓN</div>
					</div>
					<div style="position: absolute; float: right; display: inline-block; width: 28%; margin-left: 2%; text-align: center;">
						<div>........................</div>
						<div style="font-size: 10px">FIRMA DIRECTOR/A</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
