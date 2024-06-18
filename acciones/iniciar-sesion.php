<?php
use DaVinci\Auth\Auth;
require_once __DIR__ . '/../bootstrap/init.php';

$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

$errores = [];

if (empty($email)) {
    $errores['email'] = "Tenés que ingresar tu correo electrónico.";
}

if (empty($password)) {
    $errores['password'] = "Tenés que ingresar tu contraseña.";
}

if (count($errores) > 0) {
    $_SESSION['data-form'] = $_POST;
    $_SESSION['errores'] = $errores;

    header("Location: ../index.php?s=iniciar-sesion");
    exit;
}

$auth = new Auth;

if(!$auth->login($email, $password)) {
	$_SESSION['data-form'] = $_POST;
	$_SESSION['mensajeError'] = "Las credenciales ingresadas no coinciden con nuestros registros.";

	header("Location: ../index.php?s=iniciar-sesion");
	exit;
}

header("Location: ../index.php?s=noticias");
exit;