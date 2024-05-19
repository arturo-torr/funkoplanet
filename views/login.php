<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    require_once "../views/head.php";
    ?>
</head>

<body>
    <?php
    require_once "../views/header.php";
    require_once '../includes/libreriaPDO.php';
    require_once '../dao/UsuarioDAO.php';
    require_once '../dao/LoginDAO.php';

    $db = "funkoplanet";
    $daoLogin = new DaoLogin($db);
    $daoUsuarios = new DaoUsuarios($db);
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header bg_purple">
                        <h5 class="card-title text-center text-white fw-bold">¡Bienvenido a FunkoPlanet</h5>
                    </div>
                    <div class="card-body">
                        <form name="fLogin" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                            <div class="mb-3 form-group">
                                <label for="email" class="form-label">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-user-square" width="30" height="30"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 10a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                            <path d="M6 21v-1a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v1" />
                                            <path
                                                d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control" id="username" name="username">
                                </div>
                            </div>
                            <div class="mb-3 form-group">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-password" width="30" height="30"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 10v4" />
                                            <path d="M10 13l4 -2" />
                                            <path d="M10 11l4 2" />
                                            <path d="M5 10v4" />
                                            <path d="M3 13l4 -2" />
                                            <path d="M3 11l4 2" />
                                            <path d="M19 10v4" />
                                            <path d="M17 13l4 -2" />
                                            <path d="M17 11l4 2" />
                                        </svg>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                            <!-- Botón de inicio de sesión -->
                            <button type="submit" name='Enviar' class="btn btn_purple text-white fw-bold">Iniciar
                                sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="incorrectoModal" tabindex="-1" aria-labelledby="incorrectoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg_purple text-white">
                    <h1 class="modal-title fs-5 fw-bold" id="incorrectoModalLabel">Usuario o contraseña incorrectos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    El usuario o contraseña que ha introducido son incorrectos. Por favor, inténtelo de nuevo.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn_purple text-white fw-bold"
                        data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once "../views/scripts.php";
    ?>
    <script>
    function showIncorrectoModal() {
        let myModal = new bootstrap.Modal(document.getElementById('incorrectoModal'));
        myModal.show();
    }
    </script>

    <?php
    function intentoLogin($user, $pass)
    {
        global $daoUsuarios;
        global $daoLogin;
        $fila = "";

        if (($user) && ($pass)) {
            $filaPass = $daoUsuarios->obtenerPass($user);

            if (password_verify("$pass", $filaPass['password'])) {
                $fila = $daoUsuarios->login($user);
                $acceso = "C";
            } else {
                $acceso = "D";
            }
            $daoLogin->insertarIntento($user, $pass, $acceso);
        }

        return $fila;
    }
    // Si se ha pulsado en enviar
    if (isset($_POST['Enviar'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $fila = intentoLogin($user, $pass);

        if (($fila)  && ($user !== "") && ($pass !== "")) {
            // Si el intento es correcto, coge las variables de sesión y redirige al index
            $_SESSION['usuario'] = array(
                'username' => $user,
                'rol' => $fila['tipo'] // Agrega el rol a la sesión
            );
            echo "<script>localStorage.setItem('user', '$user');</script>";
            echo "<script>
    window.location.replace('/funkoplanet/index.php');
    </script>";
        } else {
            echo "<script>
    showIncorrectoModal();
    </script>";
        }
    }
    ?>

</body>

</html>