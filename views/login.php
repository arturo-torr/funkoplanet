<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    require_once "../views/head.php";
    ?>
</head>

<body>
    <?php
    function bloqueado($user, $loginDAO)
    {
        $intentos = $loginDAO->obtenerUltimosIntentos($user);
        $bloqueado = -1;
        $tiempoBloqueo = 300; // 5 minutos

        if ($loginDAO->tresDenegaciones($intentos) && $loginDAO->menorTiempoInt($intentos, $tiempoBloqueo)) {
            $bloqueado = time() + $tiempoBloqueo;
        }

        return $bloqueado;
    }

    function intentoLogin($user, $pass, $usuarioDAO, $loginDAO)
    {
        $fila = $usuarioDAO->login($user, $pass);
        $acceso = ($fila) ? "C" : "D";
        $loginDAO->insertarIntento($user, sha1($pass), $acceso);
        return $fila;
    }

    require_once "../views/header.php";
    require_once '../includes/libreriaPDO.php';
    require_once '../dao/UsuarioDAO.php';
    require_once '../dao/LoginDAO.php';
    $db = "funkoplanet";
    $loginDAO = new DaoLogin($db);
    $usuarioDAO = new DaoUsuarios($db);
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
    <?php
    if (isset($_POST['Enviar'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $bloqueado = bloqueado($user, $loginDAO);

        if ($bloqueado == -1) {
            $fila = intentoLogin($user, $pass, $usuarioDAO, $loginDAO);

            if ($fila) {
                $_SESSION['usuario'] = $user;
                echo "<script>window.location.replace('/funkoplanet/index.php');</script>";
            } else {
                echo "<br> Usuario o clave incorrecto </b>";
            }
        } else {
            $hora = date("H:i:s", $bloqueado);
            $dia = date("d/m/Y", $bloqueado);
            echo "El usuario $user está bloqueado hasta las $hora de $dia";
        }
    }


    require_once "../views/scripts.php";
    ?>
</body>

</html>