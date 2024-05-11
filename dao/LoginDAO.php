<?php
require_once '../includes/libreriaPDO.php';

class DaoLogin extends DB
{
    public $logins = array();

    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function insertarIntento($user, $pass, $acceso)
    {
        $consulta = "INSERT INTO login (username, clave, fecha, acceso) VALUES (:Username, :Clave, :Fecha, :Acceso)";
        $param = array(
            ":Username" => $user,
            ":Clave" => $pass,
            ":Fecha" => time(),
            ":Acceso" => $acceso
        );
        $this->ConsultaSimple($consulta, $param);
    }
}
