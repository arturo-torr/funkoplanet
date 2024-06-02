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
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$daoEventos = new DaoEventos("funkoplanet");
$daoEventos->listar();


switch ($parametro) {
    case 'eventsMenu':
        echo json_encode($daoEventos->eventosJSON, JSON_UNESCAPED_UNICODE);
        break;
    case 'eventClicked':
        header("Location: /funkoplanet/views/event.php?evento=$id");
        exit();
        break;
    default:
        # code...
        break;
}
