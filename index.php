<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require_once "./views/head.php";
    ?>
</head>

<body>
    <?php
    require_once "./views/header.php";
    ?>
    <main>

        <section id="nuevos_productos" data-aos='fade-up' data-aos-duration='1000'>
            <?php
            $parametro = "nuevosProductos";
            require_once "web/controlador_productos.php";
            ?>
        </section>
        <section id="central_zone" data-aos='fade-up' data-aos-duration='1000'>
            <?php
            $paramCategorias = "categoriesCentral";
            require_once "web/controlador_categorias.php";
            ?>
        </section>
    </main>
    <?php
    require_once "./views/scripts.php";
    ?>
</body>

</html>