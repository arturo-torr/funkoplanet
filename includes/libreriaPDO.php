<?php
class DB
{
    private $usuario = "root";
    private $clave = "";
    private $host = "localhost";

    private $pdo;

    protected $dbname = "";

    // Array de filas con el resultado de la consulta
    public $filas = array();

    // El constructor recibe como parámetro la base de datos a conectar
    public function __construct($base)
    {
        $this->dbname = $base;
    }

    // Se conecta a la base de datos que introducimos por parámetro
    private function Conectar()
    {
        $dns = "mysql:host=$this->host;dbname=$this->dbname";

        try {
            $this->pdo = new PDO($dns, $this->usuario, $this->clave);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // Función para aquellas consultas que no devuelven filas
    public function ConsultaSimple($consulta, $param = array())
    {
        $this->Conectar();

        // Preparamos la consulta y recibimos el objeto statement
        $statement = $this->pdo->prepare($consulta);

        // Si al ejecutar la consulta devuelve falso, se da un error
        if (!$statement->execute($param)) {
            echo "Error en la consulta simple.";
        };

        $this->Cerrar();
    }

    // Funcion para aquellas consultas que devuelven 1 ó más filas
    public function ConsultaDatos($consulta, $param = array())
    {
        $this->Conectar();

        // Preparamos la consulta y recibimos el objeto statement
        $statement = $this->pdo->prepare($consulta);

        $resul = $statement->execute($param);

        // Si al ejecutar la consulta devuelve filas, se mostrará
        if ($resul) {
            // Extrae todas las filas de una sola vez
            $this->filas = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Extrae las filas de una en una
            // while ($fila = $statement->fetch(PDO::FETCH_ASSOC)) {
            //     $this->filas[] = $fila;
            // }
        } else {
            // Si no devuelve nada, muestra un error
            echo "Error en la consulta de datos.";
        }

        $this->Cerrar();

        // Devuelve el array con los resultados
        return $this->filas;
    }

    // Se pone el objeto pdo a nulo para cerrar la conexión
    private function Cerrar()
    {
        $this->pdo = null;
    }
}
