<?php
header('Content-Type: application/json');
require_once '../dao/EventoDAO.php';

$parametro = "";
if (isset($_GET['parametro'])) {
    $parametro = $_GET['parametro'];
}
if (isset($_POST['parametro'])) {
    $parametro = $_POST['parametro'];
}
$daoEventos = new DaoEventos("funkoplanet");
$daoEventos->listar();


switch ($parametro) {
    case 'eventsMenu':
        echo json_encode($daoEventos->eventosJSON, JSON_UNESCAPED_UNICODE);
        break;
    default:
        # code...
        break;
}
