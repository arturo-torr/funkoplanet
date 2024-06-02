<?php
require_once "../dao/UsuarioDAO.php";
$db = "funkoplanet";
$daoUsuarios = new DaoUsuarios($db);

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'];

$user = $daoUsuarios->obtenerPorEmail($email);

if ($user || $email == "") {
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
