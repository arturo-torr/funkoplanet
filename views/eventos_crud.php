<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "../views/head.php"; ?>
</head>

<body>

    <?php
    require_once "../views/header.php";
    require_once "../dao/EventoDAO.php";
    require_once "../dao/UsuarioDAO.php";
    $db = "funkoplanet";
    $daoEventos = new DaoEventos($db);
    $daoUsuarios = new DaoUsuarios($db);

    // Comprueba si el usuario está registrado y es administrador
    if (isset($_SESSION['usuario'])) {
        $username = $_SESSION['usuario']['username'];
        $usuarioAdministrador = $daoUsuarios->obtenerPorUsername($username);
        if ($usuarioAdministrador) {
            if ($usuarioAdministrador->__get("tipo") !== "A") {
                echo "<script>window.location.href = '/funkoplanet/index.php'</script>";
            }
        } else {
            echo "<script>window.location.href = '/funkoplanet/index.php'</script>";
        }
    } else {
        echo "<script>window.location.href = '/funkoplanet/index.php'</script>";
    }
    ?>

    <main>
        <section class="py-2">
            <div class="container-fluid mt-2">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 mx-auto table-responsive">
                        <form name="fEventos" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                            <fieldset>
                                <legend class='purple'>Administración de Eventos</legend>

                                <input type='submit' class='btn btn_purple text-white fw-bold' name='Insertar' value='Insertar'>
                                <input type='submit' class='btn btn_purple text-white fw-bold' name='Buscar' value='Buscar'>
                                <input type='submit' class='btn btn_purple text-white fw-bold' name='Actualizar' value='Actualizar'>
                                <input type='submit' class='btn btn_purple text-white fw-bold' name='Borrar' value='Borrar'>
                                <input type='reset' class='btn btn_purple text-white fw-bold' name='Cancelar' value='Cancelar'>


                                <?php
                                $numReg = 5;

                                if (isset($_POST['numReg'])) {
                                    $numReg = $_POST['numReg'];
                                }

                                if (isset($_GET['numReg'])) {
                                    $numReg = $_GET['numReg'];
                                }

                                $pagina = 1; // Empezamos por defecto mostrando la página 1
                                if (isset($_GET['numPaginas'])) {
                                    $pagina = $_GET['numPaginas'];
                                }

                                $numPaginas = $daoEventos->hallarPaginas($numReg);
                                echo "<br><label class='form-label my-2' for='numReg'>Número de registros que desea visualizar: </label>";
                                // permite recargar la página sin necesidad de tener un submit, usando javascript
                                echo "<select name='numReg' class='form-select w-25' onChange='document.fEventos.submit()'>";
                                for ($i = 1; $i < 11; $i++) {
                                    echo "<option value='$i' ";
                                    if ($i == $numReg) {
                                        echo " selected";
                                    }
                                    echo ">$i</option>";
                                }

                                echo "</select>";


                                $inicio = ($pagina - 1) * $numReg; // algoritmo para mostrar los registros necesarios
                                // Convierte la fecha epoch en formato legible
                                function ConvertirLegible($fechaSeg)
                                {
                                    $campos = getdate($fechaSeg);

                                    $fechaLeg = $campos['mday'] . "/" . $campos['mon'] . "/" . $campos['year'];

                                    return $fechaLeg;
                                }
                                // Si se ha seleccionado algún elemento y se ha pulsado en actualizar
                                if (isset($_POST['Actualizar']) && (isset($_POST['Selec']))) {
                                    $selec = $_POST['Selec'];
                                    $usuarios = $_POST['Usuarios'];
                                    $fechas = $_POST['Fechas'];
                                    $nombres = $_POST['Nombres'];
                                    $descripciones = $_POST['Descripciones'];

                                    $errores = [];
                                    // Se recorre con un ForEach para cada uno de los mascotas seleccionados
                                    foreach ($selec as $clave => $valor) {

                                        // Se realizan las validaciones de servidor
                                        if (empty($nombres[$clave])) {
                                            $errores[] = "El nombre del evento con ID $clave no puede estar vacío.";
                                        }

                                        if (empty($descripciones[$clave])) {
                                            $errores[] = "La descripción del evento " . $nombres[$clave] . " no puede estar vacía.";
                                        }

                                        if (empty($usuarios[$clave])) {
                                            $errores[] = "El usuario del evento " . $nombres[$clave] . " no puede estar vacío.";
                                        }

                                        if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $fechas[$clave])) {
                                            $errores[] = "La fecha del evento " . $nombres[$clave] . " no se ajusta al formato dd/mm/yyyy";
                                        }

                                        if (empty($errores)) {
                                            // Almacenamos en array la fecha de nacimiento para ese Id
                                            $camposFecha = explode("/", $fechas[$clave]);

                                            // La convertimos a epoch para guardarla de esta forma
                                            $fechaEpoch = mktime(0, 0, 0, $camposFecha[1], $camposFecha[0], $camposFecha[2]);


                                            // Creamos una nueva situación
                                            $event = new Evento();

                                            // Asignamos las propiedades correspondientes al nuevo objeto
                                            $event->__set("id", $clave);
                                            $event->__set("id_usuario", $usuarios[$clave]);
                                            $event->__set("nombre", $nombres[$clave]);
                                            $event->__set("descripcion", $descripciones[$clave]);
                                            $event->__set("fecha", $fechaEpoch);

                                            $daoEventos->actualizar($event);
                                            echo "<div class='alert alert-success my-2'>Se ha actualizado correctamente el evento con ID $clave: " . $nombres[$clave] . "</div>";
                                        } else {
                                            // Mostrar errores
                                            foreach ($errores as $error) {
                                                echo "<div class='alert alert-danger my-2'>$error</div>";
                                            }
                                        }
                                    }
                                }

                                // Si se ha seleccionado algún elemento y se ha pulsado en Borrar
                                if (isset($_POST['Borrar']) && (isset($_POST['Selec']))) {
                                    $selec = $_POST['Selec'];
                                    // Recorre las mascotas y las ba borrando por Id
                                    foreach ($selec as $clave => $valor) {
                                        $daoEventos->borrar($clave);
                                    }
                                }

                                // Si se ha seleccionado Insertar
                                if (isset($_POST['Insertar'])) {
                                    $usuario = $_POST['usuarioNuevo'];
                                    $nombre = $_POST['nombreNuevo'];
                                    $descripcion = $_POST['descripcionNueva'];
                                    $fecha = $_POST['fechaNueva'];

                                    $errores = [];

                                    // Se realizan las validaciones de servidor
                                    if (empty($nombre)) {
                                        $errores[] = "El nombre del evento no puede estar vacío.";
                                    }

                                    if (empty($descripcion)) {
                                        $errores[] = "La descripción no puede estar vacía.";
                                    }

                                    if (empty($usuario)) {
                                        $errores[] = "El usuario creador del evento no puede estar vacío.";
                                    }

                                    if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $fecha)) {
                                        $errores[] = "La fecha del evento no se ajusta al formato dd/mm/yyyy";
                                    }

                                    if (empty($errores)) {
                                        // Almacenamos en array la fecha
                                        $camposFecha = explode("/", $fecha);

                                        // La convertimos a epoch para guardarla de esta forma
                                        $fechaEpoch = mktime(0, 0, 0, $camposFecha[1], $camposFecha[0], $camposFecha[2]);


                                        // Creamos una nueva situación
                                        $event = new Evento();
                                        $event->__set("id_usuario", $usuario);
                                        $event->__set("nombre", $nombre);
                                        $event->__set("descripcion", $descripcion);
                                        $event->__set("fecha", $fechaEpoch);

                                        $daoEventos->insertar($event);
                                        echo "<div class='alert alert-success my-2'>Se ha insertado correctamente el evento $nombre</div>";
                                    } else {
                                        foreach ($errores as $error) {
                                            echo "<div class='alert alert-danger my-2'>$error</div>";
                                        }
                                    }
                                }

                                // El Dao Lista todos los Eventos
                                $daoEventos->listarConLimite($inicio, $numReg);

                                // Si es >= a 0, la lista
                                if (count($daoEventos->eventos) >= 0) {
                                    echo "<table class='mt-2 table table-hover table-bordered border_purple text-center bg_purple'>";
                                    echo "<th class='text-white fw-bold'>Selección</th>
                            <th class='text-white fw-bold'>Nombre</th>
                            <th class='text-white fw-bold'>Descripción</th>
                            <th class='text-white fw-bold'>Fecha</th>
                            <th class='text-white fw-bold'>Usuario</th>";

                                    // Se crea la fila de inserción
                                    echo "<tr class='align-middle text-center bg-light'>";
                                    echo "<td>*</td>
                            <td><input type='text' class='form-control' name='nombreNuevo'></td>";
                                    echo "<td><input type='text' class='form-control' name='descripcionNueva'></td>
                            <td><input type='text' class='form-control' name='fechaNueva' placeholder='dd/mm/yyyy'></td>";
                                    echo "<td> <select name='usuarioNuevo' class='form-select'> ";
                                    echo "<option value=''></option>";
                                    $daoUsuarios->listarAdmins();
                                    foreach ($daoUsuarios->usuarios as $key => $user) {
                                        echo "<option value=" . $user->__get("id");
                                        echo ">" . $user->__get("username") . "</option>";
                                    }
                                    echo "</select></td>";

                                    // FIN DE FILA DE INSERCIÓN


                                    // MOSTRADO DE TABLA
                                    foreach ($daoEventos->eventos  as $key => $event) {
                                        echo "<tr class='align-middle text-center bg-light'>";
                                        echo "<td><input type='checkbox' id='" . $event->__get("id") . "' class='btn-check' name='Selec[" . $event->__get("id") . "]' autocomplete='off'>
                                <label class='btn btn-outline-danger' for='" . $event->__get("id") . "'>Seleccionar</label></td>";
                                        $nombre = $event->__get("nombre");
                                        echo "<td>
                                <input type='text' class='form-control' name='Nombres[" . $event->__get("id") . "]' value='$nombre'>
                                </td>
                                ";
                                        $descripcion = $event->__get("descripcion");
                                        echo "
                                <td>
                                    <input type='text' class='form-control' name='Descripciones[" . $event->__get("id") . "]' value='$descripcion'>
                                </td>";
                                        // Hay que convertir la fecha de nacimiento a un formato legible y no epoch
                                        $fechaLegible = ConvertirLegible($event->__get("fecha"));
                                        echo "
                                <td>
                                    <input type='text' class='form-control' name='Fechas[" . $event->__get("id") . "]' value=$fechaLegible>
                                </td>";
                                        echo "<td> <select class='form-select' name='Usuarios[" . $event->__get("id") . "]'>";
                                        $daoUsuarios->listarAdmins();
                                        foreach ($daoUsuarios->usuarios as $key => $user) {
                                            echo "<option value=" . $user->__get("id");

                                            if ($event->__get("id_usuario") == $user->__get("id")) {
                                                echo " selected";
                                            }
                                            echo ">" . $user->__get("username") . "</option>";
                                        }
                                        echo "</select></td>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }

                                    echo "</table>";

                                    echo "<ul class='pagination'>";
                                    // lleva un enlace al número de páginas y recoge el número de registros
                                    for ($i = 1; $i <= $numPaginas; $i++) {
                                        echo "
                                <li class='page-item'>
                                <a class='page-link text-white bg_purple ";
                                        if ($i == $pagina) {
                                            echo "active_link";
                                        }
                                        echo "' href='$_SERVER[PHP_SELF]?numPaginas=$i&numReg=$numReg'>$i</a>&nbsp;&nbsp;
                                </li>";
                                    }

                                    echo "</ul>";
                                }
                                ?>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            // Si se da click en buscar
            if (isset($_POST['Buscar'])) {
                $nombre = $_POST['nombreNuevo'];
                $usuario = $_POST['usuarioNuevo'];
                $fecha = $_POST['fechaNueva'];
                $fechaEpoch = "";

                if (!empty($fecha)) {
                    // Almacenamos en array la fecha
                    $camposFecha = explode("/", $fecha);

                    // La convertimos a epoch para guardarla de esta forma
                    $fechaEpoch = mktime(0, 0, 0, $camposFecha[1], $camposFecha[0], $camposFecha[2]);
                }

                $daoEventos->buscar($nombre, $usuario, $fechaEpoch);
                echo "<div class='container-fluid' id='busquedaEventos'><div class='row'>";
                echo "<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 mx-auto table-responsive'>";
                echo "<fieldset><legend class='purple'>Resultados de la búsqueda</legend>";
                if (count($daoEventos->eventos) > 0) {
                    echo "<table class='mt-2 table bg_purple table-bordered border_purple text-center'>
                <th class='text-white fw-bold'>Nombre</th><th class='text-white fw-bold'>Descripción</th><th class='text-white fw-bold'>Fecha</th><th class='text-white fw-bold'>Usuario</th>";

                    foreach ($daoEventos->eventos as $event) {
                        echo "<tr class='align-middle bg-light'>";
                        echo "<td>" . $event->__get("nombre") . "</td>";
                        echo "<td>" . $event->__get("descripcion") . "</td>";
                        $fechaLegible = ConvertirLegible($event->__get("fecha"));
                        echo "<td>" . $fechaLegible . "</td>";
                        $usuario = $daoUsuarios->obtener($event->__get("id_usuario"));
                        echo "<td>" . $usuario->__get("username") . "</td>";
                    }


                    echo "</table>";
                } else {
                    echo "<div class='alert alert-warning my-2'>No ha habido resultados para la búsqueda.</div>";
                }
                echo "</fieldset>";
                echo "</div></div></div>";
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    let busqueda = document.getElementById('busquedaEventos');
                    if (busqueda) busqueda.scrollIntoView({ behavior: 'smooth' });
                });
            </script>";
            }
            ?>
        </section>
    </main>


    <?php
    require_once "../views/footer.php";
    require_once "../views/scripts.php";
    ?>
    <script>
        // Validación en la inserción de eventos
        $(document).ready(function() {
            // Método personalizado para el formato de las fechas
            $.validator.addMethod("fechaPattern", function(value, element) {
                return this.optional(element) || /^\d{2}\/\d{2}\/\d{4}$/.test(value);
            }, "El formato debe ser dd/mm/yyyy.");
            $("form[name='fEventos']").validate({
                rules: {
                    nombreNuevo: {
                        required: function(element) {
                            return $("input[name='Insertar']").is(":focus");
                        },
                        minlength: 5
                    },
                    descripcionNueva: {
                        required: function(element) {
                            return $("input[name='Insertar']").is(":focus");
                        }
                    },
                    fechaNueva: {
                        required: function(element) {
                            return $("input[name='Insertar']").is(":focus");
                        },
                        fechaPattern: true
                    },
                    usuarioNuevo: {
                        required: function(element) {
                            return $("input[name='Insertar']").is(":focus");
                        }
                    }
                },
                messages: {
                    nombreNuevo: {
                        required: "Ingrese un nombre.",
                        minlength: "El nombre debe tener al menos 5 caracteres."
                    },
                    descripcionNueva: {
                        required: "Ingrese una descripción."
                    },
                    fechaNueva: {
                        required: "Ingrese una fecha.",
                    },
                    usuarioNuevo: {
                        required: "Seleccione un usuario."
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>