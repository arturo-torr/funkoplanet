<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "../views/head.php"; ?>
</head>

<body>
    <?php
    require_once "../views/header.php";
    $db = "funkoplanet";
    require_once "../dao/ProductoDAO.php";
    require_once "../dao/CategoriaDAO.php";
    require_once "../dao/FotoProDAO.php";
    $daoProductos = new DaoProductos($db);
    $daoCategorias = new DaoCategorias($db);
    $daoFotosProductos = new DaoFotosProductos($db);

    $idCategoria = "";
    if (isset($_GET['category'])) {
        $idCategoria = $_GET['category'];
    }
    $cont = 0;
    $daoProductos->listarPorCategoria($idCategoria);
    $cat = $daoCategorias->obtener($idCategoria);
    ?>

    <div class='container-fluid mx-auto w-75'>
        <h1 class='purple mt-2 text-center'><?php echo $cat->__get("nombre") ?></h1>
        <hr class='purple_line mb-2'>
        <div class="row">
            <!-- Panel de ordenación y búsqueda -->
            <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2 bg_purple text-white rounded h-25">
                <form action="" method="get" class='p-2'>
                    <legend class='fw-bold text-center'>FILTRAR PRODUCTOS</legend>
                    <div class="form-group my-2">
                        <label for="search">Buscar por nombre de producto:</label>
                        <input type="text" class="form-control" id="search" name="search">
                    </div>
                    <div class="form-group my-2">
                        <label for="filter">Filtrar por:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="stock" id="stock">
                            <label class="form-check-label" for="stock">
                                Stock
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="reserva" id="reserva">
                            <label class="form-check-label" for="reserva">
                                Reserva
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="agotado" id="agotado">
                            <label class="form-check-label" for="agotado">
                                Agotado
                            </label>
                        </div>
                    </div>
                    <div class="form-group my-2">
                        <label for="order">Ordenar por:</label>
                        <select class="form-control" id="order" name="order">
                            <!-- Aquí puedes agregar las opciones de ordenación -->
                            <option value="NEW">Más nuevo a más viejo</option>
                            <option value="OLD">Más viejo a más nuevo</option>
                            <option value="PRICE-ASC">Más barato a más caro</option>
                            <option value="PRICE-DESC">Más caro a más barato</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn_purple fw-bold text-white mt-2">Aplicar</button>
                        <button type="reset" class="btn btn-secondary fw-bold text-white mt-2">Resetear</button>
                    </div>
                </form>
            </div>
            <div class="col-sm-0 col-md-0 col-lg-1 col-xl-1"></div>
            <!-- Contenido principal -->
            <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9 text-center">
                <?php
                foreach ($daoProductos->productosJSON as $key => $prod) {
                    // Se lista por el id del producto para obtener posteriormente sus imágenes
                    $daoFotosProductos->listarPorId($prod["id"]);

                    // Abre un nuevo div de fila si es el primer producto o si ya se han mostrado 3 productos
                    if ($cont == 0 || $cont % 3 == 0) {
                        echo "<div class='row'>";
                    }

                    echo "<div class='col-sm-12 col-md-6 col-lg-6 col-xl-4 p-2 product__box'>";
                    if (strtoupper($prod["estado"]) == "STOCK") {
                        echo "<div class='label__stock rounded'>DISPONIBLE</div>";
                    } else if (strtoupper($prod["estado"]) == "RESERVA") {
                        echo "<div class='label__reserva rounded'>RESERVA</div>";
                    } else if (strtoupper($prod["estado"]) == "AGOTADO") {
                        echo "<div class='label__agotado rounded'>AGOTADO</div>";
                    }
                    echo "<a class='no_decoration' data-product='" . $prod["id"] . "' href='#'>
                            <div class='p-3 bg-white'>";

                    // Manejo de imágenes con bucle, el estilo en línea solo muestra una imagen, que es la primera que obtiene de la bbdd 
                    $imgIndex = 0;
                    foreach ($daoFotosProductos->fotosPro as $key => $fotoPro) {
                        if ($imgIndex < 2) {
                            $conte = $fotoPro->__get("foto");
                            echo "<img src='data:image/jpg;base64,$conte' class='img-fluid product-image' id='product-image-" . $prod["id"] . "-" . $imgIndex . "' style='display:" . ($imgIndex == 0 ? "block" : "none") . "'>";
                            $imgIndex++;
                        }
                    }

                    // Definición de producto
                    echo "<h3 class='product__name'>" . $prod["nombre"] . "</h3>
                        <span class='purple product__price--noeffect fw-bold'>" . $prod["precio"] . "€</span>
                        <br>";

                    if (strtoupper($prod["estado"]) == "STOCK") {
                        echo "<button class='btn btn_purple fw-bold text-white no_decoration mt-2' data-product='" . $prod["id"] . "'>COMPRAR</button>";
                    } else if (strtoupper($prod["estado"]) == "RESERVA") {
                        echo "<button class='btn btn_purple fw-bold text-white no_decoration mt-2' data-product='" . $prod["id"] . "'>RESERVAR</button>";
                    } else if (strtoupper($prod["estado"]) == "AGOTADO") {
                        echo "<button class='btn btn_purple fw-bold text-white no_decoration mt-2 disabled' role='button' aria-disabled='true' data-product='" . $prod["id"] . "'>COMPRAR</button>";
                    }
                    echo "</div>
                        </a>
                    </div>";

                    $cont++;

                    // Cierra la fila si se han mostrado 3 productos
                    if ($cont % 3 == 0) {
                        echo "</div>"; // Cierre de la fila
                    }
                }
                // Cierra la fila final si no se han mostrado 3 productos
                if ($cont % 3 != 0) {
                    echo "</div>"; // Cierre de la fila
                }

                // Cierre de contenedor principal
                echo " </div>";
                ?>
            </div>
        </div>
    </div>

    <?php
    require_once "../views/scripts.php";
    ?>
</body>

</html>