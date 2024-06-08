<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "../views/head.php"; ?>
</head>

<body>

    <?php
    require_once "../views/header.php";
    require_once "../dao/CategoriaDAO.php";
    require_once '../models/Categoria.php';
    require_once "../dao/UsuarioDAO.php";
    $db = "funkoplanet";
    $daoCategorias = new DaoCategorias($db);
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
                        <form name="fCategorias" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" novalidate>
                            <fieldset>
                                <legend class='purple'>Administración de Categorías</legend>

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

                                $numPaginas = $daoCategorias->hallarPaginas($numReg);

                                $inicio = ($pagina - 1) * $numReg; // algoritmo para mostrar los registros necesarios
                                echo "<br><label class='form-label my-2' for='numReg'>Número de registros que desea visualizar: </label>";

                                // permite recargar la página sin necesidad de tener un submit, usando javascript
                                echo "<select name='numReg' class='form-select w-25' onChange='document.fCategorias.submit()'>";
                                for ($i = 1; $i < 11; $i++) {
                                    echo "<option value='$i' ";
                                    if ($i == $numReg) {
                                        echo " selected";
                                    }
                                    echo ">$i</option>";
                                }

                                echo "</select>";
                                // Recupera la foto anterior que tenía la categoría
                                function FotoAnterior($id)
                                {
                                    global $daoCategorias;
                                    $cat = $daoCategorias->obtener($id);
                                    return $cat->__get("foto");
                                }

                                function comprobarValidezImagen($archivo)
                                {
                                    $tiposPermitidos = ['image/jpeg', 'image/png'];
                                    $tipoArchivo = mime_content_type($archivo);
                                    return in_array($tipoArchivo, $tiposPermitidos);
                                }

                                function comprobarTamanoimagen($archivo, $tamanoMaximo)
                                {
                                    return filesize($archivo) <= $tamanoMaximo;
                                }

                                // Si se ha seleccionado algún elemento y se ha pulsado en actualizar
                                if (isset($_POST['Actualizar']) && (isset($_POST['Selec']))) {
                                    $selec = $_POST['Selec'];
                                    $nombres = $_POST['Nombres'];
                                    $descripciones = $_POST['Descripciones'];

                                    $errores = [];

                                    // Se recorre con un ForEach para cada uno de los productos seleccionados
                                    foreach ($selec as $clave => $valor) {

                                        $categoria = $daoCategorias->obtenerPorNombre($nombres[$clave]);
                                        if ($categoria) {
                                            if ($categoria->__get("id") != $clave) {
                                                $errores[] = "No se ha podido actualizar la categoría. Ya existe una categoría con el nombre " . $nombres[$clave] . "";
                                            }
                                        }

                                        if (strlen($nombres[$clave]) < 3) {
                                            $errores[] = "No se ha podido actualizar la categoría " . $nombres[$clave] . ", el nombre debe tener al menos 3 caracteres.";
                                        }

                                        if (strlen($descripciones[$clave]) < 10) {
                                            $errores[] = "No se ha podido actualizar la categoría " . $nombres[$clave] . ", la descripción debe tener al menos 10 caracteres.";
                                        }

                                        // Por defecto el campo foto es la foto anterior que tenía la mascota
                                        $foto = FotoAnterior($clave);

                                        if ($_FILES['Fotos']['name'][$clave] != "") {
                                            $temp = $_FILES['Fotos']['tmp_name'][$clave];
                                            if (comprobarValidezImagen($temp) && comprobarTamanoimagen($temp, 1048576)) { // 1MB = 1048576 bytes
                                                $contenido = file_get_contents($temp);
                                                $contenido = base64_encode($contenido);
                                                $foto = $contenido;
                                            } else {
                                                $errores[] = "El archivo para la categoría " . $nombres[$clave] . " debe ser PNG o JPG y no superar el tamaño máximo de 1MB.";
                                            }
                                        }

                                        if (empty($errores)) {
                                            // Creamos una nueva situación
                                            $cate = new Categoria();
                                            // Asignamos las propiedades correspondientes al nuevo objeto
                                            $cate->__set("id", $clave);
                                            $cate->__set("nombre", $nombres[$clave]);
                                            $cate->__set("descripcion", $descripciones[$clave]);
                                            $cate->__set("foto", $foto);

                                            $daoCategorias->actualizar($cate);
                                            echo "<div class='alert alert-success my-2'>Se ha actualizado correctamente la categoría con ID " . $clave . ": " . $nombres[$clave] . "</div>";
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
                                        $daoCategorias->borrar($clave);
                                    }
                                }

                                // Si se ha seleccionado Insertar
                                if (isset($_POST['Insertar'])) {
                                    $nombre = $_POST['nombreNuevo'];
                                    $descripcion = $_POST['descripcionNueva'];

                                    $errores = [];
                                    $categoria = $daoCategorias->obtenerPorNombre($nombre);
                                    if ($categoria) {
                                        $errores[] = "No se ha podido insertar la categoría $nombre. Ya existe una categoría con el nombre " . $nombre . "";
                                    }
                                    // Por defecto el campo foto es vacío
                                    $foto = "";

                                    // Si obtenemos una foto, la recuperamos y la guardamos en la variable $foto
                                    if ($_FILES['fotoNueva']['name'] != "") {
                                        $temp = $_FILES['fotoNueva']['tmp_name'];
                                        if (comprobarValidezImagen($temp) && comprobarTamanoimagen($temp, 1048576)) { // 1MB = 1048576 bytes
                                            $contenido = file_get_contents($temp);
                                            $contenido = base64_encode($contenido);
                                            $foto = $contenido;
                                        } else {
                                            $errores[] = "El archivo para la categoría " . $nombre . " debe ser PNG o JPG y no superar el tamaño máximo de 1MB.";
                                        }
                                    }

                                    if (empty($errores)) {
                                        // Creamos una nueva situación
                                        $cate = new Categoria();
                                        $cate->__set("nombre", $nombre);
                                        $cate->__set("descripcion", $descripcion);
                                        $cate->__set("foto", $foto);

                                        $daoCategorias->insertar($cate);
                                        echo "<div class='alert alert-success my-2'>Se ha insertado correctamente la categoría $nombre</div>";
                                    } else {
                                        // Mostrar errores
                                        foreach ($errores as $error) {
                                            echo "<div class='alert alert-danger my-2'>$error</div>";
                                        }
                                    }
                                }

                                // El Dao Lista todas las categorías
                                $daoCategorias->listarConLimite($inicio, $numReg);

                                // Si es >= a 0, la lista
                                if (count($daoCategorias->categoriasObjetos) >= 0) {
                                    echo "<table class='mt-2 table table-hover table-bordered border_purple text-center bg_purple'>";
                                    echo "<th class='text-white fw-bold'>Selección</th><th class='text-white fw-bold'>Nombre</th><th class='text-white fw-bold'>Descripción</th><th class='text-white fw-bold'>Foto</th>";
                                    // Se crea la fila de inserción
                                    echo "<tr class='align-middle text-center bg-light'>";
                                    echo "<td>*</td>
                            <td>
                                <input type='text' class='form-control' id='nombreNuevo' name='nombreNuevo'>
                                <div class='invalid-feedback'>Debes introducir el nombre de la categoría obligatoriamente.</div>
                                <div class='valid-feedback'></div>
                            </td>";
                                    echo "<td>
                                <input type='text' class='form-control' id='descripcionNueva' name='descripcionNueva'>
                                <div class='invalid-feedback'>Debes introducir la descripción de la categoría obligatoriamente.</div>
                                <div class='valid-feedback'></div>
                            </td>
                            <td>
                                <img class='img-fluid mx-2' width=100></img><input type='file' class='form-control-sm' id='fotoNueva' name='fotoNueva'>
                                <div class='invalid-feedback'>El fichero debe ser jpg/png.</div>
                                <div class='valid-feedback'></div>
                            </td>";

                                    // FIN DE FILA DE INSERCIÓN


                                    // MOSTRADO DE TABLA
                                    foreach ($daoCategorias->categoriasObjetos as $key => $cate) {
                                        echo "<tr class='align-middle text-center bg-light'>";
                                        echo "<td><input type='checkbox' id='" . $cate->__get("id") . "' class='btn-check' name='Selec[" . $cate->__get("id") . "]' autocomplete='off'>
                                <label class='btn btn-outline-danger' for='" . $cate->__get("id") . "'>Seleccionar</label></td>";
                                        $nombre = $cate->__get("nombre");
                                        echo "<td>
                                    <input type='text' class='form-control' id='" . $cate->__get("id") . "' name='Nombres[" . $cate->__get("id") . "]' value='$nombre' required>
                                    <div class='invalid-feedback'>Debes introducir el nombre de la categoría obligatoriamente.</div>
                                    <div class='valid-feedback'></div>
                                </td>";

                                        $descripcion = $cate->__get("descripcion");
                                        echo "<td>
                                    <input type='text' class='form-control' id='" . $cate->__get("id") . "' name='Descripciones[" . $cate->__get("id") . "]' value='$descripcion'>
                                    <div class='invalid-feedback'>Debes introducir una descripción obligatoriabmente.</div>
                                    <div class='valid-feedback'></div>
                                </td>";
                                        $conte = $cate->__get("foto");
                                        echo "<td>
                                        <img src='data:image/jpg;base64,$conte' class='img-fluid mx-2' width=100 height=100 alt='Cat:" . $cate->__get("id") . "'>";
                                        echo "<input type='file' class='form-control-sm' id='" . $cate->__get("id") . "' name='Fotos[" . $cate->__get("id") . "]'>";
                                        echo "<div class='invalid-feedback'>El fichero debe ser jpg/png.</div>
                                <div class='valid-feedback'></div></td>";
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
                $daoCategorias->buscar($nombre);
                echo "<div class='container-fluid' id='busquedaCategorias'><div class='row'>";
                echo "<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 mx-auto table-responsive'>";
                echo "<fieldset><legend class='purple'>Resultados de la búsqueda</legend>";
                if (count($daoCategorias->categoriasObjetos) > 0) {
                    echo "<table class='mt-2 table bg_purple table-bordered border_purple text-center'>
                <th class='text-white fw-bold'>Nombre</th><th class='text-white fw-bold'>Descripción</th><th class='text-white fw-bold'>Foto</th>";

                    foreach ($daoCategorias->categoriasObjetos as $cate) {
                        echo "<tr class='align-middle bg-light'>";
                        echo "<td>" . $cate->__get("nombre") . "</td>";
                        echo "<td>" . $cate->__get("descripcion") . "</td>";
                        $conte = $cate->__get("foto");
                        echo "<td>
                           <img src='data:image/jpg;base64,$conte' width=70 height=70 alt='Cat:" . $cate->__get("id") . "'>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<div class='alert alert-warning my-2'>No ha habido resultados para la búsqueda.</div>";
                }
                echo "</fieldset>";
                echo "</div></div></div>";
            }
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                let busqueda = document.getElementById('busquedaCategorias');
                if (busqueda) busqueda.scrollIntoView({ behavior: 'smooth' });
            });
        </script>";
            ?>
        </section>
    </main>
    <?php
    require_once "../views/footer.php";
    require_once "../views/scripts.php";
    ?>
    <script>
        $(document).ready(function() {
            // Agrega el método de validación personalizado para archivos de imagen
            $.validator.addMethod("imageType", function(value, element) {
                if (element.files.length === 0) {
                    return true; // No hay archivo, no validar
                }
                const fileType = element.files[0].type;
                return fileType === "image/jpeg" || fileType === "image/png";
            }, "Solo se permiten archivos JPG o PNG.");

            $("form[name='fCategorias']").validate({
                rules: {
                    nombreNuevo: {
                        required: function(element) {
                            return $("input[name='Insertar']").is(":focus");
                        },
                        minlength: 3
                    },
                    descripcionNueva: {
                        required: function(element) {
                            return $("input[name='Insertar']").is(":focus");
                        },
                        minlength: 10
                    },
                    fotoNueva: {
                        required: function(element) {
                            return $("input[name='Insertar']").is(":focus");
                        },
                        imageType: true
                    },
                },
                messages: {
                    nombreNuevo: {
                        required: "Ingrese un nombre.",
                        minlength: "El nombre debe tener al menos 3 caracteres."
                    },
                    descripcionNueva: {
                        required: "Ingrese una descripción.",
                        minlength: "La descripción debe tener al menos 10 caracteres."
                    },
                    fotoNueva: {
                        required: "Seleccione una imagen."
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>