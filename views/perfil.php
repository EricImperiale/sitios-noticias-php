<?php
use DaVinci\Auth\Auth;
use DaVinci\Models\Usuario;

$errores = $_SESSION['errores'] ?? [];
$dataForm = $_SESSION['data-form'] ?? [];
unset($_SESSION['errores'], $_SESSION['data-form']);

$id = (new Auth)->getId();
$usuario = (new Usuario)->traerPorId($id);
?>
<section class="container">
    <div>
        <h1>Tu Perfil</h1>
        <p>Desde acá vas a poder modificar toda tu información.</p>
    </div>

    <div>
        <form action="acciones/perfil-editar.php" method="post" enctype="multipart/form-data">
            <div class="form-fila">
                <label for="email">Username</label>
                <?php
                if (isset($errores['username'])):
                    ?>
                    <div class="msg-error" id="error-texto">
                        <span class="visually-hidden">Error:</span> <?= $errores['username'];?>
                    </div>
                <?php
                endif;
                ?>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    value='<?= $dataForm['username'] ?? $usuario->getUsername();?>'
                >
            </div>
            <div class="form-fila">
               <button type="submit" class="button">Modificar</button>
            </div>
        </form>
    </div>
</section>