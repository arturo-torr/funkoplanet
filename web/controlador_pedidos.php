<?php
session_start();

if (isset($_GET['parametro'])) {
    $parametro = $_GET['parametro'];
}

if (isset($_POST['parametro'])) {
    $parametro = $_POST['parametro'];
}

switch ($parametro) {
    case 'misPedidos':
        header('Location: ../views/mispedidos.php');
        exit();
        break;
    default:
        # code...
        break;
}
