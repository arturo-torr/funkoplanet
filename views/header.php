<nav class="navbar navbar-expand-lg bg-light sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/funkoplanet/index.php" id="logo"><img src="/funkoplanet/assets/img/logo.png" alt="Logo_FunkoPlanet" class="navbar__logo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active text-white fw-bold" aria-current="page" href="/funkoplanet/index.php" id="init">Inicio</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle show text-white fw-bold" href="#" id="navCats" role="button" data-bs-toggle="dropdown" aria-expanded="true">Categorías</a>
                    <ul class="dropdown-menu" data-bs-popper="static" id="categories-list-menu">
                        <li class="hover-menu"><a data-category="" class="dropdown-item fw-bold" href="#">Todas las
                                categorías</a>
                            <hr>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle show text-white fw-bold" href="#" id="navEvents" role="button" data-bs-toggle="dropdown" aria-expanded="true">Eventos</a>
                    <ul class="dropdown-menu" data-bs-popper="static">
                    </ul>
                </li>
            </ul>
            <div class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle text-white" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                    </svg>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <?php
                    session_start();
                    if (!isset($_SESSION['usuario'])) {
                        echo "<li><a class='dropdown-item fw-bold' href='/funkoplanet/views/login.php'>Iniciar sesión</a>
                        </li>";
                    } else {
                        echo "<li><a class='dropdown-item fw-bold' href='/funkoplanet/views/login.php'>Mi cuenta - " . $_SESSION['usuario'] . "</a>
                        </li>";
                        echo "<li><a class='dropdown-item fw-bold' href='/funkoplanet/views/logout.php' id='logout'>Cerrar sesión</a>
                        </li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</nav>