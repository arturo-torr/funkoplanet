<?php

require_once '../includes/libreriaPDO.php';
require_once '../models/FotoPro.php';

class DaoFotosProductos extends DB
{
    public $fotosPro = array();

    // Al instanciar el DAO, especicamos sobre que BBDD trabajaremos
    public function __construct($base)
    {
        $this->dbname = $base;
    }

    public function obtenerSiguienteId($idProducto)
    {
        $consulta = "SELECT MAX(id_foto) AS UltId FROM fotopro WHERE id_producto=:IdPro";
        $param = array();
        $param = array(":IdPro" => $idProducto);
        $this->ConsultaDatos($consulta, $param);

        $fila = $this->filas[0];

        return $fila['UltId'] + 1;
    }

    public function obtenerNumeroFotos($idProducto)
    {
        $consulta = "SELECT id_foto, foto FROM fotopro WHERE id_producto = :IdPro";
        $param = array();

        $param = array(":IdPro" => $idProducto);

        $this->ConsultaDatos($consulta, $param);

        return count($this->filas);
    }

    // Método para insertar una situación que obtiene por parámetro
    public function insertar($fotoPro)
    {
        $consulta = "INSERT INTO fotopro VALUES(:id_foto, :id_producto, :foto)";
        $param = array();

        $param[":id_foto"] = $fotoPro->__get("id_foto");
        $param[":id_producto"] = $fotoPro->__get("id_producto");
        $param[":foto"] = $fotoPro->__get("foto");

        $this->ConsultaSimple($consulta, $param);
    }

    public function listarPorId($id)
    {
        $consulta = "SELECT * FROM fotopro WHERE id_producto = :id ORDER BY id_foto DESC";
        $param = array();

        $param[":id"] = $id;

        $this->fotosPro = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $fotoPro = new FotoPro();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $fotoPro->__set("id_foto", $fila['id_foto']);
            $fotoPro->__set("id_producto", $fila['id_producto']);
            $fotoPro->__set("foto", $fila['foto']);

            $this->fotosPro[] = $fotoPro;
        }
    }


    public function listarUnaImagenPorId($id)
    {
        $consulta = "SELECT * FROM fotopro WHERE id_producto = :id ORDER BY id_foto DESC LIMIT 1";
        $param = array();

        $param[":id"] = $id;

        $this->fotosPro = array();

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $fotoPro = new FotoPro();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $fotoPro->__set("id_foto", $fila['id_foto']);
            $fotoPro->__set("id_producto", $fila['id_producto']);
            $fotoPro->__set("foto", $fila['foto']);

            $this->fotosPro[] = $fotoPro;
        }
    }


    public function eliminar($idFoto, $idProducto)
    {
        $consulta = "DELETE FROM fotopro WHERE id_foto = :id_foto AND id_producto = :id_producto";
        $param = array();
        $param = array(":id_foto" => $idFoto, ":id_producto" => $idProducto);

        $this->ConsultaSimple($consulta, $param);
    }
}
