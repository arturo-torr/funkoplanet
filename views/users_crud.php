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
    $daoUsuarios = new DaoUsuarios("funkoplanet");
    ?>
    <div class="container-fluid mt-2">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 mx-auto table-responsive">
                <form name="fUsuarios" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                    <fieldset>
                        <legend class='purple'>Administración de Usuarios</legend>

                        <input type='submit' class='btn btn_purple text-white fw-bold' name='Insertar' value='Insertar'>
                        <input type='submit' class='btn btn_purple text-white fw-bold' name='Buscar' value='Buscar'>
                        <input type='submit' class='btn btn_purple text-white fw-bold' name='Actualizar' value='Actualizar'>
                        <input type='submit' class='btn btn_purple text-white fw-bold' name='Borrar' value='Borrar'>


                        <?php
                        // Recupera la foto anterior que tenía la categoría
                        function FotoAnterior($id)
                        {
                            global $daoUsuarios;
                            $user = $daoUsuarios->obtener($id);
                            return $user->__get("foto");
                        }

                        // Si se ha seleccionado algún elemento y se ha pulsado en actualizar
                        if (isset($_POST['Actualizar']) && (isset($_POST['Selec']))) {
                            $selec = $_POST['Selec'];
                            $usernames = $_POST['Usernames'];
                            $emails = $_POST['Emails'];
                            $passwords = $_POST['Passwords'];
                            $tipos = $_POST['Tipos'];
                            $monederos = $_POST['Monederos'];

                            // Se recorre con un ForEach para cada uno de los mascotas seleccionados
                            foreach ($selec as $clave => $valor) {
                                // Por defecto el campo foto es la foto anterior que tenía la mascota
                                $foto = FotoAnterior($clave);

                                // Si obtenemos una foto, la recuperamos y la guardamos en la variable $foto
                                if ($_FILES['Fotos']['name'][$clave] != "") {
                                    $temp = $_FILES['Fotos']['tmp_name'][$clave];
                                    $contenido = file_get_contents($temp);
                                    $contenido = base64_encode($contenido);
                                    $foto = $contenido;
                                }

                                $user = new Usuario();

                                // Asignamos las propiedades correspondientes al nuevo objeto
                                $user->__set("id", $clave);
                                $user->__set("username", $usernames[$clave]);
                                $user->__set("email", $emails[$clave]);
                                $user->__set("password", password_hash($passwords[$clave], PASSWORD_BCRYPT));
                                $user->__set("tipo", $tipos[$clave]);
                                $user->__set("monedero", $monederos[$clave]);
                                $user->__set("foto", $foto);

                                $daoUsuarios->actualizar($user);
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

                        // Si se ha seleccionado Insertar
                        if (isset($_POST['Insertar'])) {
                            $username = $_POST['usernameNuevo'];
                            $email = $_POST['emailNuevo'];
                            $tipo = $_POST['tipoNuevo'];
                            $monedero = $_POST['monederoNuevo'];
                            $password = $_POST['passwordNueva'];
                            // Por defecto el campo foto es vacío
                            $foto = "";

                            // Si obtenemos una foto, la recuperamos y la guardamos en la variable $foto
                            if ($_FILES['fotoNueva']['name'] != "") {
                                $temp = $_FILES['fotoNueva']['tmp_name'];
                                $contenido = file_get_contents($temp);
                                $contenido = base64_encode($contenido);
                                $foto = $contenido;
                            }

                            $user = new Usuario();

                            // Asignamos las propiedades correspondientes al nuevo objeto;
                            $user->__set("username", $username);
                            $user->__set("email", $email);
                            $user->__set("password", password_hash($password, PASSWORD_BCRYPT));
                            $user->__set("tipo", $tipo);
                            $user->__set("monedero", $monedero);
                            $user->__set("foto", $foto);

                            $daoUsuarios->insertar($user);
                        }

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

                        // El Dao Lista todas las categorías
                        $daoUsuarios->listarConLimite($inicio, $numReg);

                        // Si es >= a 0, la lista
                        if (count($daoUsuarios->usuarios) >= 0) {
                            echo "<table class='mt-2 table table-hover table-bordered border_purple text-center bg_purple'>";
                            echo "<th class='text-white fw-bold'>Selección</th>
                            <th class='text-white fw-bold'>Username</th>
                            <th class='text-white fw-bold'>Email</th>
                            <th class='text-white fw-bold'>Contraseña</th>
                            <th class='text-white fw-bold'>Tipo</th>
                            <th class='text-white fw-bold'>Monedero</th>
                            <th class='text-white fw-bold'>Foto</th>";

                            // Se crea la fila de inserción
                            echo "<tr class='align-middle text-center bg-light'>";
                            echo "<td>*</td>
                            <td><input type='text' class='form-control' name='usernameNuevo'></td>";
                            echo "<td><input type='email' class='form-control' name='emailNuevo'></td>
                            <td><input type='password' class='form-control' name='passwordNueva'></td>
                            <td><input type='text' class='form-control' name='tipoNuevo' maxlength=1 pattern='^[APE]$' title='Solo se permiten los caracteres A, P o E'></td>
                            <td><input type='text' class='form-control' name='monederoNuevo'></td>
                            <td><img class='img-fluid' width=100></img><input type='file' class='form-control-sm' name='fotoNueva'></td>";
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
                                            <input type='text' class='form-control' maxlength=1 pattern='^[APE]$' title='Solo se permiten los caracteres A, P o E' name='Tipos[" . $user->__get("id") . "]' value='" . $user->__get("tipo") . "'>
                                        </td>";
                                echo "<td>
                                            <input type='text' class='form-control' name='Monederos[" . $user->__get("id") . "]' value='" . $user->__get("monedero") . "'>
                                        </td>";
                                $conte = $user->__get("foto");
                                echo "<td>
                                            <img src='data:image/jpg;base64,$conte'  width=100 height=100 alt='User:" . $user->__get("id") . "'>";
                                echo "<input type='file' class='form-control-sm' name='Fotos[" . $user->__get("id") . "]'";
                                echo "</td>";

                                echo "</tr>";
                            }
                            echo "</table>";

                            echo "<label class='form-label' for='numReg'>Num Registros: </label>";

                            // permite recargar la página sin necesidad de tener un submit, usando javascript
                            echo "<select name='numReg' onChange='document.fUsuarios.submit()'>";
                            for ($i = 1; $i < 11; $i++) {
                                echo "<option value='$i' ";
                                if ($i == $numReg) {
                                    echo " selected";
                                }
                                echo ">$i</option>";
                            }

                            echo "</select>";

                            echo "<br>";

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
    require_once "../views/footer.php";
    require_once "../views/scripts.php";
    ?>
</body>

</html>
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
        <th class='text-white fw-bold'>Monedero</th>
        <th class='text-white fw-bold'>Foto</th>";

    foreach ($daoUsuarios->usuarios as $user) {
        echo "<tr class='align-middle bg-light'>";
        echo "<td>" . $user->__get("username") . "</td>";
        echo "<td>" . $user->__get("email") . "</td>";
        echo "<td>" . $user->__get("tipo") . "</td>";
        echo "<td>" . $user->__get("monedero") . "</td>";
        $conte = $user->__get("foto");
        echo "<td>
                   <img src='data:image/jpg;base64,$conte' width=70 height=70 alt='Cat:" . $user->__get("id") . "'>";
        echo "</tr>";
    }


    echo "</table>";
    echo "</fieldset>";
    echo "</div></div></div>";
}
?>