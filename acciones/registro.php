<?php
use DaVinci\Models\Usuario;
use DaVinci\Security\Hash;
require_once __DIR__ . '/../bootstrap/init.php';

$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$passwordConfirmar 	= htmlspecialchars($_POST['password_confirmar']);

$errores = [];

if (empty($email)) {
    $errores['email'] = "Tenés que ingresar tu correo electrónico.";
}

if (empty($password)) {
    $errores['password'] = "Tenés que ingresar tu contraseña.";
}

if ($password !== $passwordConfirmar) {
    $errores['password_confirmar'] = "Las contraseñas no coinciden.";
}

if (count($errores) > 0) {
    $_SESSION['data-form'] = $_POST;
    $_SESSION['errores'] = $errores;

    header("Location: ../index.php?s=registro");
    exit;
}

$usuario = new Usuario();

if ($usuario->traerPorEmail($email)) {
    $_SESSION['mensajeError'] = "El correo electrónico ya está en uso. Por favor, intenta con otro.";
    $_SESSION['data-form'] = $_POST;
    header('Location: ../index.php?s=registro');
    exit;
}

try {
    $usuario->crear([
		'rol_fk' => 2, 
		'email' => $email,
		'password' => Hash::crear($password),
	]);

	$_SESSION['mensajeFeedback'] = "¡Cuenta creada con éxito! Ya podés iniciar sesión.";
	header('Location: ../index.php?s=iniciar-sesion');
	exit;
} catch (Exception $e) {
	$_SESSION['data-form'] = $_POST;
	$_SESSION['mensajeError'] = "Ocurrió un error inesperado. La cuenta no pudo crearse. " . $e->getMessage();
	header('Location: ../index.php?s=registro');
	exit;
}