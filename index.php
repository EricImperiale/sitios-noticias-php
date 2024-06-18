<?php
use DaVinci\Auth\Auth;
use Carbon\Carbon;

require_once __DIR__ . '/bootstrap/init.php';

$auth = new Auth;

$usuarioAuth = $auth->getUsuario();

if ($usuarioAuth !== null && $usuarioAuth->getEmail() !== null) {
    $titlePerfil = "Tu Perfil " . $usuarioAuth->getUsername() ?? $usuarioAuth->getEmail();
} else {
    $titlePerfil = "Tu Perfil";
}

$rutas = [
    '404' => [
        'titulo' => 'Página no Encontrada',
    ],
    'home' => [
        'titulo' => 'Página Principal',
    ],
    'noticias' => [
        'titulo' => 'Últimas Noticias de la NBA',
    ],
    'noticias-leer' => [
        'titulo' => 'Detalle de la Noticia',
    ],
    'iniciar-sesion' => [
        'titulo' => 'Iniciar Sesión',
    ],
    'registro' => [
        'titulo' => 'Crear una Nueva Cuenta',
    ],
    'perfil' => [
        'titulo' => htmlspecialchars($titlePerfil),
    ],
    'restablecer-password' => [
        'titulo' => 'Restablecer Contraseña',
    ],
];

$view = $_GET['s'] ?? 'home';

if(!file_exists('views/' . $view . '.php')) {
    $view = '404';
}

if(!isset($rutas[$view])) {
    $view = '404';
}

$rutaConfig = $rutas[$view];

$mensajeFeedback    = $_SESSION['mensajeFeedback'] ?? null;
$mensajeError       = $_SESSION['mensajeError'] ?? null;
unset($_SESSION['mensajeFeedback'], $_SESSION['mensajeError']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $rutaConfig['titulo'];?> :: Saraza Basket</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header id="main-header">
        <p class="brand">Saraza Basket</p>
        <p>Enterate de todas las novedades sobre la NBA</p>
    </header>
    <nav id="main-nav">
        <div class="container-fixed">
            <ul>
                <li><a href="index.php?s=home">Home</a></li>
                <li><a href="index.php?s=noticias">Noticias</a></li>
                <?php 
                if(!$auth->autenticado()):
                ?>
                <li><a href="index.php?s=iniciar-sesion">Iniciar Sesión</a></li>
                <li><a href="index.php?s=registro">Registrarse</a></li>

                <?php 
                else:
                ?>
                <li><a href="index.php?s=perfil">Mi Perfil</a></li>
                <?php
                if ($auth->esAdministrador()):
                ?>
                <li><a href="admin/index.php?s=dashboard">Panel</a></li>
                <?php
                endif;
                ?>
                <li>
                    <form action="acciones/cerrar-sesion.php" method="post">
                        <button type="submit" class="button">Cerrar Sesión (<?= htmlspecialchars($auth->getUsuario()->getEmail());?>)</button>
                    </form>
                </li>
                <?php 
                endif;
                ?>
            </ul>
        </div>
    </nav>
    <main class="main-content">
        <?php 
        if($mensajeFeedback !== null):
        ?>
        <div class="msg-success"><?= $mensajeFeedback;?></div>
        <?php 
        endif;
        ?>
        <?php 
        if($mensajeError !== null):
        ?>
        <div class="msg-error"><?= $mensajeError;?></div>
        <?php 
        endif;
        ?>

        <?php
        require 'views/' . $view . '.php';
        ?>
    </main>
    <footer id="main-footer">
        <p>&copy; Eric Imperiale - 2024</p>
    </footer>
</body>
</html>
