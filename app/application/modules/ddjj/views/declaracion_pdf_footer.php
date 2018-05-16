<table class="table table-hover table-condensed dt-responsive dataTable no-footer dtr-inline" role="grid">
	<tr>
		<td style="width: 30%;">
			<span style="color:red; font-size: 12px;">* Información según la liquidación pagada en <?= $this->nombres_meses[(new DateTime(AMES_LIQUIDACION . '01'))->format('m')] . ' de ' . (new DateTime(AMES_LIQUIDACION . '01'))->format('Y'); ?></span>
		</td>
		<td style="width: 40%; text-align: center;">
			<!--<barcode code="http://testing.mendoza.edu.ar/gem/ddjj/declaracion/ddjj/<?= $persona->cuil; ?>" type="QR" class="barcode" size="0.8" error="M"/>-->
			<barcode code="<?= "$persona->cuil $persona->persona"; ?>" type="QR" class="barcode" size="0.8" error="M"/>
		</td>
		<td style="width: 30%; vertical-align: bottom; text-align: center; font-weight: bold;">
			.....................................................
		</td>
	</tr>
	<tr>
		<td style="text-align: center; font-weight: bold;">
			Mendoza, <?= (new DateTime())->format('d/m/Y'); ?>
		</td>
		<td style="text-align: center; font-weight: bold;">
			{PAGENO} de {nb}
		</td>
		<td style="text-align: center; font-weight: bold;">
			FIRMA DEL DECLARANTE
		</td>
	</tr>
</table>