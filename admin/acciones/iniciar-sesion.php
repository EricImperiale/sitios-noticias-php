<?php
/*
Vamos a hacer el login.
Esto requiere:
- Recibir los datos del form.
- Validar.
- Preguntar si los datos coinciden con un usuario.
- En caso de éxito, marcar que el usuario está autenticado.
- Redireccionar.
*/
use DaVinci\Auth\Auth;

require_once __DIR__ . '/../../bootstrap/init.php';

$email = $_POST['email'];
$password = $_POST['password'];


// Autenticación.
$auth = new Auth;

if(!$auth->login($email, $password)) {
	$_SESSION['data-form'] = $_POST;
	$_SESSION['mensajeError'] = "Las credenciales ingresadas no coinciden con nuestros registros.";
	header("Location: ../index.php?s=iniciar-sesion");
	exit;
}

// Yay! El usuario se autenticó.
header("Location: ../index.php?s=dashboard");
exit;