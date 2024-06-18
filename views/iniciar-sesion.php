<?php 

$errores = $_SESSION['errores'] ?? [];
$dataForm = $_SESSION['data-form'] ?? [];
unset($_SESSION['errores'], $_SESSION['data-form']);
?>
<section class="container">
    <h1 class="mb-1">Iniciar Sesión</h1>

    <form action="acciones/iniciar-sesion.php" method="post" class="mb-1">
        <div class="form-fila">
            <label for="email">Email</label>
            <?php
            if (isset($errores['email'])):
                ?>
                <div class="msg-error" id="error-texto">
                    <span class="visually-hidden">Error:</span> <?= $errores['email'];?>
                </div>
            <?php
            endif;
            ?>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                value='<?= $dataForm['email'] ?? null; ?>'
            >
        </div>
        <div class="form-fila">
            <label for="password">Contraseña</label>
            <?php
            if (isset($errores['password'])):
                ?>
                <div class="msg-error" id="error-texto">
                    <span class="visually-hidden">Error:</span> <?= $errores['password']; ?>
                </div>
            <?php
            endif;
            ?>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <button type="submit" class="button">Ingresar</button>
    </form>

    <p>¿No recordás tu contraseña? Podés <a href="index.php?s=restablecer-password">restablecer tu contraseña</a>.</p>
</section>