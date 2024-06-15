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

    if (!$idProducto) {
        header('Location: index.php');
    }

    $prod = new Producto();
    $prod = $daoProductos->obtener($idProducto);
    $cat = new Categoria();
    $cat = $daoCategorias->obtener($prod->__get("id_categoria"));
    $daoFotosProductos->listarPorId($prod->__get("id"));

    ?>
    <main>
        <section id="individual_product" data-aos='fade-up' data-aos-duration='1000' class="my-4 py-2">
            <div class='container-fluid mx-auto w-75 text-center'>
                <div class="row align-items-stretch">
                    <div
                        class="col-sm-12 col-md-12 col-lg-12 col-xl-6  mt-2 order-2 order-sm-2 order-md-2 order-lg-2 order-xl-1 d-flex flex-column">
                        <div
                            class="container-fluid d-flex justify-content-center text-center flex-grow-1 border rounded border_purple">
                            <div id="mainImage" class=' w-100 mx-1 d-flex align-items-center justify-content-center'>
                                <?php
                                if (!empty($daoFotosProductos->fotosPro)) {
                                    $conte = $daoFotosProductos->fotosPro[0]->__get("foto");
                                    echo "<img src='data:image/jpg;base64,$conte' class='img-fluid w-75'>";
                                }
                                ?>
                            </div>
                        </div>
                        <div class="container mt-2 border rounded border_purple">
                            <div class="d-flex justify-content-around">
                                <?php
                                foreach ($daoFotosProductos->fotosPro as $key => $fotoPro) {
                                    $conte = $fotoPro->__get("foto");
                                    echo "<div onmouseover='changeMainImage(\"$conte\")'><img src='data:image/jpg;base64,$conte' class='w-75'></div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div
                        class="col-sm-12 col-md-12 col-lg-12 col-xl-6 order-1 order-sm-1 order-md-1 order-lg-1 order-xl-2 mt-2">
                        <div class='card d-flex border_purple'>
                            <div class=" card-header bg_purple text-white fw-bold">
                                <h1 class="m-0 p-0"><?php echo $prod->__get("nombre") ?></h1>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <?php
                                $conte = $cat->__get("foto");
                                echo "<img src='data:image/jpg;base64,$conte' alt='Imagen " . $cat->__get("nombre") . "' class='img-fluid rounded w-100 img_individualproduct my-3'>";
                                ?>
                                <div class="d-flex align-items-center justify-content-center">
                                    <?php
                                    if (strtoupper($prod->__get("estado")) == "STOCK") {
                                        echo "<span class='label_individual--stock rounded my-2 mx-2'>DISPONIBLE</span>";
                                    } else if (strtoupper($prod->__get("estado")) == "RESERVA") {
                                        echo "<span class='label_individual--reserva rounded my-2 mx-2'>RESERVA</span>";
                                    } else if (strtoupper($prod->__get("estado")) == "AGOTADO") {
                                        echo "<span class='label_individual--agotado rounded my-2 mx-2'>AGOTADO</span>";
                                    }
                                    ?>
                                    <span class="individual_price"><?php echo $prod->__get("precio") ?>€</span>
                                </div>
                                <p><?php echo $prod->__get("descripcion") ?></p>
                                <hr class="w-75 text-center mx-auto">

                                <div class="my-3">
                                    <?php
                                    if ($prod->__get("uds_disponibles") > 0) {
                                        echo "<span class='my-2'>Actualmente quedan <strong>" . $prod->__get("uds_disponibles") . "</strong> unidades en stock.</span>";
                                    } else {
                                        echo "<span class='my-2'>No existen unidades disponibles para este artículo actualmente.</span>";
                                    }
                                    ?>
                                </div>

                                <span class="my-2 text-center d-block">Seleccione la cantidad deseada:</span>
                                <div class="d-flex justify-content-center mt-auto">
                                    <div class="d-flex align-items-center">
                                        <button id="btn_decremento"
                                            class='btn btn_purple text-white mx-1 py-2'>-</button>
                                        <span id="cantidad" class="btn mx-1">1</span>
                                        <button id="btn_incremento" class='btn btn_purple text-white mx-1 py-2'
                                            data-disponibles='<?php echo $prod->__get("uds_disponibles") ?>'>+</button>

                                        <?php
                                        if (strtoupper($prod->__get("estado")) == "STOCK") {
                                            echo "<button class='btn btn_purple fw-bold text-white no_decoration py-2' id='btn_comprar' data-product='" . $prod->__get("id") . "'>AÑADIR AL CARRITO</button>";
                                        } else if (strtoupper($prod->__get("estado")) == "RESERVA") {
                                            echo "<button class='btn btn_purple fw-bold text-white no_decoration py-2' id='btn_reservar' data-product='" . $prod->__get("id") . "'>RESERVAR</button>";
                                        } else if (strtoupper($prod->__get("estado")) == "AGOTADO") {
                                            echo "<button class='btn btn_purple fw-bold text-white no_decoration py-2 disabled' role='button' aria-disabled='true'>AÑADIR AL CARRITO</button>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal -->
        <div class="modal fade" id="modal_usuario" tabindex="-1" aria-labelledby="incorrectoModalLabel"
            aria-hidden="true">
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
    </main>
    <script>
    function changeMainImage(imageData) {
        document.getElementById('mainImage').innerHTML = "<img src='data:image/jpg;base64," + imageData +
            "' class='img-fluid w-75'>";
    }
    </script>
    <?php
    require_once "../views/footer.php";
    require_once "../views/scripts.php";
    ?>
</body>

</html>