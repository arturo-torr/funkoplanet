<?php
// El siguiente php se utiliza para mandar a JavaScript el usuario y su rol
session_start();

if (isset($_SESSION['usuario'])) {
    // Si el usuario está autenticado
    if (isset($_SESSION['usuario']['rol'])) {
        // Devolver la información del usuario y su rol
        header('Content-Type: application/json');
        echo json_encode(array(
            'username' => $_SESSION['usuario']['username'],
            'rol' => $_SESSION['usuario']['rol']
        ));
    } else {
        // Si no se ha establecido el rol del usuario, devolver un error
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(array(
            'error' => 'No se pudo obtener el rol del usuario'
        ));
    }
} else {
    // Si no hay un usuario autenticado, devolver un objeto JSON vacío
    header('Content-Type: application/json');
    echo json_encode(array());
}
