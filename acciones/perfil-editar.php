<?php
use DaVinci\Auth\Auth;
use DaVinci\Models\Usuario;
require_once __DIR__ . '/../bootstrap/init.php';

$usuario_id = (new Auth)->getId();
$usuario = (New Usuario)->traerPorId($usuario_id);

$username = $_POST['username'];

$errores = [];

if (empty($username)) {
    $errores['username'] = "Esté campo no puede quedar vacío.";
}

if (count($errores) > 0) {
    $_SESSION['data-form'] = $_POST;
    $_SESSION['errores'] = $errores;

    header("Location: ../index.php?s=perfil&id= " . $usuario_id);
    exit;
}

if ((new Usuario)->verificarDisponibilidadUsuario($usuario_id, $username)) {
    $_SESSION['data-form'] = $_POST;
    $_SESSION['mensajeError'] = "El username ya está en uso. Intenta otro distinto.";

    header("Location: ../index.php?s=perfil&id= " . $usuario_id);
    exit;
}

try {
    (new Usuario)->editar([
        'usuario_id' => $usuario_id,
        'username' => $username ?? $usuario->getUsername(),
    ]);

    if(
        !empty($avatar['tmp_name']) &&
        !empty($usuario->getAvatar())
    ) {
        $avatar = __DIR__ . '/../imgs/' . $usuario->getAvatar();

        if(file_exists($avatar)) unlink($avatar);
    }

    $_SESSION['mensajeFeedback'] = "Tu perfil fue modificado con éxito.";
    header("Location: ../index.php?s=perfil&id= " . $usuario_id);
    exit;
} catch (Exception $e) {
    $_SESSION['data-form'] = $_POST;
    $_SESSION['mensajeError'] = "Ocurrío un error al modificar tu username. Intenta más tarde.";
    header("Location: ../index.php?s=perfil&id= " . $usuario_id);
    exit;
}

