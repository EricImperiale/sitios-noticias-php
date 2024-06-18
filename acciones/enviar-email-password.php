<?php
use DaVinci\Auth\RestablecerPassword;
require_once __DIR__ . '/../bootstrap/init.php';

$email = $_POST['email'];

try {
	(new RestablecerPassword)->enviarEmail($email);

	$_SESSION['mensajeFeedback'] = "Se envió un correo a la casilla indicada. Seguí las instrucciones que contiene para restablecer tu contraseña.";
	header('Location: ../index.php?s=iniciar-sesion');
	exit;
} catch (Exception $e) {
	$_SESSION['mensajeError'] = "Ocurrió un error inesperado.";
	$_SESSION['data-form'] = $_POST;
	header('Location: ../index.php?s=restablecer-password');
	exit;
}