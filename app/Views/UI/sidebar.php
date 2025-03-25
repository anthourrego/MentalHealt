<div class="wrapper">
		<!-- navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="padding: .5rem .3rem">
			<li class="nav-item no-blocked-menu" style="list-style-type: none;">
				<a class="nav-link text-dark" data-widget="pushmenu" href="#" role="button">
					<i class="fas fa-bars"></i>
				</a>
			</li>
			<li class="nav-item d-none border-left <?= (isset($title) ? 'd-sm-inline-block' : '') ?>">
				<h4 class="nav-link mb-0"><?= (isset($title) ? $title : '') ?></h4>
			</li>
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-<?= $theme->sidebar ?? "primary" ?> elevation-4">
			<!-- Brand Logo -->
			<a href="<?= base_url() ?>" class="brand-link d-flex align-items-center <?= $theme->bg_logo ?? "" ?>">
				<img src="<?=base_url("assets/img/icono-blanco.png") ?>" alt="<?=$Project_Name?>" class="brand-image">
				<span class="brand-text font-weight-light"><?=$Project_Name?></span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul id="opciones" class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
					<!-- Add icons to the links using the .nav-icon class
					with font-awesome or any other icon font library -->
						<li class="nav-item has-treeview user-panel mt-2 pb-2 mb-2">
							<a href="#" class="nav-link">
								<i class="nav-icon far fa-user-circle"></i>
								<p>
									<?= substr(session()->get('full_name'), 0, 18) . '...'; ?>
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<!-- <li class="nav-item">
									<a id="miPerfil" href="#" class="nav-link">
										<i class="fa-solid fa-user nav-icon"></i>
										<p>Mi Perfil</p>
									</a>
								</li> -->
								<li class="nav-item">
									<a id="btnLogout" href="#" class="nav-link">
										<i class="fas fa-sign-out-alt nav-icon"></i>
										<p>Cerrar Sesión</p>
									</a>
								</li>
							</ul>
						</li>
						<?php if ($Profile == 1) { //Administrador ?>
						<li class="nav-item">
              <a href="<?= base_url("admin") ?>" class="nav-link <?= (current_url(true)->getSegment(1) == 'admin' && current_url(true)->getSegment(2) == '') ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-house"></i>
                <p>Inicio</p>
              </a>
            </li>
						<li class="nav-item">
              <a href="<?= base_url("admin/Users") ?>" class="nav-link <?= (current_url(true)->getSegment(1) == 'admin' && current_url(true)->getSegment(2) == 'Users') ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-users"></i>
                <p>Usuarios</p>
              </a>
            </li>

						
						<?php } else if ($Profile == 2) { //Terapista ?>
						<li class="nav-item">
              <a href="<?= base_url("therapist") ?>" class="nav-link <?= (current_url(true)->getSegment(1) == 'therapist' && current_url(true)->getSegment(2) == '') ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-house"></i>
                <p>Inicio</p>
              </a>
            </li>

						<?php } else { //Paciente ?>
							<li class="nav-item">
              <a href="<?= base_url("patient") ?>" class="nav-link <?= (current_url(true)->getSegment(1) == 'patient' && current_url(true)->getSegment(2) == '') ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-house"></i>
                <p>Inicio</p>
              </a>
							<a href="<?= base_url("patient/diary") ?>" class="nav-link <?= (current_url(true)->getSegment(1) == 'patient' && current_url(true)->getSegment(2) == 'diary') ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-book"></i>
                <p>Mi diario</p>
              </a>
            </li>
						<?php } ?>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>
