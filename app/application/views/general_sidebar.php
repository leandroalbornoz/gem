<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img src="img/generales/logo-dge-sm.png" class="img-circle" alt="Usuario" />
			</div>
			<div class="pull-left info">
				<p><?php echo "$apellido, $nombre"; ?></p>
				<a href="#"><i class="fa fa-circle text-success"></i> En línea</a>
			</div>
		</div>
		<ul class="sidebar-menu">
			<li class="header">MENU PRINCIPAL</li>
				<?php if (!empty($permisos)) echo $permisos; ?>
		</ul>
	</section>
</aside>