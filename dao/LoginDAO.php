<?php
require_once '../includes/libreriaPDO.php';

class DaoLogin extends DB
{
    public $logins = array();

    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function obtenerUltimosIntentos($user)
    {
        $consulta = "SELECT fecha, acceso FROM login WHERE username=:Username ORDER BY fecha DESC LIMIT 3";
        $param = array(":Username" => $user);
        $this->ConsultaDatos($consulta, $param);
        return $this->filas;
    }

    public function tresDenegaciones($filas)
    {
        $denegado = false;
        $cont = 0;
        if (count($filas) >= 3) {
            foreach ($filas as $fila) {
                if ($fila['acceso'] === "D") {
                    $cont++;
                }
            }
            $denegado = ($cont == 3);
        }
        return $denegado;
    }

    public function menorTiempoInt($filas, $intervalo)
    {
        $inicio = $filas[0]['hora'];
        $diferencia = time() - $inicio;
        return ($diferencia <= $intervalo);
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
