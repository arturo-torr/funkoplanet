<?php
// El siguiente php se utiliza para mandar a JavaScript el usuario y su rol
session_start();

// Verificar si el usuario está autenticado
if (isset($_SESSION['usuario'])) {
    // Verificar si se ha establecido el rol del usuario
    if (isset($_SESSION['usuario']['rol'])) {
        // Manda la información al JS
        header('Content-Type: application/json');
        echo json_encode(array(
            'username' => $_SESSION['usuario']['username'],
            'rol' => $_SESSION['usuario']['rol']
        ));
    } else {
        // Devuelve código de error si no hay rol de usuario establecido
        http_response_code(401);
        echo json_encode(array(
            'error' => 'No se pudo obtener el rol del usuario'
        ));
    }
}
