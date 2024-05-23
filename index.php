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
        <section id="carousel_section">
            <div id="carousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-item active">
                        <img src="./assets/img/carousel_1.jpg" class="d-block w-100" alt="Carousel-1-Img">
                    </div>
                    <div class="carousel-item">
                        <img src="./assets/img/carousel_2.jpg" class="d-block w-100" alt="Carousel-2-Img">
                    </div>
                    <div class="carousel-item">
                        <img src="./assets/img/carousel_3.jpg" class="d-block w-100" alt="Carousel-3-Img">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>

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
    require_once "./views/footer.php";
    require_once "./views/scripts.php";
    ?>
</body>

</html>