<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "../views/head.php"; ?>
</head>

<body>

    <?php
    $db = "funkoplanet";
    require_once "../views/header.php";
    require_once "../dao/ProductoDAO.php";
    require_once "../dao/CategoriaDAO.php";
    require_once "../dao/UsuarioDAO.php";
    require_once "../dao/FotoProDAO.php";
    require_once '../models/Producto.php';
    $daoProductos = new DaoProductos($db);
    $daoCategorias = new DaoCategorias($db);
    $daoUsuarios = new DaoUsuarios($db);
    $daoFotosProductos = new DaoFotosProductos($db);
    ?>
    <div class="container-fluid mt-2">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 mx-auto table-responsive">
                <form name="fProductos" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>"
                    enctype="multipart/form-data">
                    <fieldset>
                        <legend class='purple'>Administración de Productos</legend>

                        <input type='submit' class='btn btn_purple text-white fw-bold' name='Insertar' value='Insertar'>
                        <input type='submit' class='btn btn_purple text-white fw-bold' name='Buscar' value='Buscar'>
                        <input type='submit' class='btn btn_purple text-white fw-bold' name='Actualizar'
                            value='Actualizar'>
                        <input type='submit' class='btn btn_purple text-white fw-bold' name='Borrar' value='Borrar'>


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

                        $numPaginas = $daoProductos->hallarPaginas($numReg);

                        $inicio = ($pagina - 1) * $numReg; // algoritmo para mostrar los registros necesarios
                        echo "<br><label class='form-label my-2' for='numReg'>Número de registros que desea visualizar:: </label>";

                        // permite recargar la página sin necesidad de tener un submit, usando javascript
                        echo "<select name='numReg' class='form-select w-25' onChange='document.fProductos.submit()'>";
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
                            global $daoProductos;
                            $prod = $daoProductos->obtener($id);
                            return $prod->__get("foto");
                        }

                        // Si se ha seleccionado algún elemento y se ha pulsado en actualizar
                        if (isset($_POST['Actualizar']) && (isset($_POST['Selec']))) {
                            $selec = $_POST['Selec'];
                            $nombres = $_POST['Nombres'];
                            $categorias = $_POST['Categorias'];
                            $usuarios = $_POST['Usuarios'];
                            $descripciones = $_POST['Descripciones'];
                            $precios = $_POST['Precios'];
                            $estados = $_POST['Estados'];

                            // Se recorre con un ForEach para cada uno de los mascotas seleccionados
                            foreach ($selec as $clave => $valor) {

                                $prod = new Producto();

                                // Asignamos las propiedades correspondientes al nuevo objeto
                                $prod->__set("id", $clave);
                                $prod->__set("nombre", $nombres[$clave]);
                                $prod->__set("id_categoria", $categorias[$clave]);
                                $prod->__set("id_usuario", $usuarios[$clave]);
                                $prod->__set("descripcion", $descripciones[$clave]);
                                $prod->__set("precio", $precios[$clave]);
                                $prod->__set("estado", $estados[$clave]);

                                $daoProductos->actualizar($prod);
                            }
                        }

                        // Si se ha seleccionado algún elemento y se ha pulsado en Borrar
                        if (isset($_POST['Borrar']) && (isset($_POST['Selec']))) {
                            $selec = $_POST['Selec'];
                            // Recorre los usuarios y las va borrando por Id
                            foreach ($selec as $clave => $valor) {
                                $daoProductos->borrar($clave);
                            }
                        }

                        function generarId($nombre, $categoria)
                        {
                            global $daoCategorias;
                            $categoria = $daoCategorias->obtener($categoria);
                            $cat = strtoupper($categoria->__get("nombre"));
                            $nombre = strtoupper($nombre);

                            // Tomar las primeras 4 letras del producto y de la categoría
                            $nombre = substr($nombre, 0, 3);
                            $cat = substr($cat, 0, 3);

                            // Generar 5 dígitos aleatorios
                            $numeros_aleatorios = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

                            // Concatenar las partes para formar el ID
                            $id = $numeros_aleatorios . "-" . $nombre . $cat;

                            return $id;
                        }
                        // Si se ha seleccionado Insertar
                        if (isset($_POST['Insertar'])) {
                            $nombre = $_POST['nombreNuevo'];
                            $categoria = $_POST['categoriaNueva'];
                            $usuario = $_POST['usuarioNuevo'];
                            $descripcion = $_POST['descripcionNueva'];
                            $precio = $_POST['precioNuevo'];
                            $estado = $_POST['estadoNuevo'];
                            $id = generarId($nombre, $categoria);

                            $prod = new Producto();

                            // Asignamos las propiedades correspondientes al nuevo objeto;
                            $prod->__set("id", $id);
                            $prod->__set("nombre", $nombre);
                            $prod->__set("id_categoria", $categoria);
                            $prod->__set("id_usuario", $usuario);
                            $prod->__set("descripcion", $descripcion);
                            $prod->__set("precio", $precio);
                            $prod->__set("estado", $estado);

                            $daoProductos->insertar($prod);
                        }

                        // El Dao Lista todas las categorías
                        $daoProductos->listarConLimite($inicio, $numReg);

                        // Si es >= a 0, la lista
                        if (count($daoProductos->productos) >= 0) {
                            echo "<table class='mt-2 table table-hover table-bordered border_purple text-center bg_purple'>";
                            echo "<th class='text-white fw-bold'>Selección</th>
                            <th class='text-white fw-bold'>Nombre</th>
                            <th class='text-white fw-bold'>Categoría</th>
                            <th class='text-white fw-bold'>Descripción</th>
                            <th class='text-white fw-bold'>Precio</th>
                            <th class='text-white fw-bold'>Estado</th>
                            <th class='text-white fw-bold'>Usuario</th>";

                            // Se crea la fila de inserción
                            echo "<tr class='align-middle text-center bg-light'>";
                            echo "<td>*</td>
                            <td><input type='text' class='form-control' name='nombreNuevo'></td>";
                            echo "<td> <select name='categoriaNueva' class='form-select'> ";
                            echo "<option value=''></option>";
                            $daoCategorias->listar();
                            foreach ($daoCategorias->categoriasObjetos as $key => $cat) {
                                echo "<option value=" . $cat->__get("id");
                                echo ">" . $cat->__get("nombre") . "</option>";
                            }
                            echo "</select></td>";
                            echo "
                            <td><input type='text' class='form-control' name='descripcionNueva'></td>
                            <td><input type='text' class='form-control' name='precioNuevo'></td>
                            <td><input type='text' class='form-control' name='estadoNuevo'></td>";

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
                            foreach ($daoProductos->productos as $key => $prod) {

                                echo "<tr class='align-middle text-center bg-light'>";

                                echo "<td><input type='checkbox' id='" . $prod->__get("id") . "' class='btn-check' name='Selec[" . $prod->__get("id") . "]' autocomplete='off'>
                                    <label class='btn btn-outline-danger' for='" . $prod->__get("id") . "'>Seleccionar</label></td>";

                                $nombre = $prod->__get("nombre");
                                echo "<td>
                                            <input type='text' class='form-control' name='Nombres[" . $prod->__get("id") . "]' value='$nombre'>
                                        </td>";
                                echo "<td> <select class='form-select' name='Categorias[" . $prod->__get("id") . "]'>";
                                $daoCategorias->listar();
                                foreach ($daoCategorias->categoriasObjetos as $key => $cat) {
                                    echo "<option value=" . $cat->__get("id");

                                    if ($prod->__get("id_categoria") == $cat->__get("id")) {
                                        echo " selected";
                                    }
                                    echo ">" . $cat->__get("nombre") . "</option>";
                                }
                                echo "</select></td>";
                                echo "<td>
                                            <input type='text' class='form-control' name='Descripciones[" . $prod->__get("id") . "]' value='" . $prod->__get("descripcion") . "'>
                                        </td>";
                                echo "<td>
                                            <input type='text' class='form-control' name='Precios[" . $prod->__get("id") . "]' value='" . $prod->__get("precio") . "'>
                                        </td>";
                                echo "<td>
                                            <input type='text' class='form-control' name='Estados[" . $prod->__get("id") . "]' value='" . $prod->__get("estado") . "'>
                                        </td>";
                                echo "<td> <select class='form-select' name='Usuarios[" . $prod->__get("id") . "]'>";
                                $daoUsuarios->listarAdmins();
                                foreach ($daoUsuarios->usuarios as $key => $user) {
                                    echo "<option value=" . $user->__get("id");

                                    if ($prod->__get("id_usuario") == $user->__get("id")) {
                                        echo " selected";
                                    }
                                    echo ">" . $user->__get("username") . "</option>";
                                }
                                echo "</select></td>";

                                echo "</tr>";
                            }
                            echo "</table>";



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
                    <hr class='purple_line my-2'>
                    <?php
                    // Si se da clcik en buscar
                    if (isset($_POST['Buscar'])) {
                        $nombre = $_POST['nombreNuevo'];
                        $categoria = $_POST['categoriaNueva'];
                        $usuario = $_POST['usuarioNuevo'];
                        $precio = $_POST['precioNuevo'];
                        $estado = $_POST['estadoNuevo'];

                        $daoProductos->buscar($nombre, $categoria, $precio, $estado, $usuario);
                        echo "<div class='container-fluid'><div class='row'>";
                        echo "<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 mx-auto table-responsive'>";
                        echo "<fieldset><legend class='purple'>Resultados de la búsqueda</legend>";
                        echo "<table class='mt-2 table bg_purple table-bordered border_purple text-center'>";
                        echo "<th class='text-white fw-bold'>Nombre</th>
                                <th class='text-white fw-bold'>Categoría</th>
                                <th class='text-white fw-bold'>Precio</th>
                                <th class='text-white fw-bold'>Estado</th>
                                <th class='text-white fw-bold'>Usuario</th>";

                        foreach ($daoProductos->productos as $prod) {
                            echo "<tr class='align-middle bg-light'>";
                            echo "<td>" . $prod->__get("nombre") . "</td>";
                            $categoria = $daoCategorias->obtener($prod->__get("id_categoria"));
                            echo "<td>" . $categoria->__get("nombre") . "</td>";
                            echo "<td>" . $prod->__get("precio") . "</td>";
                            echo "<td>" . $prod->__get("estado") . "</td>";
                            $usuario = $daoUsuarios->obtener($prod->__get("id_usuario"));
                            echo "<td>" . $usuario->__get("username") . "</td>";
                        }


                        echo "</table>";
                        echo "</fieldset>";
                        echo "</div></div></div><hr class='purple_line my-2'>";
                    }
                    ?>


                    <fieldset>
                        <legend class="purple">Administración de imágenes de Productos</legend>

                        <label class="form-label" for="productos">Seleccione el producto que desee:</label>
                        <select name="productos" class='form-select w-25'>
                            <option value=""></option>
                            <?php
                            $id = "";
                            if (isset($_POST['productos'])) {
                                $id = $_POST['productos'];
                            }
                            foreach ($daoProductos->productos as $key => $prod) {
                                echo "<option value=" . $prod->__get("id");

                                if ($id == $prod->__get("id")) {
                                    echo " selected";
                                }

                                echo ">" . $prod->__get("nombre") . "</option>";
                            }
                            ?>
                        </select>

                        <br>
                        <input type='submit' class='btn btn_purple text-white fw-bold' name='Mostrar' value='Mostrar'>

                    </fieldset>
                    <?php
                    if (isset($_POST['Guardar'])) {
                        if (!empty($_FILES['NuevaF']['name'][0])) {
                            foreach ($_FILES['NuevaF']['tmp_name'] as $key => $tmp_name) {
                                $contenido = file_get_contents($tmp_name);
                                $contenido = base64_encode($contenido);

                                // Función que devuelve el ID de la última foto para ese producto
                                $idFoto = $daoFotosProductos->obtenerSiguienteId($id);

                                $fotoPro = new FotoPro();
                                $fotoPro->__set("id_foto", $idFoto);
                                $fotoPro->__set("id_producto", $id);
                                $fotoPro->__set("foto", $contenido);

                                $daoFotosProductos->insertar($fotoPro);
                            }
                        }
                    }
                    $ids = array();
                    if (isset($_POST['Eliminar'])) {
                        if (isset($_POST['ids'])) {
                            $ids = $_POST['ids'];
                            foreach ($ids as $idFoto => $value) {
                                $daoFotosProductos->eliminar($idFoto, $id);
                            }
                        }
                    }
                    if (isset($_POST['Mostrar'])) {
                        echo "<hr class='purple_line my-2'><fieldset>";
                        echo "<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mx-auto table-responsive'>";
                        echo "<table class='mt-2 table bg_purple table-bordered border_purple text-center'>";
                        echo "<fieldset><legend class='purple'>Fotos del producto seleccionado</legend>";
                        echo "<th class='text-white'>Seleccionar</th><th class='text-white'>Foto</th>";

                        $numFotos = $daoFotosProductos->obtenerNumeroFotos($id);
                        $daoFotosProductos->listarPorId($id);


                        foreach ($daoFotosProductos->fotosPro as $key => $fotoPro) {
                            echo "<tr class='bg-white align-middle'>";
                            echo "<td><input type='checkbox' id='" . $fotoPro->__get("id_foto") . "' class='btn-check' name=ids[" . $fotoPro->__get("id_foto") . "]>";
                            echo "<label class='btn btn-outline-danger' for='" . $fotoPro->__get("id_foto") . "'>Seleccionar</label></td>";
                            $conte = $fotoPro->__get("foto");
                            echo "<td><img src='data:image/jpg;base64,$conte' width=70 height=70></td>";
                            echo "</tr>";
                        }

                        echo "</fieldset>";
                        echo "</table>";
                        echo "</div>";
                        echo "<input type='file' class='form-control-sm mx-1' name='NuevaF[]' multiple='multiple'>";
                        echo "<input type='submit' class='btn btn-danger text-white fw-bold mx-1' name='Eliminar' value='Eliminar'>";
                        echo "<input type='submit' class='btn btn_purple text-white fw-bold mx-1' name='Guardar' value='Añadir Imágenes'>";
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>



    <?php
    require_once "../views/scripts.php";
    ?>
</body>

</html>