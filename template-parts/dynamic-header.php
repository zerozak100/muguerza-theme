<header>
    <div class="menu-principal-wrapper container">
        <nav class="navbar navbar-expand-lg">
            <div class="navbar-brand-wrapper">
                <a href="/" class="site-logo navbar-brand">
                    <img class="menu__logo" src="https://muguerza.local/wp-content/uploads/2020/04/logo-muguerza-tagline.png" alt="Christus Muguerza">
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
                                    <a class="dropdown-toggle" href="#"> Otras tiendas</a>
                                </div>
                            </div>
                            <div class="row g-0">
                                <div class="col">
                                    <span class="ubicacion-seleccionada">
                                        <?php echo mg_get_saved_location_name(); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown menu-item--especialidades-y-servicios">
                        <a class="nav-link" href="/tienda">
                            ESPECIALIDADES Y SERVICIOS
                        </a>
                    </li>
                    <li class="nav-item dropdown position-static menu-item--ubicaciones">
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
                        <!-- <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul> -->
                    </li>
                    <li class="nav-item menu-item--acerca-de">
                        <a class="nav-link" href="/">
                            ACERCA DE
                        </a>
                    </li>
                    <li class="nav-item menu-item--covid-19">
                        <a class="nav-link" href="/">
                            COVID-19
                        </a>
                    </li>
                    <li class="nav-item dropdown menu-item--buscador">
                        <span class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false"></span>
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
                        </div>
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
</header>