<?php
echo "<div class='container-fluid mx-auto text-center w-75 my-2' id='novedades'>
<h1 class='purple my-3'>¡Últimas novedades!</h1>
<hr class='purple_line my-3'>";

$cont = 0;
// Recorre los productos
foreach ($daoProductos->productosJSON as $key => $prod) {

    // Se lista por el id del producto para obtener posteriormente sus imágenes
    $daoFotosProductos->listarPorId($prod["id"]);

    // Abre un nuevo div de fila si es el primer producto o si ya se han mostrado 4 productos
    if ($cont == 0 || $cont % 4 == 0) {
        echo "<div class='row'>";
    }

    echo "<div class='col-sm-12 col-md-6 col-lg-6 col-xl-3 p-2 product__box'> ";
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
            echo "<img src='data:image/jpg;base64,$conte' alt='product-image-" . $prod["id"] . "-" . $imgIndex . "' class='img-fluid product-image' id='product-image-" . $prod["id"] . "-" . $imgIndex . "' style='display:" . ($imgIndex == 0 ? "block" : "none") . "'>";
            $imgIndex++;
        }
    }

    // Definición de producto
    echo "<h3 class='product__name'>" . $prod["nombre"] . "</h3>
                    <span class='purple product__price fw-bold'>" . $prod["precio"] . "€</span>
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

    // Cierra la fila si se han mostrado 4 productos
    if ($cont % 4 == 0) {
        echo "</div>"; // Cierre de la fila
    }
}

// Cierra la fila final si no se han mostrado 4 productos
if ($cont % 4 != 0) {
    echo "</div>"; // Cierre de la fila
}

// Cierre de contenedor principal
echo " </div>";
