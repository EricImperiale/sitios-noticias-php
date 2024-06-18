<?php
use DaVinci\Auth\Auth;
require_once __DIR__ . '/../../bootstrap/init.php';

(new Auth)->logout();

$_SESSION['mensajeFeedback'] = 'La sesión se cerró correctamente. ¡Te esperamos de nuevo!';

header("Location: ../index.php?s=iniciar-sesion");
exit;