<?php session_start(); ?>
<header>
    <nav id="navbar" class="navbar navbar-expand-lg bg-light fixed-top">
        <div class="container-fluid">
            <!-- Logo a la izquierda -->
            <a class="navbar-brand" href="/funkoplanet/index.php" id="logo"><img src="/funkoplanet/assets/img/logo.png"
                    alt="Logo_FunkoPlanet" class="navbar__logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class=""><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2"
                        width="30" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 6l16 0" />
                        <path d="M4 12l16 0" />
                        <path d="M4 18l16 0" />
                    </svg></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Menús de navegación al centro -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link fw-bold" aria-current="page" href="/funkoplanet/index.php"
                            id="init">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle show fw-bold" href="#" id="navCats" role="button"
                            data-bs-toggle="dropdown" aria-expanded="true">Categorías</a>
                        <ul class="dropdown-menu" data-bs-popper="static" id="categories-list-menu">
                            <li class="hover-menu"><a data-category="" class="dropdown-item fw-bold" href="#">Todas las
                                    categorías</a>
                                <hr>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle show fw-bold" href="#" id="navEvents" role="button"
                            data-bs-toggle="dropdown" aria-expanded="true">Eventos</a>
                        <ul class="dropdown-menu" data-bs-popper="static" id="events-list-menu">
                        </ul>
                    </li>
                    <li class=" nav-item">
                        <a class="nav-link text-white fw-bold" href="#" id="navReservas" role="button">Reservas</a>
                    </li>
                </ul>
                <!-- SVGs a la derecha -->
                <div class="d-flex ms-auto">
                    <div class="nav-item dropdown">
                        <div class="nav-link" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-user-circle text-white brand" width="44" height="44"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                            </svg>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-start dropdown-menu-lg-end"
                            aria-labelledby="navbarDropdownUser">
                            <?php
                            if (!isset($_SESSION['usuario'])) {
                                echo "<li><a class='dropdown-item fw-bold' href='/funkoplanet/views/login.php'><img src='/funkoplanet/assets/img/iniciosesion.png' alt='InicioSesion-Img' class='li_img me-2'>Iniciar sesión</a></li>";
                                echo "<li><a class='dropdown-item fw-bold' href='/funkoplanet/views/registro.php'><img src='/funkoplanet/assets/img/registro.png' alt='Registro-Img' class='li_img me-2'>Registrarse</a></li>";
                            } else {
                                echo "<li><a class='dropdown-item fw-bold d-flex align-items-center' id='mis_pedidos' href='#'><img src='/funkoplanet/assets/img/pedido.png' alt='Pedido-Img' class='li_img me-2'>Mis pedidos</a></li>";
                                echo "<li'><a class='dropdown-item fw-bold d-flex align-items-center' id='mis_reservas' href='#'><img src='/funkoplanet/assets/img/reserva.png' alt='Reserva-Img' class='li_img me-2'>Mis reservas</a></li>";
                                echo "<li><a class='dropdown-item fw-bold d-flex align-items-center' href='/funkoplanet/views/logout.php' id='logout'><img src='/funkoplanet/assets/img/logout.png' alt='Logout-Img' class='li_img me-2'>Cerrar sesión</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="nav-item dropdown">
                        <a class="nav-link" href="#" id="carrito" role="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offCanvasCarrito" aria-controls="offcanvasRight">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-shopping-cart brand" width="44" height="44"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="white" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
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
</header>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offCanvasCarrito" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header bg_purple text-white fw-bold">
        <h5 class="offcanvas-title" id="offcanvasCarritoLabel">Carrito de la compra</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
    </div>
</div>

<!-- Script que llevan todas las páginas, se utiliza para ocultar en el scroll hacia abajo
el navbar y mostrarlo si se realiza scroll hacia  arriba -->
<script>
// Espera a que carge el contenido
document.addEventListener('DOMContentLoaded', function() {
    // Coge el navbar, el main de la vista donde esté y la altura del navbar
    var navbar = document.getElementById('navbar');
    var main = document.querySelector('main');
    var navbarHeight = navbar.offsetHeight;

    // Ajusta el margen superior del main para que no se solape con el navbar
    // Se ha añadido el -2 porque quedaba un ligero pixel al re scrollear hacia arriba en el carrusel
    main.style.marginTop = navbarHeight - 2 + 'px';

    var lastScrollTop = 0;
    // Captura el scroll
    window.addEventListener('scroll', function() {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop) {
            // Si el scroll es hacia abajo lo oculta
            navbar.classList.add('navbar-hidden');
        } else {
            // Si el scroll es hacia arriba lo muestra
            navbar.classList.remove('navbar-hidden');
        }
        // Así se evitan valores negativos 
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
});
</script>