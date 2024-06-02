<?php
require_once "../dao/UsuarioDAO.php";
require_once "../dao/LoginDAO.php";
require_once "../models/Usuario.php";

$db = "funkoplanet";
$daoUsuarios = new DaoUsuarios($db);
$daoLogin = new DaoLogin($db);

function intentoLogin($user, $pass)
{
    global $daoUsuarios;
    global $daoLogin;
    $filaPass = $daoUsuarios->obtenerPass($user);
    $fila = "";

    if (password_verify("$pass", $filaPass['password'])) {
        $fila = $daoUsuarios->login($user);
        $acceso = "C";
    } else {
        $acceso = "D";
    }
    $daoLogin->insertarIntento($user, $pass, $acceso);
    return $fila;
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$user = new Usuario();

// Asignamos las propiedades correspondientes al nuevo objeto
$encryptPassword = password_hash($password, PASSWORD_BCRYPT);
$user->__set("username", $username);
$user->__set("email", $email);
$user->__set("password", $encryptPassword);
$user->__set("tipo", "E");
$user->__set("monedero", "0");
// Inserción del usuario
$insercionExitosa = $daoUsuarios->insertar($user);

if ($insercionExitosa) {
    $fila = intentoLogin($username, $password);

    // Si el intento es correcto, coge las variables de sesión y redirige al index
    if ($fila) {
        session_start();
        $_SESSION['usuario'] = array(
            'username' => $username,
            'rol' => $fila['tipo'] // Agrega el rol a la sesión
        );
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false]);
