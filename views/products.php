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
    require_once "../models/Categoria.php";
    $daoProductos = new DaoProductos($db);
    $daoCategorias = new DaoCategorias($db);
    $daoFotosProductos = new DaoFotosProductos($db);

    // Id de categoría correspondiente
    // Se realizan las comprobaciones pertinentes por si se accede directamente a products.php
    $idCategoria = "";
    if (isset($_GET['category'])) {
        $idCategoria = $_GET['category'];
    }

    // Variables de filtrado
    $busqueda = "";
    if (isset($_GET['busqueda'])) {
        $busqueda = $_GET['busqueda'];
    }
    $disponibilidad = "Todos los productos";
    if (isset($_GET['disponibilidad'])) {
        $disponibilidad = $_GET['disponibilidad'];
    }
    $orden = "";
    if (isset($_GET['orden'])) {
        $orden = $_GET['orden'];
    }

    // Contador para rows
    $cont = 0;
    $cat = "";

    // Si se ha clickeado en filtrar
    if (isset($_GET['Filtrar'])) {
        if ($idCategoria) {
            $cat = $daoCategorias->obtener($idCategoria);
        }
        $daoProductos->listarConFiltro($idCategoria, $busqueda, $disponibilidad, $orden);
    } else {
        // Si no, comprueba que la categoría no sea vacía 
        if ($idCategoria == "") {
            // Si es vacía, lista todos los productos
            $daoProductos->listar();
        } else {
            // Si no está vacía, obtiene la categoría y lista los productos perttenecientes a ella
            $cat = $daoCategorias->obtener($idCategoria);
            $daoProductos->listarPorCategoria($idCategoria);
        }
    }
    ?>

    <div class="d-flex justify-content-center mt-2 mx-5">
        <form name='f1' class='row bg_purple p-3 rounded' method='get' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
            <!-- Campo oculto para poder recuperar la categoría que tenemos -->
            <input type="hidden" name='category' value='<?php echo $idCategoria ?>'>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                <label for="busqueda" class="form-label text-white">Búsqueda por texto:</label>
                <input type="text" class="form-control" name="busqueda" id="busqueda" value='<?php echo $busqueda ?>'>
            </div>
            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                <label for="disponibilidad" class="form-label text-white">Disponibilidad:</label>
                <select class="form-select" id="disponibilidad" name="disponibilidad">
                    <?php
                    $arrDisponibilidad = array('Todos los productos', 'Stock', 'Reserva', 'Agotado');
                    foreach ($arrDisponibilidad as $dis) {
                        echo "<option value='$dis' ";

                        if ($dis == $disponibilidad) {
                            echo " selected";
                        }

                        echo ">$dis</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                <label for="orden" class="form-label text-white">Orden:</label>
                <select class="form-select" id="orden" name="orden">
                    <?php
                    $arrOrden = array(
                        'nuevos' => 'Más nuevo a más viejo',
                        'viejos' => 'Más viejo a más nuevo',
                        'baratos' => 'Más barato a más caro',
                        'caros' => 'Más caro a más barato'
                    );

                    foreach ($arrOrden as $key => $value) {
                        echo "<option value='$key'";
                        if ($key == $orden) {
                            echo " selected";
                        }
                        echo ">$value</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mx-auto mt-2 text-center">
                <br>
                <input type="submit" name='Filtrar' value='Filtrar'
                    class="btn btn_purple--dark text-white px-5 fw-bold">
            </div>
        </form>
    </div>

    <div class='container-fluid mx-auto w-75'>
        <h1 class='purple mt-2 text-center'><?php echo ($cat ? $cat->__get("nombre") : "Todas las categorías"); ?></h1>
        <hr class='purple_line mb-2'>
        <div class="row">

            <div class="col-sm-0 col-md-0 col-lg-1 col-xl-1"></div>
            <!-- Contenido principal -->
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                <?php
                if (count($daoProductos->productosJSON) == 0) {
                    echo "<p class='fw-bold'>No se han encontrado productos. ¿Por qué no pruebas con otra categoría o filtro?</p>";
                }
                foreach ($daoProductos->productosJSON as $key => $prod) {
                    // Se lista por el id del producto para obtener posteriormente sus imágenes
                    $daoFotosProductos->listarPorId($prod["id"]);
                    // Abre un nuevo div de fila si es el primer producto o si ya se han mostrado 3 productos
                    if ($cont == 0 || $cont % 4 == 0) {
                        echo "<div class='row'>";
                    }

                    echo "<div class='col-sm-12 col-md-6 col-lg-3 col-xl-3 p-2 product__box' data-aos='fade-up'
                    data-aos-duration='1000'>";
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
                    if ($cont % 4 == 0) {
                        echo "</div>"; // Cierre de la fila
                    }
                }
                // Cierra la fila final si no se han mostrado 3 productos
                if ($cont % 4 != 0) {
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