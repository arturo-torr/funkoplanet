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
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <div class="container-fluid d-flex justify-content-center text-center">
                        <div id="mainImage" class='border rounded border_purple p-1 mx-1'>
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
                        <h5><?php echo $prod->__get("descripcion") ?></h5>
                        <?php
                        $conte = $cat->__get("foto");
                        echo "<img src='data:image/jpg;base64,$conte' class='img-fluid w-50'>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    require_once "../views/scripts.php";
    ?>

    <script>
        function changeMainImage(imageData) {
            document.getElementById('mainImage').innerHTML = "<img src='data:image/jpg;base64," + imageData +
                "' class='img-fluid w-50'>";
        }
    </script>
</body>

</html>