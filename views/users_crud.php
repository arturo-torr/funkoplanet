<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "../views/head.php"; ?>
</head>

<body>

    <?php
    require_once "../views/header.php";
    require_once "../dao/UsuarioDAO.php";
    require_once '../models/Usuario.php';
    $db = "funkoplanet";
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
                        <form name="fUsuarios" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>"
                            enctype="multipart/form-data">
                            <fieldset>
                                <legend class='purple'>Administración de Usuarios</legend>

                                <input type='submit' class='btn btn_purple text-white fw-bold' name='Insertar'
                                    value='Insertar'>
                                <input type='submit' class='btn btn_purple text-white fw-bold' name='Buscar'
                                    value='Buscar'>
                                <input type='submit' class='btn btn_purple text-white fw-bold' name='Actualizar'
                                    value='Actualizar'>
                                <input type='submit' class='btn btn_purple text-white fw-bold' name='Borrar'
                                    value='Borrar'>
                                <input type='reset' class='btn btn_purple text-white fw-bold' name='Cancelar'
                                    value='Cancelar'>


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

                                $numPaginas = $daoUsuarios->hallarPaginas($numReg);

                                $inicio = ($pagina - 1) * $numReg; // algoritmo para mostrar los registros necesarios

                                echo "<br><label class='form-label my-2' for='numReg'>Número de registros que desea visualizar: </label>";

                                // permite recargar la página sin necesidad de tener un submit, usando javascript
                                echo "<select name='numReg' class='form-select w-25' onChange='document.fUsuarios.submit()'>";
                                for ($i = 1; $i < 11; $i++) {
                                    echo "<option value='$i' ";
                                    if ($i == $numReg) {
                                        echo " selected";
                                    }
                                    echo ">$i</option>";
                                }

                                echo "</select>";

                                // Si se ha seleccionado Insertar
                                if (isset($_POST['Insertar'])) {
                                    $username = $_POST['usernameNuevo'];
                                    $email = $_POST['emailNuevo'];
                                    $tipo = $_POST['tipoNuevo'];
                                    $monedero = $_POST['monederoNuevo'];
                                    $password = $_POST['passwordNueva'];
                                    $errores = [];

                                    // Verifica que no exista un usuario con ese nombre
                                    $usuario = $daoUsuarios->obtenerPorUsername($username);
                                    if ($usuario) {
                                        $errores[] = "Ya existe un usuario con el username $username";
                                    }

                                    // Verifica que no exista un usuario con ese email
                                    $usuario = $daoUsuarios->obtenerPorEmail($email);
                                    if ($usuario) {
                                        $errores[] = "Ya existe un usuario con el email $email";
                                    }

                                    if (empty($errores)) {
                                        $user = new Usuario();

                                        // Asignamos las propiedades correspondientes al nuevo objeto;
                                        $user->__set("username", $username);
                                        $user->__set("email", $email);
                                        $user->__set("password", password_hash($password, PASSWORD_BCRYPT));
                                        $user->__set("tipo", $tipo);
                                        $user->__set("monedero", $monedero);

                                        $daoUsuarios->insertar($user);
                                        echo "<div class='alert alert-success my-2'>Se ha insertado correctamente el usuario $username</div>";
                                    } else {
                                        // Mostrar errores
                                        foreach ($errores as $error) {
                                            echo "<div class='alert alert-danger my-2'>$error</div>";
                                        }
                                    }
                                }

                                // Si se ha seleccionado algún elemento y se ha pulsado en actualizar
                                if (isset($_POST['Actualizar']) && (isset($_POST['Selec']))) {
                                    $selec = $_POST['Selec'];
                                    $usernames = $_POST['Usernames'];
                                    $emails = $_POST['Emails'];
                                    $passwords = $_POST['Passwords'];
                                    $tipos = $_POST['Tipos'];
                                    $monederos = $_POST['Monederos'];

                                    $errores = [];

                                    // Se recorre con un ForEach para cada uno de los usuarios seleccionados
                                    foreach ($selec as $clave => $valor) {

                                        // Se realizan las validaciones de servidor
                                        if (empty($usernames[$clave])) {
                                            $errores[] = "El username con ID $clave no puede estar vacío.";
                                        }

                                        // Verifica que no exista un usuario con ese nombre
                                        $usuario = $daoUsuarios->obtenerPorUsername($usernames[$clave]);
                                        if ($usuario) {
                                            if ($usuario->__get("id") != $clave) {
                                                $errores[] = "Ya existe un usuario con el username " . $usernames[$clave] . "";
                                            }
                                        }

                                        if (!preg_match('/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/', $emails[$clave])) {
                                            $errores[] = "El email del usuario " . $usernames[$clave] . " no se ajusta al formato de un correo electrónico.";
                                        }

                                        // Verifica que no exista un usuario con ese email
                                        $usuario = $daoUsuarios->obtenerPorEmail($emails[$clave]);
                                        if ($usuario) {
                                            if ($usuario->__get("id") != $clave) {
                                                $errores[] = "Ya existe un usuario con el email " . $emails[$clave] . "";
                                            }
                                        }

                                        $usuario = $daoUsuarios->obtener($clave);
                                        $passwordHash = $usuario->__get("password");
                                        $pass = $passwords[$clave];


                                        if ($pass !== $passwordHash) {
                                            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[\w$@$!%*?&]{8,15}$/', $pass)) {
                                                $errores[] = "La contraseña del usuario " . $usernames[$clave] . " no cumple con los requisitos.";
                                            }
                                        }


                                        // Comprueba el tipo de usuario
                                        if (!preg_match('/^[APE]$/', $tipos[$clave])) {
                                            $errores[] = "El tipo de usuario de " . $usernames[$clave] . " no tiene el formato A, P ó E.";
                                        }

                                        if (empty($errores)) {
                                            $user = new Usuario();

                                            // Asignamos las propiedades correspondientes al nuevo objeto
                                            $user->__set("id", $clave);
                                            $user->__set("username", $usernames[$clave]);
                                            $user->__set("email", $emails[$clave]);
                                            $user->__set("tipo", $tipos[$clave]);
                                            $user->__set("monedero", $monederos[$clave]);

                                            if ($pass !== $passwordHash) {
                                                $user->__set("password", password_hash($pass, PASSWORD_BCRYPT));
                                            } else {
                                                $user->__set("password", $passwordHash);
                                            }
                                            $daoUsuarios->actualizar($user);
                                            echo "<div class='alert alert-success my-2'>Se ha actualizado correctamente el usuario con ID " . $clave . ": " . $usernames[$clave] . "</div>";
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
                                    // Recorre los usuarios y las va borrando por Id
                                    foreach ($selec as $clave => $valor) {
                                        $daoUsuarios->borrar($clave);
                                    }
                                }



                                // El Dao Lista todas los usuarios
                                $daoUsuarios->listarConLimite($inicio, $numReg);
                                // Si es >= a 0, la lista
                                if (count($daoUsuarios->usuarios) >= 0) {
                                    echo "<table class='mt-2 table table-hover table-bordered border_purple text-center bg_purple'>";
                                    echo "<th class='text-white fw-bold'>Selección</th>
                            <th class='text-white fw-bold'>Username</th>
                            <th class='text-white fw-bold'>Email</th>
                            <th class='text-white fw-bold'>Contraseña</th>
                            <th class='text-white fw-bold'>Tipo</th>
                            <th class='text-white fw-bold'>Monedero</th>";

                                    // Se crea la fila de inserción
                                    echo "<tr class='align-middle text-center bg-light'>";
                                    echo "<td>*</td>
                            <td><input type='text' class='form-control' name='usernameNuevo'></td>";
                                    echo "<td><input type='email' class='form-control' name='emailNuevo'></td>
                            <td><input type='password' class='form-control' name='passwordNueva'></td>
                            <td><input type='text' class='form-control' name='tipoNuevo' maxlength=1 pattern='^[APE]$' title='Solo se permiten los caracteres A, P o E'></td>
                            <td><input type='text' class='form-control' name='monederoNuevo'></td>";
                                    // FIN DE FILA DE INSERCIÓN


                                    // MOSTRADO DE TABLA
                                    foreach ($daoUsuarios->usuarios as $key => $user) {
                                        echo "<tr class='align-middle text-center bg-light'>";

                                        echo "<td><input type='checkbox' id='" . $user->__get("id") . "' class='btn-check' name='Selec[" . $user->__get("id") . "]' autocomplete='off'>
                                    <label class='btn btn-outline-danger' for='" . $user->__get("id") . "'>Seleccionar</label></td>";

                                        $nombre = $user->__get("username");
                                        echo "<td>
                                            <input type='text' class='form-control' name='Usernames[" . $user->__get("id") . "]' value='$nombre'>
                                        </td>";
                                        echo "<td>
                                            <input type='email' class='form-control' name='Emails[" . $user->__get("id") . "]' value='" . $user->__get("email") . "'>
                                        </td>";
                                        echo "<td>
                                            <input type='password' class='form-control' name='Passwords[" . $user->__get("id") . "]' value='" . $user->__get("password") . "'>
                                        </td>";
                                        echo "<td>
                                            <input type='text' class='form-control' maxlength=1 title='Solo se permiten los caracteres A, P o E' name='Tipos[" . $user->__get("id") . "]' value='" . $user->__get("tipo") . "'>
                                        </td>";
                                        echo "<td>
                                            <input type='text' class='form-control' name='Monederos[" . $user->__get("id") . "]' value='" . $user->__get("monedero") . "'>
                                        </td>";

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
            // Si se da clcik en buscar
            if (isset($_POST['Buscar'])) {
                $username = $_POST['usernameNuevo'];

                $daoUsuarios->buscar($username);
                echo "<div class='container-fluid'><div class='row'>";
                echo "<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 mx-auto table-responsive'>";
                echo "<fieldset><legend class='purple'>Resultados de la búsqueda</legend>";
                echo "<table class='mt-2 table bg_purple table-bordered border_purple text-center'>";
                echo "<th class='text-white fw-bold'>Username</th>
            <th class='text-white fw-bold'>Email</th>
            <th class='text-white fw-bold'>Tipo</th>
            <th class='text-white fw-bold'>Monedero</th>";

                foreach ($daoUsuarios->usuarios as $user) {
                    echo "<tr class='align-middle bg-light'>";
                    echo "<td>" . $user->__get("username") . "</td>";
                    echo "<td>" . $user->__get("email") . "</td>";
                    echo "<td>" . $user->__get("tipo") . "</td>";
                    echo "<td>" . $user->__get("monedero") . "</td>";
                }


                echo "</table>";
                echo "</fieldset>";
                echo "</div></div></div>";
            }
            ?>
        </section>
    </main>

    <?php
    require_once "../views/footer.php";
    require_once "../views/scripts.php";
    ?>

    <script>
    // Validaciones cliente - insertado
    $(document).ready(function() {

        // Regex para la password
        $.validator.addMethod("passPattern", function(value, element) {
                return this.optional(element) ||
                    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,15}[^'\s]$/.test(
                        value);
            },
            "La contraseña debe contener 1 mayúscula, 1 minúscula, 1 número y 1 carácter especial. La longitud mínima son 8 caracteres."
        );

        // Regex para el email
        $.validator.addMethod("emailPattern", function(value, element) {
                return this.optional(element) ||
                    /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/
                    .test(
                        value);
            },
            "El e-mail no tiene un formato correcto.");

        // Regex para tipo usuario 
        $.validator.addMethod("tipoPattern", function(value, element) {
                return this.optional(element) ||
                    /^[APE]$/
                    .test(
                        value);
            },
            "Solo se admite: A, de Admin, P, de Premium y E de Estándar.");
        $("form[name='fUsuarios']").validate({
            rules: {
                usernameNuevo: {
                    required: function(element) {
                        return $("input[name='Insertar']").is(":focus");
                    },
                    minlength: 5
                },
                emailNuevo: {
                    required: function(element) {
                        return $("input[name='Insertar']").is(":focus");
                    },
                    emailPattern: true
                },
                passwordNueva: {
                    required: function(element) {
                        return $("input[name='Insertar']").is(":focus");
                    },
                    passPattern: true,
                    minlength: 8,
                    maxlength: 15
                },
                tipoNuevo: {
                    required: function(element) {
                        return $("input[name='Insertar']").is(":focus");
                    },
                    tipoPattern: true
                },
                monederoNuevo: {
                    required: function(element) {
                        return $("input[name='Insertar']").is(":focus");
                    },
                }
            },
            messages: {
                usernameNuevo: {
                    required: "Por favor, ingrese un nombre de usuario.",
                    minlength: "El nombre de usuario debe tener al menos 5 caracteres."
                },
                emailNuevo: {
                    required: "Por favor, ingrese un correo electrónico.",
                    email: "Por favor, ingrese un correo electrónico válido."
                },
                passwordNueva: {
                    required: "Por favor, ingrese una contraseña.",
                    pattern: "La contraseña debe tener entre 8 y 15 caracteres, al menos una letra minúscula, una letra mayúscula, un número y un carácter especial.",
                    minlength: "La contraseña debe tener al menos 8 caracteres.",
                    maxlength: "La contraseña debe tener como máximo 15 caracteres."
                },
                tipoNuevo: {
                    required: "Por favor, ingrese un tipo de usuario.",
                },
                monederoNuevo: {
                    required: "Por favor, ingrese el monedero."
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
    </script>
    </script>
</body>

</html>