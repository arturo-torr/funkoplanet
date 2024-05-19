<!DOCTYPE html>
<html lang="es-ES">

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
    require_once "../models/Producto.php";
    require_once "../models/Categoria.php";
    $daoProductos = new DaoProductos($db);
    $daoCategorias = new DaoCategorias($db);
    $daoFotosProductos = new DaoFotosProductos($db);

    // Id de productos correspondiente
    $idProducto = "";
    if (isset($_GET['product'])) {
        $idProducto = $_GET['product'];
    }

    $prod = new Producto();
    $prod = $daoProductos->obtener($idProducto);
    $cat = new Categoria();
    $cat = $daoCategorias->obtener($prod->__get("id_categoria"));
    $daoFotosProductos->listarPorId($prod->__get("id"));

    ?>

    <section id="individual_product" data-aos='fade-up' data-aos-duration='1000'>
        <div class='container-fluid mx-auto w-75 text-center'>
            <h1 class='purple mt-2 text-center'><?php echo $prod->__get("nombre") ?></h1>
            <hr class='purple_line mb-2'>
            <div class="row align-items-center">
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 p-1 mt-2">
                    <div class="container-fluid d-flex justify-content-center text-center">
                        <div id="mainImage" class='border rounded border_purple p-1 mx-1 w-100'>
                            <?php
                            if (!empty($daoFotosProductos->fotosPro)) {
                                $conte = $daoFotosProductos->fotosPro[0]->__get("foto");
                                echo "<img src='data:image/jpg;base64,$conte' class='img-fluid w-50'>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="container mt-3">
                        <div class="d-flex">
                            <?php
                            foreach ($daoFotosProductos->fotosPro as $key => $fotoPro) {
                                $conte = $fotoPro->__get("foto");
                                echo "<div class='border rounded border_purple p-1 mx-1' onmouseover='changeMainImage(\"$conte\")'><img src='data:image/jpg;base64,$conte' class='img-fluid w-50'></div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <div class='container-fluid'>
                        <div class="row align-items-center">
                            <div class="col-12 align-items-center">
                                <?php
                                $conte = $cat->__get("foto");
                                echo "<img src='data:image/jpg;base64,$conte' alt='Imagen " . $cat->__get("nombre") . "' class='img-fluid rounded img_individualproduct my-3'>";
                                ?>
                                <p><?php echo $prod->__get("descripcion") ?></p>
                                <hr class="w-50 text-center mx-auto">
                                <?php
                                if (strtoupper($prod->__get("estado")) == "STOCK") {
                                    echo "<span class='label_individual--stock rounded'>DISPONIBLE</span>";
                                } else if (strtoupper($prod->__get("estado")) == "RESERVA") {
                                    echo "<span class='label_individual--reserva rounded'>RESERVA</span>";
                                } else if (strtoupper($prod->__get("estado")) == "AGOTADO") {
                                    echo "<span class='label_individual--agotado rounded'>AGOTADO</span>";
                                }
                                ?>
                                <span class="individual_price"><?php echo $prod->__get("precio") ?>€</span>
                            </div>
                        </div>

                        <div class="row align-items-center ">
                            <span class="my-2">Seleccione la cantidad deseada:</span>
                            <div class="col-12">
                                <button id="btn_decremento"
                                    class='btn btn_purple px-3 fw-bold text-white mt-1'>-</button>
                                <span id="cantidad" class="px-3 mt-1">1</span>
                                <button id="btn_incremento"
                                    class='btn btn_purple px-3 fw-bold text-white mt-1'>+</button>
                                <?php
                                if (strtoupper($prod->__get("estado")) == "STOCK") {
                                    echo "<button class='btn btn_purple fw-bold text-white no_decoration mx-2 mt-1' id='btn_comprar' data-product='" . $prod->__get("id") . "'>AÑADIR AL CARRITO</button>";
                                } else if (strtoupper($prod->__get("estado")) == "RESERVA") {
                                    echo "<button class='btn btn_purple fw-bold text-white no_decoration mx-2 mt-1'>RESERVAR PRODUCTO</button>";
                                } else if (strtoupper($prod->__get("estado")) == "AGOTADO") {
                                    echo "<button class='btn btn_purple fw-bold text-white no_decoration mx-2 mt-1 disabled' role='button' aria-disabled='true'>AÑADIR AL CARRITO</button>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="modal_usuario" tabindex="-1" aria-labelledby="incorrectoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg_purple text-white">
                    <h1 class="modal-title fs-5 fw-bold" id="incorrectoModalLabel">Inicio de sesión necesario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Para comprar un producto es necesario estar logueado. Por favor, inicie sesión o regístrese.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn_purple text-white fw-bold"
                        data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function changeMainImage(imageData) {
        document.getElementById('mainImage').innerHTML = "<img src='data:image/jpg;base64," + imageData +
            "' class='img-fluid w-50'>";
    }
    </script>
    <?php
    require_once "../views/scripts.php";
    ?>
</body>

</html>