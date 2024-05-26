<?php

require_once '../includes/libreriaPDO.php';
require_once '../models/Reserva.php';

class DaoReservas extends DB
{
    public $reservas = array();

    // Al instanciar el DAO, especicamos sobre que BBDD trabajaremos
    public function __construct($base)
    {
        $this->dbname = $base;
    }

    // Método para insertar una situación que obtiene por parámetro
    public function insertar($reserva)
    {

        // Consulta para evitar inyección de SQL
        $consulta = "INSERT INTO reserva VALUES (NULL, :id_usuario, :id_producto, :cantidad, :fecha)";
        $param = array();

        // Asignamos los valores del objeto que hemos recibido por parámetro
        $param[":id_usuario"] = $reserva->__get("id_usuario");
        $param[":id_producto"] =  $reserva->__get("id_producto");
        $param[":cantidad"] = $reserva->__get("cantidad");
        $param[":fecha"] =  $reserva->__get("fecha");

        // Ejecutamos la consulta
        $this->ConsultaSimple($consulta, $param);
    }
}