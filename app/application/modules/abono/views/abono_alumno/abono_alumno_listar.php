<style>
	.datepicker,.datepicker>datepicker-days>.table-condensed{
		margin:0 auto;
	}
	.info-box-icon {
    border-top-left-radius: 5px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 5px;
    display: block;
    float: left;
    height: 55px;
    width: 70px;
    text-align: center;
    font-size: 45px;
    line-height: 52px;
		color: white;
    background: rgb(60, 141, 188);
	}
	.info-box {
    display: block;
		min-height: 0px; 
    background: #fff;
    width: 20%;
    height: 55px;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    border-radius: 6px;
    margin-bottom: 15px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Abonos <?php echo "Esc. $escuela->nombre_corto" ?> - <label class="label label-default"><?php echo $mes; ?></label>
			<a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#datepicker_modal"><i class="fa fa-refresh"></i> Cambiar</a>
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="escuela/ver/<?php echo $escuela->id; ?>"> <?php echo "Esc. $escuela->nombre_largo"; ?></a></li>
			<li><a href="alumno/listar/<?php echo $escuela->id . '/' . date('Y'); ?>">Alumnos</a></li>
			<li><a href="abono/abono_alumno/listar/<?php echo $escuela->id; ?>/<?php echo $mes_id; ?>">Abono Escuela</a></li>
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
		<?php if (!empty($warning)) : ?>
			<div class="alert alert-warning alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> Atención!</h4>
				<?php echo $warning; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-body">
						<a class="btn btn-app btn-app-zetta" href="escuela/ver/<?php echo $escuela->id; ?>">
							<i class="fa fa-search"></i> Ver escuela
						</a>
						<a class="btn btn-app btn-app-zetta" href="alumno/listar/<?php echo $escuela->id; ?>">
							<i class="fa fa-users"></i> Alumnos
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="abono/abono_alumno/listar/<?php echo $escuela->id . "/" . $mes_id; ?>">
							<i class="fa fa-bus"></i> Abonos
						</a>
						<a class="btn bg-green btn-app btn-app-zetta pull-right" data-remote="false" data-toggle="modal" data-target="#remote_modal" href="abono/abono_alumno/modal_buscar/<?php echo $escuela->id; ?>/<?php echo $mes_id; ?>/<?php echo $division_id; ?>" id="btn_agregar_a">
							<i class="fa fa-money" id="btn-agregar"></i> Agregar
						</a>
						<div class="info-box" style="float: right;">
							<span class="info-box-icon"><i class="fa fa-dollar"></i></span>

							<div class="info-box-content" style="margin-left: 75px;">
								<span class="info-box-text">Monto Restante</span>
								<span class="info-box-number text-success"><?php echo $monto_total_escuela; ?></span>
							</div>
						</div>
						<?php if ($escuela_mes) : ?>
							<a class="btn bg-blue btn-app btn-app-zetta" href="abono/abono_alumno/listar_anteriores/<?php echo $escuela->id; ?>/<?php echo $mes_id; ?>">
								<i class="fa fa-plus" id="btn-agregar"></i> Mes anterior
							</a>
						<?php endif; ?>
						<hr style="margin: 10px 0;">
						<?php echo $js_table; ?>
						<?php echo $html_table; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div style="display:none;" id="div_buscar_alumno"></div>
<div class="modal fade" id="datepicker_modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<?php echo form_open("abono/abono_alumno/cambiar_mes/$escuela->id/$mes_id/$division_id"); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Cambiar Mes</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12" style="text-align:center;">
						<div id="datepicker" data-date="<?php echo $fecha; ?>"></div>
						<input type="hidden" name="mes" id="mes" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($txt_btn) ? 'Cancelar' : 'Cerrar'; ?></button>
				<?php echo form_submit(array('class' => 'btn btn-primary pull-right', 'title' => 'Seleccionar'), 'Seleccionar'); ?>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
<?php if (isset($alumno_id)): ?>

			$('#div_buscar_alumno').append('<a id="a_buscar_alumno" href="abono/abono_alumno/modal_agregar_abono_alumno/<?php echo "$alumno_id/$escuela->id/$division_id/$mes_id"; ?>" data-remote="false" data-toggle="modal" data-target="#remote_modal"></a>');
			setTimeout(function() {
				$('#a_buscar_alumno').click();
			}, 500);
<?php endif; ?>
		$("#datepicker").datepicker({
			format: "dd/mm/yyyy",
			startView: "months",
			minViewMode: "months",
			language: 'es',
			todayHighlight: false
		});
		$("#datepicker").on("changeDate", function(event) {
			$("#mes").val($("#datepicker").datepicker('getFormattedDate'))
		});
		var monto_total_escuela = '<?php echo $monto_total_escuela; ?>';
		var cantidad_alumnos_espera = '<?php echo $cantidad_alumnos_espera; ?>';
		if (monto_total_escuela == 0 && cantidad_alumnos_espera >= 5) {
			document.getElementById("btn_agregar_a").className += " disabled";
		}
	});
</script>
