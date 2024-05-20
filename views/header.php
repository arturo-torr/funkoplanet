<nav class="navbar navbar-expand-lg bg-light sticky-top">
    <div class="container-fluid">
        <!-- Logo a la izquierda -->
        <a class="navbar-brand" href="/funkoplanet/index.php" id="logo"><img src="/funkoplanet/assets/img/logo.png"
                alt="Logo_FunkoPlanet" class="navbar__logo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Menús de navegación al centro -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 col-xl-5 col-lg-7">
                <li class="nav-item">
                    <a class="nav-link active text-white fw-bold" aria-current="page" href="/funkoplanet/index.php"
                        id="init">Inicio</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle show text-white fw-bold" href="#" id="navCats" role="button"
                        data-bs-toggle="dropdown" aria-expanded="true">Categorías</a>
                    <ul class="dropdown-menu" data-bs-popper="static" id="categories-list-menu">
                        <li class="hover-menu"><a data-category="" class="dropdown-item fw-bold" href="#">Todas las
                                categorías</a>
                            <hr>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle show text-white fw-bold" href="#" id="navEvents" role="button"
                        data-bs-toggle="dropdown" aria-expanded="true">Eventos</a>
                    <ul class="dropdown-menu" data-bs-popper="static">
                    </ul>
                </li>
            </ul>
            <!-- SVGs a la derecha -->
            <div class="d-flex">
                <div class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="icon icon-tabler icon-tabler-user-circle text-white" width="44" height="44"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                        </svg>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start dropdown-menu-lg-end"
                        aria-labelledby="navbarDropdownUser">
                        <?php
                        session_start();
                        if (!isset($_SESSION['usuario'])) {
                            echo "<li><a class='dropdown-item fw-bold' href='/funkoplanet/views/login.php'>Iniciar sesión</a></li>";
                            echo "<li><a class='dropdown-item fw-bold' href='/funkoplanet/views/registro.php'>Registrarse</a></li>";
                        } else {
                            echo "<li><a class='dropdown-item fw-bold' href='#'>Mi cuenta - " . $_SESSION['usuario']['username'] . "</a></li>";
                            echo "<li><a class='dropdown-item fw-bold' href='/funkoplanet/views/logout.php' id='logout'>Cerrar sesión</a></li>";
                        }
                        ?>
                    </ul>
                </div>
                <div class="nav-item dropdown">
                    <a class="nav-link" href="#" id="carrito" role="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offCanvasCarrito" aria-controls="offcanvasRight">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart"
                            width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M17 17h-11v-14h-2" />
                            <path d="M6 5l14 1l-1 7h-13" />
                            <circle cx="16" cy="16.5" r="6" fill="#4ae600" stroke="#4ae600" />
                            <text id='numero_items_carrito' x="16" y="17" font-size="8.5" stroke="black"
                                font-family="Arial" dominant-baseline="middle" text-anchor="middle">
                                <?php if (!isset($_SESSION['usuario'])) {
                                    echo "0";
                                    echo "<script>localStorage.removeItem('cantidades');</script>";
                                } ?></text>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offCanvasCarrito" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header bg_purple text-white fw-bold">
        <h5 class="offcanvas-title" id="offcanvasCarritoLabel">Carrito de la compra</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
    </div>
</div>