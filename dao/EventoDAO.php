<?php

require_once '../includes/libreriaPDO.php';
require_once '../models/Evento.php';

class DaoEventos extends DB
{
    public $eventos = array();

    // Al instanciar el DAO, especicamos sobre que BBDD trabajaremos
    public function __construct($base)
    {
        $this->dbname = $base;
    }

    // Método que permite listar el contenido de la tabla
    public function listar()
    {
        $consulta = "SELECT * FROM evento";
        $param = array();

        $this->eventos = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $event = new Evento();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $event->__set("id", $fila['id']);
            $event->__set("eventname", $fila['eventname']);
            $event->__set("email", $fila['email']);
            $event->__set("password", $fila['password']);
            $event->__set("tipo", $fila['tipo']);
            $event->__set("monedero", $fila['monedero']);
            $event->__set("foto", $fila['foto']);

            $this->eventos[] = $event;
        }
    }

    // Método que permite listar el contenido de la tabla
    public function listarAdmins()
    {
        $consulta = "SELECT * FROM eventos WHERE tipo = :tipo";
        $param = array();
        $param[":tipo"] = "A";
        $this->eventos = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $event = new Evento();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $event->__set("id", $fila['id']);
            $event->__set("nombre", $fila['nombre']);
            $event->__set("id_usuario", $fila['id_usuario']);
            $event->__set("descripcion", $fila['descripcion']);
            $event->__set("fecha", $fila['fecha']);

            $this->eventos[] = $event;
        }
    }

    // Función que permite obtener un elemento a partir de un ID
    public function obtener($id)
    {
        // Consulta para evitar inyectado de SQL
        $consulta = "SELECT * FROM evento WHERE id=:id";
        $param = array(":id" => $id);

        // Se realiza para vaciar el array de las mascotas entre consulta y consulta
        $this->eventos = array();

        $this->ConsultaDatos($consulta, $param);

        // Cómo solo puede devolver una fila, hacemos la comprobación
        if (count($this->filas) == 1) {
            // Asignamos en una variable el array de filas en la posición 0, que es la única que hay
            $fila = $this->filas[0];

            // Creamos una nueva situación
            $event = new Evento();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $event->__set("id", $fila['id']);
            $event->__set("nombre", $fila['nombre']);
            $event->__set("id_usuario", $fila['id_usuario']);
            $event->__set("descripcion", $fila['descripcion']);
            $event->__set("fecha", $fila['fecha']);
        } else {
            echo "<b>El Id introducido no corresponde con una Evento.</b>";
        }

        // Devolvemos el objeto
        return $event;
    }

    // Método que permite eliminar una situación dado un ID
    public function borrar($id)
    {
        // Consulta para evitar inyectado de SQL
        $consulta = "DELETE FROM evento WHERE id = :id";
        $param = array(":id" => $id);

        // Se realiza para vaciar el array de las mascotas entre consulta y consulta
        $this->eventos = array();

        $this->ConsultaSimple($consulta, $param);
    }

    // Método para insertar una situación que obtiene por parámetro
    public function insertar($event)
    {
        // Consulta para evitar inyección de SQL
        $consulta = "INSERT INTO evento VALUES (NULL, :id_usuario, :nombre, :descripcion, :fecha)";
        $param = array();

        // Asignamos los valores del objeto que hemos recibido por parámetro
        $param[":id_usuario"] = $event->__get("id_usuario");
        $param[":nombre"] = $event->__get("nombre");
        $param[":descripcion"] = $event->__get("descripcion");
        $param[":fecha"] = $event->__get("fecha");

        // Ejecutamos la consulta
        $this->ConsultaSimple($consulta, $param);
    }

    // Recibe por parámetro un objeto con los datos a actualizar
    public function actualizar($event)
    {
        // Cuando se realizan actualizaciones, se actualizan todos los campos, pero las claves primarias no se tocan
        $consulta = "UPDATE evento SET nombre = :nombre, id_usuario = :id_usuario, descripcion = :descripcion, fecha = :fecha WHERE id = :id";
        $param = array();

        // Asignamos los valores del objeto que hemos recibido por parámetro
        $param[":id"] = $event->__get("id");
        $param[":id_usuario"] = $event->__get("id_usuario");
        $param[":nombre"] = $event->__get("nombre");
        $param[":descripcion"] = $event->__get("descripcion");
        $param[":fecha"] = $event->__get("fecha");

        // Ejecutamos la consulta
        $this->ConsultaSimple($consulta, $param);
    }

    public function buscar($nombre = "", $id_usuario = "", $fecha = "")
    {
        $consulta = "SELECT * FROM evento WHERE 1 ";
        $param = array();

        $this->eventos = array();

        if ($nombre != "") {
            $consulta .= " AND nombre LIKE :nombre";
            $param[":nombre"] = "%" . $nombre . "%";
        }

        if ($id_usuario != "") {
            $consulta .= " AND id_usuario LIKE :id_usuario";
            $param[":id_usuario"] = "%" . $id_usuario . "%";
        }

        if ($fecha != "") {
            $consulta .= " AND fecha LIKE :fecha";
            $param[":fecha"] = "%" . $fecha . "%";
        }
        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $event = new Evento();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $event->__set("id", $fila['id']);
            $event->__set("nombre", $fila['nombre']);
            $event->__set("id_usuario", $fila['id_usuario']);
            $event->__set("descripcion", $fila['descripcion']);
            $event->__set("fecha", $fila['fecha']);

            // Se inserta el objeto que acabamos de crear en el Array de objetos tiendas
            $this->eventos[] = $event;
        }
    }

    public function hallarPaginas($numRegistros)
    {
        $consulta = "SELECT COUNT(*) as total FROM evento";
        $this->ConsultaDatos($consulta);
        $fila = $this->filas[0]; // extraemos la fila

        $total = $fila['total']; //extraemos el alias

        $numPaginas = ceil($total / $numRegistros); // se redondea hacia arriba por si hay un resto, que no se queden datos sin mostrar

        return $numPaginas;
    }

    public function listarConLimite($inicio, $numRegistros)
    {
        $consulta = "SELECT * FROM evento LIMIT $inicio,$numRegistros";
        $param = array();

        // Se realiza para vaciar el array de las eventgorías entre consulta y consulta
        $this->eventos = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $event = new Evento();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $event->__set("id", $fila['id']);
            $event->__set("nombre", $fila['nombre']);
            $event->__set("id_usuario", $fila['id_usuario']);
            $event->__set("descripcion", $fila['descripcion']);
            $event->__set("fecha", $fila['fecha']);

            // Se inserta el objeto que acabamos de crear en el Array de objetos eventgorías
            $this->eventos[] = $event;
        }
    }
}
