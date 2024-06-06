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
    require_once "../models/Usuario.php";
    require_once '../dao/UsuarioDAO.php';
    require_once '../dao/LoginDAO.php';

    $db = "funkoplanet";
    $daoLogin = new DaoLogin($db);
    $daoUsuarios = new DaoUsuarios($db);
    ?>

    <main>
        <section class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg_purple">
                                <h5 class="card-title text-center text-white fw-bold my-2">¡Bienvenido a FunkoPlanet
                                </h5>
                            </div>
                            <div class="card-body">
                                <form name="fRegistro" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" novalidate>
                                    <div class="mb-3 form-group">
                                        <label for="username" class="form-label">Introduzca un nombre de usuario:
                                            *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-square" width="30" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M9 10a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                                    <path d="M6 21v-1a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v1" />
                                                    <path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                                                </svg>
                                            </span>
                                            <input type="text" class="form-control" id="username" name="username" required>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">
                                                El nombre de usuario no es válido porque está vacío o se encuentra
                                                registrado.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 form-group">
                                        <label for="password" class="form-label">Introduzca su dirección de correo
                                            electrónico: *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="30" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                                                    <path d="M3 7l9 6l9 -6" />
                                                </svg>
                                            </span>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@hotmail.com" required>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">
                                                El e-mail no cumple las condiciones o ya se encuentra registrado.
                                                Asegúrese de que es correcto.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 form-group">
                                        <label for="password" class="form-label">Introduzca una contraseña segura:
                                            *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-password" width="30" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                                            <input type="password" class="form-control" id="password" name="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,15}[^'\s]" minlength='8' required>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">
                                                La contraseña debe de contener las siguientes especificaciones para ser
                                                segura:
                                                <ul>
                                                    <li>Longitud entre 8 - 15 caracteres.</li>
                                                    <li>Al menos una letra minúscula y una mayúscula.</li>
                                                    <li>Al menos un dígito.</li>
                                                    <li>Deberá usar alguno de estos caracteres especiales: "$ @ $ ! % *
                                                        ? &"</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 form-group">
                                        <label for="password" class="form-label">Repita la contraseña: *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-password" width="30" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                                            <input type="password" class="form-control" id="password2" name="password2" required>
                                            <div class="valid-feedback"></div>
                                            <div class="invalid-feedback">
                                                Las contraseñas no coinciden.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <p class="fs-6 fst-italic">Los campos con asteriscos son obligatorios.</p>
                                    </div>
                                    <!-- Botón de registro -->
                                    <button type="submit" name='Enviar' class="btn btn_purple text-white fw-bold">Registrarse</button>
                                </form>
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