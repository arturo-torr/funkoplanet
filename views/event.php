<!DOCTYPE html>
<html lang="es-ES">

<head>
    <?php require_once "../views/head.php"; ?>
</head>

<body>
    <?php
    require_once "../views/header.php";
    require_once "../dao/EventoDAO.php";
    require_once "../models/Evento.php";

    $id = "";
    if (isset($_GET['evento'])) {
        $id = $_GET['evento'];
    }
    $db = "funkoplanet";
    $daoEventos = new DaoEventos($db);
    ?>
    <main>
        <section class="py-3">
            <?php
            $evento = new Evento();
            $evento = $daoEventos->obtener($id);
            // Formatear la fecha epoch
            $fecha = date("d-m-Y", $evento->__get("fecha"));
            ?>

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <img src="../assets/img/eventbanner.jpg" alt="Banner-Evento-Generico"
                            class="img-fluid w-100 rounded">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-12 my-2">
                        <div class="card">
                            <div class="card-header bg_purple text-white fw-bold">
                                <h3 class="my-1 text-center fs-2"><?php echo $evento->__get("nombre"); ?></h3>
                            </div>
                            <div class="card-body p-3">
                                <p class="card-text fs-5">
                                    <?php echo $evento->__get("descripcion"); ?></p>
                                <p class="card-text text-center fs-6"><strong>¡Guarda la fecha!<br></strong>
                                    <?php echo $fecha; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php
    require_once "../views/footer.php";
    require_once "../views/scripts.php";
    ?>
</body>

</html>