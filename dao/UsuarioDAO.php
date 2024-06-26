<?php

require_once '../includes/libreriaPDO.php';
require_once '../models/Usuario.php';

class DaoUsuarios extends DB
{
    public $usuarios = array();

    // Al instanciar el DAO, especicamos sobre que BBDD trabajaremos
    public function __construct($base)
    {
        $this->dbname = $base;
    }

    // Método que permite listar el contenido de la tabla
    public function listar()
    {
        $consulta = "SELECT * FROM usuarios";
        $param = array();

        $this->usuarios = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $user = new Usuario();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $user->__set("id", $fila['id']);
            $user->__set("username", $fila['username']);
            $user->__set("email", $fila['email']);
            $user->__set("password", $fila['password']);
            $user->__set("tipo", $fila['tipo']);
            $user->__set("monedero", $fila['monedero']);

            $this->usuarios[] = $user;
        }
    }

    // Método que permite listar el contenido de la tabla
    public function listarAdmins()
    {
        $consulta = "SELECT * FROM usuarios WHERE tipo = :tipo";
        $param = array();
        $param[":tipo"] = "A";
        $this->usuarios = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $user = new Usuario();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $user->__set("id", $fila['id']);
            $user->__set("username", $fila['username']);
            $user->__set("email", $fila['email']);
            $user->__set("password", $fila['password']);
            $user->__set("tipo", $fila['tipo']);
            $user->__set("monedero", $fila['monedero']);

            $this->usuarios[] = $user;
        }
    }

    // Función que permite obtener un elemento a partir de un ID
    public function obtener($id)
    {
        // Consulta para evitar inyectado de SQL
        $consulta = "SELECT * FROM usuarios WHERE id=:id";
        $param = array(":id" => $id);

        // Se realiza para vaciar el array de las mascotas entre consulta y consulta
        $this->usuarios = array();

        $this->ConsultaDatos($consulta, $param);

        // Cómo solo puede devolver una fila, hacemos la comprobación
        if (count($this->filas) == 1) {
            // Asignamos en una variable el array de filas en la posición 0, que es la única que hay
            $fila = $this->filas[0];

            // Creamos una nueva situación
            $user = new Usuario();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $user->__set("id", $fila['id']);
            $user->__set("username", $fila['username']);
            $user->__set("email", $fila['email']);
            $user->__set("password", $fila['password']);
            $user->__set("tipo", $fila['tipo']);
            $user->__set("monedero", $fila['monedero']);
        } else {
            echo "<b>El Id introducido no corresponde con una usuario.</b>";
        }

        // Devolvemos el objeto
        return $user;
    }

    // Función que permite obtener un elemento a partir de un nombre de usuario
    // Función que permite obtener un elemento a partir de un nombre de usuario
    public function obtenerPorUsername($username)
    {
        // Consulta para evitar inyección de SQL
        $consulta = "SELECT * FROM usuarios WHERE username = :username";
        $param = array(":username" => $username);

        // Se realiza para vaciar el array de usuarios entre consulta y consulta
        $this->usuarios = array();

        // Ejecutar la consulta con los parámetros
        $this->ConsultaDatos($consulta, $param);

        // Como solo puede devolver una fila, hacemos la comprobación
        if (count($this->filas) == 1) {
            // Asignamos en una variable el array de filas en la posición 0, que es la única que hay
            $fila = $this->filas[0];

            // Creamos un nuevo usuario
            $user = new Usuario();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $user->__set("id", $fila['id']);
            $user->__set("username", $fila['username']);
            $user->__set("email", $fila['email']);
            $user->__set("password", $fila['password']);
            $user->__set("tipo", $fila['tipo']);
            $user->__set("monedero", $fila['monedero']);
        } else {
            // Si no hay filas, devolver null
            return null;
        }

        // Devolver el objeto usuario
        return $user;
    }



    public function obtenerPorEmail($email)
    {
        // Consulta para evitar inyectado de SQL
        $consulta = "SELECT * FROM usuarios WHERE email=:email";
        $param = array(":email" => $email);

        // Se realiza para vaciar el array de las mascotas entre consulta y consulta
        $this->usuarios = array();

        $this->ConsultaDatos($consulta, $param);

        // Cómo solo puede devolver una fila, hacemos la comprobación
        if (count($this->filas) == 1) {
            // Asignamos en una variable el array de filas en la posición 0, que es la única que hay
            $fila = $this->filas[0];

            // Creamos una nueva situación
            $user = new Usuario();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $user->__set("id", $fila['id']);
            $user->__set("username", $fila['username']);
            $user->__set("email", $fila['email']);
            $user->__set("password", $fila['password']);
            $user->__set("tipo", $fila['tipo']);
            $user->__set("monedero", $fila['monedero']);
        } else {
            return null;
        }

        // Devolvemos el objeto
        return $user;
    }

    // Método que permite eliminar una situación dado un ID
    public function borrar($id)
    {
        // Consulta para evitar inyectado de SQL
        $consulta = "DELETE FROM usuarios WHERE id = :id";
        $param = array(":id" => $id);

        // Se realiza para vaciar el array de las mascotas entre consulta y consulta
        $this->usuarios = array();

        $this->ConsultaSimple($consulta, $param);
    }

    // Método para insertar una situación que obtiene por parámetro
    // Se ha introducido bloque try-catch para poder devolver true en registrarusuario.php
    public function insertar($user)
    {
        try {
            // Consulta para evitar inyección de SQL
            $consulta = "INSERT INTO usuarios VALUES (NULL, :username, :email, :password, :tipo, :monedero)";
            $param = array();

            // Asignamos los valores del objeto que hemos recibido por parámetro
            $param[":username"] = $user->__get("username");
            $param[":email"] = $user->__get("email");
            $param[":password"] = $user->__get("password");
            $param[":tipo"] = $user->__get("tipo");
            $param[":monedero"] = $user->__get("monedero");

            // Ejecutamos la consulta
            $this->ConsultaSimple($consulta, $param);

            return true;
        } catch (Exception $e) {
            error_log("Error al insertar usuario: " . $e->getMessage());
            return false;
        }
    }


    // Recibe por parámetro un objeto con los datos a actualizar
    public function actualizar($user)
    {
        // Cuando se realizan actualizaciones, se actualizan todos los campos, pero las claves primarias no se tocan
        $consulta = "UPDATE usuarios SET username = :username, email = :email, password = :password, tipo = :tipo, monedero = :monedero WHERE id = :id";
        $param = array();

        // Asignamos los valores del objeto que hemos recibido por parámetro
        $param[":id"] = $user->__get("id");
        $param[":username"] = $user->__get("username");
        $param[":email"] = $user->__get("email");
        $param[":password"] = $user->__get("password");
        $param[":tipo"] = $user->__get("tipo");
        $param[":monedero"] = $user->__get("monedero");

        // Ejecutamos la consulta
        $this->ConsultaSimple($consulta, $param);
    }

    public function buscar($username = "")
    {
        $consulta = "SELECT id, username, email, password, tipo, monedero FROM usuarios WHERE 1 ";
        $param = array();

        $this->usuarios = array();

        if ($username != "") {
            $consulta .= " AND username LIKE :username";
            $param[":username"] = "%" . $username . "%";
        }
        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $user = new Usuario();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $user->__set("id", $fila['id']);
            $user->__set("username", $fila['username']);
            $user->__set("email", $fila['email']);
            $user->__set("password", $fila['password']);
            $user->__set("tipo", $fila['tipo']);
            $user->__set("monedero", $fila['monedero']);

            // Se inserta el objeto que acabamos de crear en el Array de objetos tiendas
            $this->usuarios[] = $user;
        }
    }

    public function hallarPaginas($numRegistros)
    {
        $consulta = "SELECT COUNT(*) as total FROM usuarios";
        $this->ConsultaDatos($consulta);
        $fila = $this->filas[0]; // extraemos la fila

        $total = $fila['total']; //extraemos el alias

        $numPaginas = ceil($total / $numRegistros); // se redondea hacia arriba por si hay un resto, que no se queden datos sin mostrar

        return $numPaginas;
    }

    public function listarConLimite($inicio, $numRegistros)
    {
        $consulta = "SELECT * FROM usuarios LIMIT $inicio,$numRegistros";
        $param = array();

        // Se realiza para vaciar el array de las usergorías entre consulta y consulta
        $this->usuarios = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva usergoría
            $user = new Usuario();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $user->__set("id", $fila['id']);
            $user->__set("username", $fila['username']);
            $user->__set("email", $fila['email']);
            $user->__set("password", $fila['password']);
            $user->__set("tipo", $fila['tipo']);
            $user->__set("monedero", $fila['monedero']);

            // Se inserta el objeto que acabamos de crear en el Array de objetos usergorías
            $this->usuarios[] = $user;
        }
    }

    public function obtenerPass($user)
    {
        $consulta = "SELECT password FROM usuarios WHERE username = :username";
        $param = array(":username" => $user);
        $this->ConsultaDatos($consulta, $param);
        return $this->filas[0] ?? null;
    }

    public function login($user)
    {
        $consulta = "SELECT * FROM usuarios WHERE username = :usuario";
        $param = array(":usuario" => $user);
        $this->ConsultaDatos($consulta, $param);
        return $this->filas[0] ?? null;
    }
}
