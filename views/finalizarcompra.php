<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require_once "../views/head.php";
    ?>
</head>

<body>
    <?php
    require_once "../views/header.php";
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
    ?>
    <main>
        <section>
            <?php
            // Comprobar si existe un carrito en la sesión
            if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
                echo "<p class='fw-bold'>¡Ooops! Parece que todavía no tienes nada en el carrito.</p>";
                exit;
            }

            $total = 0;
            $totalCantidades = 0;
            echo "<div class='container-fluid mt-3'>";
            echo "<div class='row'>";
            echo "<div class='col-10 offset-1'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered text-center'>";
            echo "<tr class='bg_purple text-white'>";
            echo "<th>Imagen</th>";
            echo "<th class='text-center'>Cantidad</th>";
            echo "<th class='text-center'>Precio</th>";
            echo "</tr>";
            echo "<tbody>";
            foreach ($_SESSION['carrito'] as $key => $prod) {
                $producto = new Producto();
                $producto = $daoProductos->obtener($prod['idProducto']);
                $daoFotosProductos->listarPorId($prod["idProducto"]);

                $conte = $daoFotosProductos->fotosPro[0]->__get("foto");

                echo "<tr data-id='{$prod['idProducto']}'>";
                echo "<td class='text-center'><img src='data:image/jpg;base64,$conte' class='img-fluid' style='max-width: 100px;'></td>";
                $totalCantidades += $prod['cantidad'];
                echo "<td class='text-center fw-bold'>";
                echo "<button class='btn btn_decremento' data-id='{$prod['idProducto']}'>-</button>";
                echo "<span class='mx-2 cantidad'>x" . $prod['cantidad'] . "</span>";
                echo "<button class='btn btn_incremento' data-id='{$prod['idProducto']}'>+</button>";
                echo "</td>";
                $precio = $producto->__get("precio") * $prod['cantidad'];
                $total += $precio;
                echo "<td class='text-center fw-bold precio'>" . number_format($precio, 2) . "€</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";

            echo "<div class='text-end'><span class='fw-bold' id='total'>Total: " . number_format($total, 2) . "€</span></div>";
            echo "<span id='span_cantidades' style='display: none'>$totalCantidades</span>";
            echo "<hr>";
            echo "<div class='d-flex justify-content-center'>";
            echo "<button class='btn btn_purple text-white fw-bold' id='btn_finalizar'>Finalizar compra</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            ?>
        </section>
    </main>
    <?php
    require_once "../views/scripts.php";
    ?>
</body>

</html>