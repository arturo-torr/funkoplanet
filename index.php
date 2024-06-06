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
        <section id="carousel_section" class="my-3">
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
                        <a href='#novedades'><img src="./assets/img/carousel_1.jpg" class="d-block w-100"
                                alt="Carousel-1-Img"></a>
                    </div>
                    <div class="carousel-item">
                        <a href='/funkoplanet/views/products.php?category=&orden=nuevos&Filtrar=Filtrar'><img
                                src="./assets/img/carousel_2.jpg" class="d-block w-100" alt="Carousel-2-Img"></a>
                    </div>
                    <div class="carousel-item">
                        <a href="/funkoplanet/views/products.php?category=33"><img src="./assets/img/carousel_3.jpg"
                                class="d-block w-100" alt="Carousel-3-Img"></a>
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

        <section id="nuevos_productos" class="my-3" data-aos='fade-up' data-aos-duration='500'>
            <?php
            $parametro = "nuevosProductos";
            require_once "web/controlador_productos.php";
            ?>
        </section>
        <section id="central_zone" class="my-3" data-aos='fade-up' data-aos-duration='500'>
            <?php
            $paramCategorias = "categoriesCentral";
            require_once "web/controlador_categorias.php";
            ?>
        </section>
        <section id="beneficios" class="my-3" data-aos='fade-up' data-aos-duration='500'>
            <div class='container-fluid mx-auto w-75'>
                <h1 class='purple my-2 text-center'>Beneficios de FunkoPlanet</h1>
                <hr class="purple_line my-2">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                        <div class="card border-0">
                            <img src="./assets/img/cohete.gif" class="w-50 mx-auto" alt="Card-Cohete">
                            <div class="card-body">
                                <h5 class="card-title text-center purple fw-bold">Rapidez</h5>
                                <p class="card-text text-center">Todos nuestros envíos salen del almacen el mismo día
                                    que lo has
                                    pedido si es antes de las 18.00h. </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                        <div class="card border-0">
                            <img src="./assets/img/globo.gif" class="w-50 mx-auto" alt="Card-Descuentos">
                            <div class="card-body">
                                <h5 class="card-title text-center purple fw-bold">Descuentos</h5>
                                <p class="card-text text-center">¿Es tu cumpleaños? ¡Estás de suerte! Con FunkoPlanet
                                    contarás con divertidos descuentos.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                        <div class="card border-0">
                            <img src="./assets/img/ayuda.gif" class="w-50 mx-auto" alt="Card-Ayuda">
                            <div class="card-body">
                                <h5 class="card-title text-center purple fw-bold">Ayuda</h5>
                                <p class="card-text text-center">¿No sabes qué Funko elegir? ¡Nosotros te ayudamos!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                        <div class="card border-0">
                            <img src="./assets/img/planeta-verde.gif" class="w-50 mx-auto" alt="Card-Sostenible">
                            <div class="card-body">
                                <h5 class="card-title text-center purple fw-bold">Sostenibilidad</h5>
                                <p class="card-text text-center">FunkoPlanet solo utiliza embalajes de cartón 100%
                                    reciclado para sus envíos. ¡Cuidemos el planeta!</p>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </main>
    <?php
    require_once "./views/footer.php";
    require_once "./views/scripts.php";
    ?>
</body>

</html>