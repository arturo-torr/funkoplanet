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
        <section class="my-5 p-3" id="carrito_resumen">
            <?php
            // Comprueba si no existe un carrito o el carrito está vacío
            if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
                echo "<div class='text-center'>";
                echo "<p class='fw-bold'>¡Ooops! Parece que todavía no tienes nada en el carrito.</p>";
                echo "<img src='/funkoplanet/assets/img/funkocarrito.png' class='img-fluid w-25' alt='Carrito-Funko'>";
                echo "</div>";
            } else {
                $total = 0;
                $totalCantidades = 0;
            ?>
            <div class="accordion col-10 offset-1 rounded" id="acordeon">
                <div class="accordion-item rounded">
                    <h2 class="accordion-header" id="header_resumen">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Resumen del Carrito
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="header_resumen"
                        data-bs-parent="#acordeon">
                        <div class="accordion-body">
                            <div class="container-fluid my-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered border_purple text-center align-middle">
                                                <tr class="bg_purple text-white">
                                                    <th>Producto</th>
                                                    <th class="text-center">Cantidad</th>
                                                    <th class="text-center">Precio</th>
                                                </tr>
                                                <tbody>
                                                    <?php
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
                                                        ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-end"><span class="fw-bold" id="total">Total:
                                                <?php echo number_format($total, 2); ?>€</span></div>
                                        <span id="span_cantidades"
                                            style="display: none"><?php echo $totalCantidades; ?></span>
                                        <hr>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn_purple text-white fw-bold" id="btn_pago">Pasar a
                                                pago</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="header_pago">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"
                            id="header_pago_button" disabled>
                            Pago con Tarjeta
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="header_pago"
                        data-bs-parent="#acordeon">
                        <div class="accordion-body">
                            <form action="" method="POST" id="fPago" class="my-3 border_purple rounded p-3" novalidate>
                                <div class="row m-1 py-2">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-1">
                                        <label for="titular" class="form-label">Titular de la tarjeta: </label>
                                        <input type="text" placeholder="Nombre del titular" id="titular"
                                            class="form-control" name="titular"
                                            pattern="^([A-Za-zÑñÁáÉéÍíÓóÚú]+['\-]{0,1}[A-Za-zÑñÁáÉéÍíÓóÚú]+)(\s+([A-Za-zÑñÁáÉéÍíÓóÚú]+['\-]{0,1}[A-Za-zÑñÁáÉéÍíÓóÚú]+))*$"
                                            required>
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback">
                                            El nombre del titular no es válido.
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-1">
                                        <label for="numTarjeta" class="form-label">Número de la tarjeta:</label>
                                        <input type="text" id="creditCard" class="form-control" name="creditCard"
                                            placeholder="XXXX XXXX XXXX XXXX" maxlength="16"
                                            pattern="^(5[1-5][0-9]{14}|4[0-9]{12}(?:[0-9]{3})?)$" required>
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback">
                                            La tarjeta no puede ser aceptada si no es Visa o Mastercard.<br>Ponga los
                                            números juntos y asegúrese de que empieza por 5.
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-1">
                                        <label for="cardDate" class="form-label">Fecha de caducidad:</label>
                                        <input type="text" id="cardDate" class="form-control" name="cardDate"
                                            placeholder="XX / XX" maxlength="5"
                                            pattern="^(0[1-9]|1[0-2])\/(2[4-9]|3[0-9])$" required>
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback">
                                            La fecha de caducidad es inválida o está caducada.
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-1">
                                        <label for="cvc" class="form-label">CVC: </label>
                                        <input type="text" id="cvc" class="form-control" name="cvc" placeholder="XXX"
                                            maxlength="4" pattern="[0-9]{3,4}" required>
                                        <div class="valid-feedback"></div>
                                        <div class="invalid-feedback">
                                            El campo debe contener entre 3 y 4 dígitos.
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn_purple text-white fw-bold">Realizar
                                    reserva</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </section>
        </div>
    </main>
    <?php
    require_once "../views/footer.php";
    require_once "../views/scripts.php";
    ?>
</body>

</html>