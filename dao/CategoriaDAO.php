<?php

require_once '../includes/libreriaPDO.php';
require_once '../models/Categoria.php';

class DaoCategorias extends DB
{
    public $categorias = array();
    public $categoriasObjetos = array();

    // Al instanciar el DAO, especicamos sobre que BBDD trabajaremos
    public function __construct($base)
    {
        $this->dbname = $base;
    }

    // Método que permite listar el contenido de la tabla
    public function listar()
    {
        $consulta = "SELECT * FROM categoria";
        $param = array();

        // Se realiza para vaciar el array de las tiendas entre consulta y consulta
        $this->categorias = array();
        $this->categoriasObjetos = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $cate = new Categoria();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $cate->__set("id", $fila['id']);
            $cate->__set("nombre", $fila['nombre']);
            $cate->__set("descripcion", $fila['descripcion']);
            $cate->__set("foto", $fila['foto']);
            // Se inserta el objeto que acabamos de crear en el Array de objetos tiendas
            $this->categorias[] = $cate->toArray();
            $this->categoriasObjetos[] = $cate;
        }
    }

    // Función que permite obtener un elemento a partir de un id
    public function obtener($id)
    {
        // Consulta para evitar inyectado de SQL
        $consulta = "SELECT * FROM categoria WHERE id=:id";
        $param = array(":id" => $id);

        // Se realiza para vaciar el array de las mascotas entre consulta y consulta
        $this->categorias = array();

        $this->ConsultaDatos($consulta, $param);

        // Cómo solo puede devolver una fila, hacemos la comprobación
        if (count($this->filas) == 1) {
            // Asignamos en una variable el array de filas en la posición 0, que es la única que hay
            $fila = $this->filas[0];

            // Creamos una nueva situación
            $cate = new Categoria();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $cate->__set("id", $fila['id']);
            $cate->__set("nombre", $fila['nombre']);
            $cate->__set("descripcion", $fila['descripcion']);
            $cate->__set("foto", $fila['foto']);
        } else {
            echo "<b>El Id introducido no corresponde con una categoria.</b>";
        }

        // Devolvemos el objeto
        return $cate;
    }

    // Función que permite obtener un elemento a partir de un id
    public function obtenerUltimoId()
    {
        // Consulta para evitar inyectado de SQL
        $consulta = "SELECT * FROM categoria ORDER BY id DESC LIMIT 1";

        // Se realiza para vaciar el array de las mascotas entre consulta y consulta
        $this->categorias = array();

        $this->ConsultaDatos($consulta);

        // Cómo solo puede devolver una fila, hacemos la comprobación
        if (count($this->filas) == 1) {
            // Asignamos en una variable el array de filas en la posición 0, que es la única que hay
            $fila = $this->filas[0];

            // Creamos una nueva situación
            $cate = new Categoria();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $cate->__set("id", $fila['id']);
            $cate->__set("nombre", $fila['nombre']);
            $cate->__set("descripcion", $fila['descripcion']);
            $cate->__set("foto", $fila['foto']);
        } else {
            echo "<b>El Id introducido no corresponde con una categoria.</b>";
        }

        // Devolvemos el objeto
        return $cate;
    }

    // Método que permite eliminar una situación dado un ID
    public function borrar($id)
    {
        // Consulta para evitar inyectado de SQL
        $consulta = "DELETE FROM categoria WHERE id = :id";
        $param = array(":id" => $id);

        // Se realiza para vaciar el array de las mascotas entre consulta y consulta
        $this->categorias = array();

        $this->ConsultaSimple($consulta, $param);
    }

    // Método para insertar una situación que obtiene por parámetro
    public function insertar($cate)
    {
        // Consulta para evitar inyección de SQL
        $consulta = "INSERT INTO categoria VALUES (NULL, :nombre, :descripcion, :foto)";
        $param = array();

        // Asignamos los valores del objeto que hemos recibido por parámetro
        $param[":nombre"] = $cate->__get("nombre");
        $param[":descripcion"] = $cate->__get("descripcion");
        $param[":foto"] = $cate->__get("foto");

        // Ejecutamos la consulta
        $this->ConsultaSimple($consulta, $param);
    }

    // Recibe por parámetro un objeto con los datos a actualizar
    public function actualizar($cate)
    {
        // Cuando se realizan actualizaciones, se actualizan todos los campos, pero las claves primarias no se tocan
        $consulta = "UPDATE categoria SET nombre = :nombre, descripcion = :descripcion, foto = :foto WHERE id = :id";
        $param = array();

        // Asignamos los valores del objeto que hemos recibido por parámetro
        $param[":id"] = $cate->__get("id");
        $param[":nombre"] = $cate->__get("nombre");
        $param[":descripcion"] = $cate->__get("descripcion");
        $param[":foto"] = $cate->__get("foto");

        // Ejecutamos la consulta
        $this->ConsultaSimple($consulta, $param);
    }

    public function buscar($nombre = "")
    {
        $consulta = "SELECT c.id, c.nombre as nombre, c.descripcion, c.foto FROM categoria c WHERE 1 ";
        $param = array();

        $this->categoriasObjetos = array();

        if ($nombre != "") {
            $consulta .= " AND c.nombre LIKE :nom";
            $param[":nom"] = "%" . $nombre . "%";
        }
        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $cate = new Categoria();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $cate->__set("id", $fila['id']);
            $cate->__set("nombre", $fila['nombre']);
            $cate->__set("descripcion", $fila['descripcion']);
            $cate->__set("foto", $fila['foto']);

            // Se inserta el objeto que acabamos de crear en el Array de objetos tiendas
            $this->categoriasObjetos[] = $cate;
        }
    }

    public function hallarPaginas($numRegistros)
    {
        $consulta = "SELECT COUNT(*) as total FROM categoria";
        $this->ConsultaDatos($consulta);
        $fila = $this->filas[0]; // extraemos la fila

        $total = $fila['total']; //extraemos el alias

        $numPaginas = ceil($total / $numRegistros); // se redondea hacia arriba por si hay un resto, que no se queden datos sin mostrar

        return $numPaginas;
    }

    public function listarConLimite($inicio, $numRegistros)
    {
        $consulta = "SELECT * FROM categoria LIMIT $inicio,$numRegistros";
        $param = array();

        // Se realiza para vaciar el array de las categorías entre consulta y consulta
        $this->categorias = array();
        $this->categoriasObjetos = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva categoría
            $cate = new Categoria();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $cate->__set("id", $fila['id']);
            $cate->__set("nombre", $fila['nombre']);
            $cate->__set("descripcion", $fila['descripcion']);
            $cate->__set("foto", $fila['foto']);

            // Se inserta el objeto que acabamos de crear en el Array de objetos categorías
            $this->categorias[] = $cate->toArray();
            $this->categoriasObjetos[] = $cate;
        }
    }
}
