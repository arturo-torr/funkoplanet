<?php

// Comprobaciones de rutas según donde es llamado el fichero
$ruta1_libPDO = 'includes/libreriaPDO.php';
$ruta1_producto = 'models/Producto.php';
$ruta2_libPDO = '../includes/libreriaPDO.php';
$ruta2_producto = '../models/Producto.php';

if (file_exists($ruta1_libPDO)) {
    require_once $ruta1_libPDO;
}

if (file_exists($ruta1_producto)) {
    require_once $ruta1_producto;
}

if (file_exists($ruta2_libPDO)) {
    require_once $ruta2_libPDO;
}

if (file_exists($ruta2_producto)) {
    require_once $ruta2_producto;
}

class DaoProductos extends DB
{
    public $productos = array();
    public $productosJSON = array();

    // Al instanciar el DAO, especicamos sobre que BBDD trabajaremos
    public function __construct($base)
    {
        $this->dbname = $base;
    }

    // Método que permite listar el contenido de la tabla
    public function listar()
    {
        $consulta = "SELECT * FROM producto";
        $param = array();

        $this->productos = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $prod = new Producto();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $prod->__set("id", $fila['id']);
            $prod->__set("id_categoria", $fila['id_categoria']);
            $prod->__set("id_usuario", $fila['id_usuario']);
            $prod->__set("nombre", $fila['nombre']);
            $prod->__set("descripcion", $fila['descripcion']);
            $prod->__set("precio", $fila['precio']);
            $prod->__set("estado", $fila['estado']);

            $this->productos[] = $prod;
            $this->productosJSON[] = $prod->toArray();
        }
    }

    // Método que permite listar el contenido de la tabla
    public function listarPorCategoria($idCategoria)
    {
        $consulta = "SELECT * FROM producto WHERE id_categoria = :id_categoria";
        $param = array();
        $param[":id_categoria"] = $idCategoria;

        $this->productos = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $prod = new Producto();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $prod->__set("id", $fila['id']);
            $prod->__set("id_categoria", $fila['id_categoria']);
            $prod->__set("id_usuario", $fila['id_usuario']);
            $prod->__set("nombre", $fila['nombre']);
            $prod->__set("descripcion", $fila['descripcion']);
            $prod->__set("precio", $fila['precio']);
            $prod->__set("estado", $fila['estado']);

            $this->productos[] = $prod;
            $this->productosJSON[] = $prod->toArray();
        }
    }

    // Método que permite listar el contenido de la tabla
    public function listarNovedades()
    {
        $consulta = "SELECT * FROM producto ORDER BY fecha_subida DESC LIMIT 8";

        $param = array();

        $this->productos = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $prod = new Producto();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $prod->__set("id", $fila['id']);
            $prod->__set("id_categoria", $fila['id_categoria']);
            $prod->__set("id_usuario", $fila['id_usuario']);
            $prod->__set("nombre", $fila['nombre']);
            $prod->__set("descripcion", $fila['descripcion']);
            $prod->__set("precio", $fila['precio']);
            $prod->__set("estado", $fila['estado']);
            $prod->__set("fecha_subida", $fila['fecha_subida']);

            $this->productos[] = $prod;
            $this->productosJSON[] = $prod->toArray();
        }
    }

    // Función que permite obtener un elemento a partir de un ID
    public function obtener($id)
    {
        // Consulta para evitar inyectado de SQL
        $consulta = "SELECT * FROM producto WHERE id=:id";
        $param = array(":id" => $id);

        // Se realiza para vaciar el array de las mascotas entre consulta y consulta
        $this->productos = array();

        $this->ConsultaDatos($consulta, $param);

        // Cómo solo puede devolver una fila, hacemos la comprobación
        if (count($this->filas) == 1) {
            // Asignamos en una variable el array de filas en la posición 0, que es la única que hay
            $fila = $this->filas[0];

            // Creamos una nueva situación
            $prod = new Producto();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $prod->__set("id", $fila['id']);
            $prod->__set("id_categoria", $fila['id_categoria']);
            $prod->__set("id_usuario", $fila['id_usuario']);
            $prod->__set("nombre", $fila['nombre']);
            $prod->__set("descripcion", $fila['descripcion']);
            $prod->__set("precio", $fila['precio']);
            $prod->__set("estado", $fila['estado']);
            $prod->__set("fecha_subida", $fila['fecha_subida']);
        } else {
            echo "<b>El Id introducido no corresponde con un producto.</b>";
        }

        // Devolvemos el objeto
        return $prod;
    }

    // Método que permite eliminar una situación dado un ID
    public function borrar($id)
    {
        // Consulta para evitar inyectado de SQL
        $consulta = "DELETE FROM producto WHERE id = :id";
        $param = array(":id" => $id);

        // Se realiza para vaciar el array de las mascotas entre consulta y consulta
        $this->productos = array();

        $this->ConsultaSimple($consulta, $param);
    }

    // Método para insertar una situación que obtiene por parámetro
    public function insertar($prod)
    {
        // Consulta para evitar inyección de SQL
        $consulta = "INSERT INTO producto VALUES (:id, :idcategoria, :idusuario, :nombre, :descripcion, :precio, :estado, :fecha_subida)";
        $param = array();

        // Asignamos los valores del objeto que hemos recibido por parámetro
        $param[":id"] = $prod->__get("id");
        $param[":idcategoria"] = $prod->__get("id_categoria");
        $param[":idusuario"] = $prod->__get("id_usuario");
        $param[":nombre"] = $prod->__get("nombre");
        $param[":descripcion"] = $prod->__get("descripcion");
        $param[":precio"] = $prod->__get("precio");
        $param[":estado"] = $prod->__get("estado");
        $param[":fecha_subida"] = $prod->__get("fecha_subida");
        // Ejecutamos la consulta
        $this->ConsultaSimple($consulta, $param);
    }

    // Recibe por parámetro un objeto con los datos a actualizar
    public function actualizar($prod)
    {
        // Cuando se realizan actualizaciones, se actualizan todos los campos, pero las claves primarias no se tocan
        $consulta = "UPDATE producto SET id_categoria = :id_categoria, id_usuario = :id_usuario, nombre = :nombre, descripcion = :descripcion, precio = :precio, estado = :estado WHERE id = :id";
        $param = array();

        // Asignamos los valores del objeto que hemos recibido por parámetro
        $param[":id"] = $prod->__get("id");
        $param[":id_categoria"] = $prod->__get("id_categoria");
        $param[":id_usuario"] = $prod->__get("id_usuario");
        $param[":nombre"] = $prod->__get("nombre");
        $param[":descripcion"] = $prod->__get("descripcion");
        $param[":precio"] = $prod->__get("precio");
        $param[":estado"] = $prod->__get("estado");

        // Ejecutamos la consulta
        $this->ConsultaSimple($consulta, $param);
    }

    // Función para realizar una búsqueda
    public function buscar($nombre = "", $categoria = "", $precio = "", $estado = "", $usuario = "")
    {
        $consulta = "SELECT * FROM producto WHERE 1 ";
        $param = array();

        $this->productos = array();

        if ($nombre != "") {
            $consulta .= " AND nombre LIKE :nombre";
            $param[":nombre"] = "%" . $nombre . "%";
        }

        if ($categoria != "") {
            $consulta .= " AND id_categoria LIKE :categoria";
            $param[":categoria"] = "%" . $categoria . "%";
        }

        if ($precio != "") {
            $consulta .= " AND precio LIKE :precio";
            $param[":precio"] = "%" . $precio . "%";
        }

        if ($usuario != "") {
            $consulta .= " AND id_usuario LIKE :usuario";
            $param[":usuario"] = "%" . $usuario . "%";
        }

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $prod = new Producto();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $prod->__set("id", $fila['id']);
            $prod->__set("id_categoria", $fila['id_categoria']);
            $prod->__set("id_usuario", $fila['id_usuario']);
            $prod->__set("nombre", $fila['nombre']);
            $prod->__set("descripcion", $fila['descripcion']);
            $prod->__set("precio", $fila['precio']);
            $prod->__set("estado", $fila['estado']);

            // Se inserta el objeto que acabamos de crear en el Array de objetos tiendas
            $this->productos[] = $prod;
        }
    }

    public function listarConFiltro($categoria, $busqueda = "", $disponibilidad = "", $orden = "")
    {
        $consulta = "SELECT * FROM producto WHERE 1 ";
        $param = array();

        $this->productos = array();

        if ($busqueda != "") {
            $consulta .= " AND nombre LIKE :busqueda";
            $param[":busqueda"] = "%" . $busqueda . "%";
        }

        if ($categoria != "") {
            $consulta .= " AND id_categoria = :categoria";
            $param[":categoria"] = $categoria;
        }

        if ($disponibilidad != "" && $disponibilidad != "Todos los productos") {
            $consulta .= " AND estado = :disponibilidad";
            $param[":disponibilidad"] = $disponibilidad;
        }

        // Añadir ordenamiento si se proporciona
        switch ($orden) {
            case 'nuevos':
                $consulta .= " ORDER BY fecha_subida DESC";
                break;
            case 'viejos':
                $consulta .= " ORDER BY fecha_subida ASC";
                break;
            case 'baratos':
                $consulta .= " ORDER BY precio ASC";
                break;
            case 'caros':
                $consulta .= " ORDER BY precio DESC";
                break;
            default:
                // No se proporcionó un orden específico, no se añade nada
                break;
        }

        // Realiza la consulta
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos un nuevo producto
            $prod = new Producto();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $prod->__set("id", $fila['id']);
            $prod->__set("id_categoria", $fila['id_categoria']);
            $prod->__set("id_usuario", $fila['id_usuario']);
            $prod->__set("nombre", $fila['nombre']);
            $prod->__set("descripcion", $fila['descripcion']);
            $prod->__set("precio", $fila['precio']);
            $prod->__set("estado", $fila['estado']);

            // Se inserta el objeto en el array de productos
            $this->productos[] = $prod;
            $this->productosJSON[] = $prod->toArray();
        }
    }
    public function hallarPaginas($numRegistros)
    {
        $consulta = "SELECT COUNT(*) as total FROM producto";
        $this->ConsultaDatos($consulta);
        $fila = $this->filas[0]; // extraemos la fila

        $total = $fila['total']; //extraemos el alias

        $numPaginas = ceil($total / $numRegistros); // se redondea hacia arriba por si hay un resto, que no se queden datos sin mostrar

        return $numPaginas;
    }

    public function listarConLimite($inicio, $numRegistros)
    {
        $consulta = "SELECT * FROM producto LIMIT $inicio,$numRegistros";
        $param = array();

        // Se realiza para vaciar el array de las prodgorías entre consulta y consulta
        $this->productos = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {

            // Creamos una nueva situación
            $prod = new Producto();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $prod->__set("id", $fila['id']);
            $prod->__set("id_categoria", $fila['id_categoria']);
            $prod->__set("id_usuario", $fila['id_usuario']);
            $prod->__set("nombre", $fila['nombre']);
            $prod->__set("descripcion", $fila['descripcion']);
            $prod->__set("precio", $fila['precio']);
            $prod->__set("estado", $fila['estado']);
            $prod->__set("fecha_subida", $fila['fecha_subida']);
            // Se inserta el objeto que acabamos de crear en el Array de objetos prodgorías
            $this->productos[] = $prod;
        }
    }
}
