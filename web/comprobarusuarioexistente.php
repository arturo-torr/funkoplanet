<?php
require_once "../dao/UsuarioDAO.php";
$db = "funkoplanet";
$daoUsuarios = new DaoUsuarios($db);

$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'];

$user = $daoUsuarios->obtenerPorUsername($username);

if ($user || $username == "") {
    echo json_encode(['success' => false]);
} else {
    echo json_encode(
        [
            'success' =>
            true
        ]
    );
    exit;
}
