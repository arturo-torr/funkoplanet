<?php
echo "<div class='container-fluid text-center'>
            <h1 class='purple mt-2'>¡Últimas novedades!</h1>
            <hr class='purple_line'>";

$cont = 0;
foreach ($daoProductos->productosJSON as $key => $prod) {
    if ($cont == 0  || $cont % 4 == 0) {
        echo "<div class='row mx-auto'>";
        echo "<div class='col-sm-0 col-md-0 col-lg-0 col-xl-2'></div>";
    }

    $daoFotosProductos->listarUnaImagenPorId($prod["id"]);
    echo "<div class='col-sm-12 col-md-6 col-lg-6 col-xl-2 my-3 p-3 product__box'>
                        <a class='no_decoration' data-product='" . $prod["id"] . "' href='#'>
                            <div class='p-3 bg-white'>";

    foreach ($daoFotosProductos->fotosPro as $key => $fotoPro) {
        $conte = $fotoPro->__get("foto");
        echo "<img src='data:image/jpg;base64,$conte' class='img-fluid'>";
    }

    echo "<h3 class='product__name'>" . $prod["nombre"] . "</h3>
                                <span class='purple'>" . $prod["precio"] . "€</span>
                                <br>
                                <button class='btn btn_purple fw-bold text-white no_decoration mt-2'>COMPRAR</button>
                            </div>
                        </a>
                    </div>";
    $cont++;

    if ($cont % 4 == 0) {
        echo "<div class='col-sm-0 col-md-0 col-lg-0 col-xl-2'></div>
        </div>";
    }
}
echo " </div>";
