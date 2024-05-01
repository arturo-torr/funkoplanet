<?php
echo "<div class='container-fluid text-center'>
<h1 class='purple mt-2'>¡Últimas novedades!</h1>
<hr class='purple_line'>";

$cont = 0;
// Recorre los productos
foreach ($daoProductos->productosJSON as $key => $prod) {
    // Nueva row si es la primera vez que lo recorre o es múltiplo de 4, que son los productos que queremos mostrar como máximo en pantallas grandes
    if ($cont == 0  || $cont % 4 == 0) {
        echo "<div class='row mx-auto'>";
        echo "<div class='col-sm-0 col-md-0 col-lg-0 col-xl-2'></div>";
    }

    // Se lista por el id del producto para obtener posteriormente sus imágenes
    $daoFotosProductos->listarPorId($prod["id"]);
    echo "<div class='col-sm-12 col-md-6 col-lg-6 col-xl-2 my-3 p-3 product__box'>
            <a class='no_decoration' data-product='" . $prod["id"] . "' href='#'>
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
                    <span class='purple'>" . $prod["precio"] . "€</span>
                    <br>
                    <button class='btn btn_purple fw-bold text-white no_decoration mt-2'>COMPRAR</button>
                </div>
            </a>
        </div>";
    $cont++;

    // Si es múltiplo de 4 cierra los contenedores
    if ($cont % 4 == 0) {
        echo "<div class='col-sm-0 col-md-0 col-lg-0 col-xl-2'></div>
</div>";
    }
}

// Cierre de contenedor principal
echo " </div>";
