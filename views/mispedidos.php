<!DOCTYPE html>
<html lang="es-ES">

<head>
    <?php
    require_once "../views/head.php";
    ?>
</head>

<body>
    <?php
    require_once "../views/header.php";
    $db = "funkoplanet";
    require_once "../dao/PedidoDAO.php";
    require_once "../dao/DetPedidoDAO.php";
    require_once "../dao/UsuarioDAO.php";
    require_once "../dao/ProductoDAO.php";
    require_once "../dao/FotoProDAO.php";

    $daoPedidos = new DaoPedidos($db);
    $daoDetPedidos = new DaoDetPedidos($db);
    $daoUsuarios = new DaoUsuarios($db);
    $daoProductos = new DaoProductos($db);
    $daoFotosProductos = new DaoFotosProductos($db);

    // Obtiene el usuario que está en la sesión y posteriormente los pedidos que ha realizado
    $usuario = $daoUsuarios->obtenerPorUsername($_SESSION['usuario']['username']);
    $daoPedidos->listar($usuario->__get("id"));
    ?>
    <main class="py-2 my-5">
        <section class="py-5 my-2">
            <div class="container">
                <?php

                if (count($daoPedidos->pedidos) > 0) {
                    foreach ($daoPedidos->pedidos as $pedido) {
                        echo "<div class='card mb-4'>";
                        echo "<div class='card-header bg_purple'>";
                        echo "<h3 class='text-white'>Pedido número <strong>" . $pedido->__get("id_pedido") . "</strong></h3>";
                        echo "</div>";
                        echo "<div class='card-body'>";
                        echo "<div class='table-responsive'>";
                        echo "<table class='table text-center align-middle'>";
                        echo "<tr>";
                        echo "<th>Imagen</th>";
                        echo "<th>Producto</th>";
                        echo "<th>Cantidad</th>";
                        echo "<th>Precio Unitario</th>";
                        echo "</tr>";

                        $daoDetPedidos->listar($pedido->__get("id_pedido"));
                        foreach ($daoDetPedidos->detpedidos as $detpedido) {
                            $producto = $daoProductos->obtener($detpedido->__get("id_producto"));
                            $daoFotosProductos->listarPorId($producto->__get("id"));
                            $conte = $daoFotosProductos->fotosPro[0]->__get("foto");
                            echo "<tr>";
                            echo "<td><img src='data:image/jpg;base64,$conte' class='img-fluid' style='max-width: 100px;' alt='Producto'></td>";
                            echo "<td>" . $producto->__get("nombre") . "</td>";
                            echo "<td>" . $detpedido->__get("cantidad") . "</td>";
                            echo "<td>" . $detpedido->__get("precio_unitario") . "€</td>";
                            echo "</tr>";
                        }

                        echo "</table>";
                        echo "</div>";
                        echo "<div class='row'>";
                        echo "<h4 class='text-center'>Total del pedido: " . $pedido->__get("total") . "€</h4>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='text-center'>";
                    echo "<p class='fw-bold'>¡Ooops! Todavía no has hecho ningún pedido. ¿Por qué no te animas?</p>";
                    echo "<img src='/funkoplanet/assets/img/funkocarrito.png' class='img-fluid w-25' alt='Carrito-Funko'>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>
    </main>
    <?php
    require_once "../views/footer.php";
    require_once "../views/scripts.php";
    ?>
</body>

</html>