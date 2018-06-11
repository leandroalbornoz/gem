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
		</h1>
		<ol class="breadcrumb">
			<li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
			<li><a href="abono/abono_escuela_monto/listar/<?php echo $mes_id; ?>"> Monto Escuela</a></li>
			<li><a href="abono/abono_escuela_monto/listar_abono_alumno/<?php echo $abono_escuela_monto->id; ?>">Abono Escuela</a></li>
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
						<a class="btn btn-app btn-app-zetta" href="abono/abono_escuela_monto/listar/<?php echo $mes_id; ?>">
							<i class="fa fa-money"></i> Montos
						</a>
						<a class="btn btn-app btn-app-zetta active btn-app-zetta-active" href="abono/abono_escuela_monto/listar_abono_alumno/<?php echo $abono_escuela_monto->id ?>">
							<i class="fa fa-bus"></i> Abonos
						</a>
						<div class="info-box" style="float: right;">
							<span class="info-box-icon"><i class="fa fa-dollar"></i></span>

							<div class="info-box-content" style="margin-left: 75px;">
								<span class="info-box-text">Monto Restante</span>
								<span class="info-box-number text-success"><?php echo $monto_total_escuela; ?></span>
							</div>
						</div>
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
</div>