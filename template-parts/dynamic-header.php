<?php

$user = MG_User::current();

$unidad = $user->get_unidad();

?>

<header class="main-header">
    <div class="header-band">
        <span class="header-band__text">RENUEVA O ADQUIERE TU MEMBRESÍA, OBTÉN BENEFICIOS PARA TU SALUD, ¡ES GRATIS! QUIERO SER SOCIO CHRISTUS</span>
    </div>
    <div class="menu-terciario-wrapper container">
        <nav class="navbar navbar-expand-lg">
            <div class="menu-terciario navbar-collapse collapse">
                <ul class="navbar-nav">
                    <li class="nav-item menu-item--casa-cuna-conchita">
                        <a class="nav-link" href="https://www.casacunaconchita.com/">
                            Casa Cuna Conchita
                        </a>
                    </li>
                    <li class="nav-item menu-item--serviciones-en-linea">
                        <a class="nav-link" href="#">
                            Servicios en Línea
                        </a>
						<ul class="submenu">
							<li class="nav-item">
								<a href="/resultados-de-laboratorio/">Resultados de laboratorio</a>
							</li>
							<li class="nav-item">
								<a href="/resultados-de-imagenologia/">Resultados de Imagenología</a>
							</li>
							<li class="nav-item">
								<a href="#">Directorio medico</a>
							</li>
							<li class="nav-item">
								<a href="https://facturacion.christusmuguerza.com.mx:8443/ords/finanzas/r/facturacion/15">Facturación electrónica</a>
							</li>
							<li class="nav-item">
								<a href="/directorio-telefonico/">Directorio telefónico</a>
							</li>
							<li class="nav-item">
								<a href="#">Espacio saludable</a>
							</li>
							<li class="nav-item">
								<a href="https://www.office.com/">Portal de colaboradores</a>
							</li>
						</ul>
                    </li>
                    <li class="nav-item menu-item--membresias">
                        <a class="nav-link" href="#">
                            Membresías
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="menu-principal-wrapper container">
        <nav class="navbar navbar-expand-lg">
            <div class="navbar-brand-wrapper">
                <a href="/" class="site-logo navbar-brand">
                    <img class="menu__logo" src="/wp-content/uploads/2020/04/logo-muguerza-tagline.png" alt="Christus Muguerza">
                </a>
            </div>
            <div class="menu-principal navbar-collapse collapse">
                <ul class="navbar-nav">
                    <li class="nav-item menu-item--ubicacion">
                        <div class="container-fluid g-0">
                            <div class="row g-0">
                                <div class="col">
                                    <span class="icono-ubicacion-morado"></span>
                                    <span>Tu tienda</span>&nbsp
                                    <a class="dropdown-toggle otras-tiendas" href="#"> Otras tiendas</a>
                                </div>
                            </div>
                            <div class="row g-0">
                                <div class="col">
                                    <span class="ubicacion-seleccionada">
                                        <?php echo $unidad->get_name(); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- <li class="nav-item dropdown position-static menu-item--ubicaciones">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            UBICACIONES
                        </a>
                        <div class="dropdown-menu w-100 mt-0" aria-labelledby="navbarDropdown" style="
                          border-top-left-radius: 0;
                          border-top-right-radius: 0;
                        ">
                            <div class="container">
                                <div class="row">
                                    <div class="col g-0">
                                        <div class="list-group list-group-flush">
                                            <span class="list-group-item dropdown-menu__col-encabezado"><b>NUEVO LEÓN</b></span>
                                            <a href="" class="list-group-item list-group-item-action">Centro</a>
                                            <a href="" class="list-group-item list-group-item-action">Obispado</a>
                                            <a href="" class="list-group-item list-group-item-action">Sur</a>
                                            <a href="" class="list-group-item list-group-item-action">Cumbres</a>
                                            <a href="" class="list-group-item list-group-item-action">San Pedro</a>
                                            <a href="" class="list-group-item list-group-item-action">Escobedo</a>
                                            <a href="" class="list-group-item list-group-item-action">San Nicolás</a>
                                        </div>
                                    </div>
                                    <div class="col g-0">
                                        <div class="list-group list-group-flush">
                                            <span class="list-group-item dropdown-menu__col-encabezado"><b>COAHUILA</b></span>
                                            <a href="" class="list-group-item list-group-item-action">Hospital Saltillo</a>
                                            <a href="" class="list-group-item list-group-item-action">CAM Nogalera</a>
                                            <a href="" class="list-group-item list-group-item-action">CAM Plaza Cristal</a>
                                        </div>
                                    </div>
                                    <div class="col g-0">
                                        <div class="list-group list-group-flush">
                                            <span class="list-group-item dropdown-menu__col-encabezado"><b>CHIHUAHUA</b></span>
                                            <a href="" class="list-group-item list-group-item-action">Hospital Del Parque</a>
                                            <a href="" class="list-group-item list-group-item-action">Clínica Juventud</a>
                                        </div>
                                    </div>
                                    <div class="col g-0">
                                        <div class="list-group list-group-flush">
                                            <span class="list-group-item dropdown-menu__col-encabezado"><b>PUEBLA</b></span>
                                            <a href="" class="list-group-item list-group-item-action">Hospital Betania</a>
                                            <a href="" class="list-group-item list-group-item-action">Hospital UPAEP</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col g-0">
                                        <div class="list-group list-group-flush">
                                            <span class="list-group-item dropdown-menu__col-encabezado"><b>TAMAULIPAS</b></span>
                                            <a href="" class="list-group-item list-group-item-action">Hospital Reynosa</a>
                                            <a href="" class="list-group-item list-group-item-action">CAM Periférico</a>
                                        </div>
                                    </div>
                                    <div class="col g-0">
                                        <div class="list-group list-group-flush">
                                            <span class="list-group-item dropdown-menu__col-encabezado"><b>GUANAJUATO</b></span>
                                            <a href="" class="list-group-item list-group-item-action">Hospital Altagracia</a>
                                            <a href="" class="list-group-item list-group-item-action">Clínica Irapuato</a>
                                        </div>
                                    </div>
                                    <div class="col g-0">
                                        <div class="list-group list-group-flush">
                                            <span class="list-group-item dropdown-menu__col-encabezado"><b>YUCATÁN</b></span>
                                            <a href="" class="list-group-item list-group-item-action">Hospital Faro del Mayab</a>
                                        </div>
                                    </div>
                                    <div class="col g-0">
                                        <div class="list-group list-group-flush">
                                            <span class="list-group-item dropdown-menu__col-encabezado"><b>7-24</b></span>
                                            <a href="" class="list-group-item list-group-item-action">Asistencia Médica</a>
                                            <a href="" class="list-group-item list-group-item-action">Inmediata</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </li> -->

                    <li class="nav-item dropdown menu-item--buscador">
                        <form class="search-form" action="/tienda">
                            <div class="search-box">
                                <span class="search-input-icon"></span>
                                <input name="s" class="search-input" type="text">
                            </div>
                            <!-- <button type="submit" class="search-button">Enviar</button> -->
                        </form>
                        <!-- <span class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false"></span>
                        <div class="dropdown-menu">
                            <div class="container">
                                <form class="search-form" action="/tienda">
                                    <div class="search-box">
                                        <span class="search-input-icon"></span>
                                        <input name="s" class="search-input" type="text">
                                    </div>
                                    <button type="submit" class="search-button">Enviar</button>
                                </form>
                            </div>
                        </div> -->
                    </li>
                    <li class="nav-item menu-item--mi-cuenta">
                        <span class="nav-link">
                            <span>
                                <a href="#">Iniciar sesión</a> o <a href="#">regístrarse</a>
                            </span>
                        </span>
                    </li>
                    <li class="nav-item menu-item--carrito">
                        <ul id="site-header-cart" class="navbar-nav site-header-cart menu">
                            <li class="nav-item">
                                <a class="cart-contents" href="/cart/" title="Ver tu carrito de compra">
                                    <span class="count">0</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="menu-secundario-wrapper container">
        <nav class="navbar navbar-expand-lg">
            <div class="menu-secundario navbar-collapse collapse">
                <ul class="navbar-nav">
                    <li class="nav-item menu-item--servicios">
                        <a class="nav-link" href="<?php echo esc_url( get_permalink( get_page_by_path( 'servicios' ) ) ); ?>">
                            SERVICIOS
                        </a>
                    </li>
                    <li class="nav-item menu-item--especialidades">
                        <a class="nav-link" href="<?php echo esc_url( get_permalink( get_page_by_path( 'especialidades' ) ) ); ?>">
                            ESPECIALIDADES
                        </a>
                    </li>
                    <li class="nav-item menu-item--promociones">
                        <a class="nav-link" href="/">
                            PROMOCIONES
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="/hospitales">
                            UBICACIONES
                        </a>
                    </li>
					<li class="nav-item menu-item--proposito-social">
					    <a class="nav-link" href="#">
                            PROPOSITO SOCIAL
                        </a>
						<ul class="submenu">
							<li class="nav-item">
								<a href="">Responsabilidad social</a>
							</li>
							<li class="nav-item">
								<a href="https://www.fundacionlafon.org.mx/">Funcación Adelaida Lafón</a>
							</li>
							<li class="nav-item">
								<a href="https://www.casacunaconchita.com/">Casa Cuna Conchita</a>
							</li>
							<li class="nav-item">
								<a href="/escuela-de-enfermeria/">Escuela de enfermeria</a>
							</li>
							<li class="nav-item">
								<a href="/educacion-e-investigacion/">Educacion e Investigacion en Salud</a>
							</li>
                            <li class="nav-item">
                                <a href="/7-24-asistencia-medica/">Asistencia Médica Inmediata 7/24</a>
                            </li>
						</ul>
                    </li>
                    <li class="nav-item menu-item--covid-19">
                        <a class="nav-link" href="/">
                            COVID-19
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
