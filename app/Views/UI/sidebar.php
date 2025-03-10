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

			<li class="nav-item d-none d-sm-inline-block ml-auto">
				<b>V</b><?= VERSION ?>
			</li>
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-warning elevation-4">
			<!-- Brand Logo -->
			<a href="<?= base_url() ?>" class="brand-link d-flex align-items-center">
				<?php if (session()->has('logoEmpresa') && session()->get('logoEmpresa') != '') {
					echo '<img src="' . base_url("Configuracion/Foto/" . str_replace(" ", ".", session()->get('logoEmpresa'))) . '" alt="Inventory System" class="brand-image" style="width: 35px;">';
				} else {
					echo '<img src="' . base_url("assets/img/icono-blanco.png") . '" alt="Inventory System" class="brand-image">';
				} ?>
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
									<?= substr(session()->get('nombre'), 0, 18) . '...'; ?>
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a id="miPerfil" href="#" class="nav-link">
										<i class="fa-solid fa-user nav-icon"></i>
										<p>Mi Perfil</p>
									</a>
								</li>
								<li class="nav-item">
									<a id="sincronizarPermisos" href="#" class="nav-link">
										<i class="fa-solid fa-rotate nav-icon"></i>
										<p>Sincronizar</p>
									</a>
								</li>
								<li class="nav-item">
									<a id="cerrarSesion" href="#" class="nav-link">
										<i class="fas fa-sign-out-alt nav-icon"></i>
										<p>Cerrar Sesión</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item">
              <a href="<?= base_url() ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == '' ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-house"></i>
                <p>Inicio</p>
              </a>
            </li>
						<?php if (validPermissions([6], true)) { ?> 
						<li class="nav-item <?= (current_url(true)->getSegment((1 + DOMINIO)) == 'Ventas' && (current_url(true)->getSegment((2 + DOMINIO)) == 'Crear' || current_url(true)->getSegment((2 + DOMINIO)) == 'Administrar')) ? 'menu-is-opening menu-open' : '' ?>">
              <a href="#" class="nav-link <?= current_url(true)->getSegment(1 + DOMINIO) == 'Ventas' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-store"></i>
                <p>
									Ventas
									<i class="fas fa-angle-left right"></i>
								</p>
              </a>
							<ul class="nav nav-treeview">
								<li	li class="nav-item">
									<a href="<?= base_url("Ventas/Crear") ?>" class="nav-link <?= (current_url(true)->getSegment((1 + DOMINIO)) == 'Ventas' && current_url(true)->getSegment((2 + DOMINIO)) == 'Crear') ? 'active' : '' ?>">
										<i class="far fa-circle nav-icon"></i>
										<p>Crear venta</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?= base_url("Ventas/Administrar") ?>" class="nav-link <?= (current_url(true)->getSegment((1 + DOMINIO)) == 'Ventas' && current_url(true)->getSegment((1 + DOMINIO)) == 'Administrar') ? 'active' : '' ?>">
										<i class="far fa-circle nav-icon"></i>
										<p>Administrar ventas</p>
									</a>
								</li>
							</ul>
            </li>
						<?php } ?>
						<?php if (validPermissions([10], true)) { ?> 
						<li class="nav-item <?= (current_url(true)->getSegment((1 + DOMINIO)) == 'Pedidos' && (current_url(true)->getSegment((2 + DOMINIO)) == 'Crear' || current_url(true)->getSegment((2 + DOMINIO)) == 'Administrar')) ? 'menu-is-opening menu-open' : '' ?>">
              <a href="#" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Pedidos' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-boxes-stacked"></i>
                <p>
									Pedidos
									<i class="fas fa-angle-left right"></i>
								</p>
              </a>
							<ul class="nav nav-treeview">
								<li	li class="nav-item">
									<a href="<?= base_url("Pedidos/Crear") ?>" class="nav-link <?= (current_url(true)->getSegment((1 + DOMINIO)) == 'Pedidos' && current_url(true)->getSegment((2 + DOMINIO)) == 'Crear') ? 'active' : '' ?>">
										<i class="far fa-circle nav-icon"></i>
										<p>Crear Pedido</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?= base_url("Pedidos/Administrar") ?>" class="nav-link <?= (current_url(true)->getSegment((1 + DOMINIO)) == 'Pedidos' && current_url(true)->getSegment((2 + DOMINIO)) == 'Administrar') ? 'active' : '' ?>">
										<i class="far fa-circle nav-icon"></i>
										<p>Administrar Pedidos</p>
									</a>
								</li>
							</ul>
            </li>
						<?php } ?>
						<?php if (validPermissions([40], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("Compras") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Compras' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-cart-shopping"></i>
                <p>Compras</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([100], true)) { ?>
						<li class="nav-item">
              <a href="<?= base_url("CuentasCobrar") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'CuentasCobrar' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-receipt"></i>
                <p>Cuentas por cobrar</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([80], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("IngresoMercancia") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'IngresoMercancia' ? 'active' : '' ?>">
			  					<i class="nav-icon fa-solid fa-arrow-up-right-dots"></i>
                <p>Ingreso Mercancía</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([70], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("Showroom") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Showroom' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-basket-shopping"></i>
                <p>Showroom</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([5], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("Productos") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Productos' ? 'active' : '' ?>">
								<i class="nav-icon fa-brands fa-product-hunt"></i>
                <p>Productos</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([4], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("Clientes") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Clientes' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-user-tie"></i>
                <p>Clientes</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([50], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("Proveedores") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Proveedores' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-boxes-packing"></i>
                <p>Proveedores</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([30], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("Empaque") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Empaque' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-box-open"></i>
                <p>Empaque</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([60], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("ProductosReportados") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'ProductosReportados' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-exclamation-triangle"></i>
                <p>Reporte de Empaque</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([90], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("ReporteInventario") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'ReporteInventario' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-cart-flatbed"></i>
                <p>Reporte de Inventario</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([8], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("Manifiesto") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Manifiesto' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-file"></i>
                <p>Manifiesto</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([1], true)) { ?> 
            <li class="nav-item">
              <a href="<?= base_url("Usuarios") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Usuarios' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>Usuarios</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([2], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("Perfiles") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Perfiles' ? 'active' : '' ?>">
                <i class="nav-icon fa-solid fa-address-book"></i>
                <p>Perfiles</p>
              </a>
            </li>
						<?php } ?>
						<?php if ((ENVIRONMENT !== 'production')) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("Almacen") ?>" class="nav-link <?= current_url(true)->getSegment((1 - DOMINIO)) == 'Almacen' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-swatchbook"></i>
                <p>Almacenes</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([3], true)) { ?> 
						<li class="nav-item">
              <a href="<?= base_url("Categorias") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Categorias' ? 'active' : '' ?>">
								<i class="nav-icon fa-brands fa-buffer"></i>
                <p>Categorias</p>
              </a>
            </li>
						<?php } ?>
						<?php if (validPermissions([9], true)) { ?> 
						<li class="nav-item <?= (current_url(true)->getSegment((1 + DOMINIO)) == 'Ubicacion' && (current_url(true)->getSegment((2 + DOMINIO)) == 'Paises' || current_url(true)->getSegment((2 + DOMINIO)) == 'Departamentos' || current_url(true)->getSegment((2 + DOMINIO)) == 'Ciudades')) ? 'menu-is-opening menu-open' : '' ?>">
              <a href="#" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Ubicacion' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-map"></i>
                <p>
									Ubicación
									<i class="fas fa-angle-left right"></i>
								</p>
              </a>
							<ul class="nav nav-treeview">
								<?php if (validPermissions([91], true)) { ?> 
								<li	li class="nav-item">
									<a href="<?= base_url("Ubicacion/Paises") ?>" class="nav-link <?= (current_url(true)->getSegment((1 + DOMINIO)) == 'Ubicacion' && current_url(true)->getSegment((2 + DOMINIO)) == 'Paises') ? 'active' : '' ?>">
										<i class="fa-solid fa-flag nav-icon"></i>
										<p>Paises</p>
									</a>
								</li>
								<?php } ?>
								<?php if (validPermissions([92], true)) { ?> 
								<li	li class="nav-item">
									<a href="<?= base_url("Ubicacion/Departamentos") ?>" class="nav-link <?= (current_url(true)->getSegment((1 + DOMINIO)) == 'Ubicacion' && current_url(true)->getSegment((2 + DOMINIO)) == 'Departamentos') ? 'active' : '' ?>">
									<i class="fa-solid fa-earth-africa nav-icon"></i>
										<p>Departamentos</p>
									</a>
								</li>
								<?php } ?>
								<?php if (validPermissions([93], true)) { ?> 
								<li	li class="nav-item">
									<a href="<?= base_url("Ubicacion/Ciudades") ?>" class="nav-link <?= (current_url(true)->getSegment((1 + DOMINIO)) == 'Ubicacion' && current_url(true)->getSegment((2 + DOMINIO)) == 'Ciudades') ? 'active' : '' ?>">
									<i class="fa-solid fa-city nav-icon"></i>
										<p>Ciudades</p>
									</a>
								</li>
								<?php } ?>
							</ul>
            </li>
						<?php } ?>
						<?php if (validPermissions([7], true)) { ?> 
						<li class="nav-item">
							<a href="<?= base_url("Configuracion") ?>" class="nav-link <?= current_url(true)->getSegment((1 + DOMINIO)) == 'Configuracion' ? 'active' : '' ?>">
								<i class="nav-icon fa-solid fa-gear"></i>
								<p>Configuración</p>
							</a>
						</li>
						<?php } ?>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>
