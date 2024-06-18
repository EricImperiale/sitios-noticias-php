<?php

$errores = $_SESSION['errores'] ?? [];
$dataForm = $_SESSION['data-form'] ?? [];
unset($_SESSION['errores'], $_SESSION['data-form']);
?>
<section class="container">
    <h1 class="mb-1">Registrarse</h1>

    <p class="mb-1">Creá tu cuenta gratuita en Saraza Basket para obtener XXX beneficios.</p>

    <form action="acciones/registro.php" method="POST">
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
                    <span class="visually-hidden">Error:</span> <?= $errores['password'];?>
                </div>
            <?php
            endif;
            ?>
            <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
            >
        </div>
        <div class="form-fila">
            <label for="password_confirmar">Confirmar Contraseña</label>
            <?php
            if (isset($errores['password_confirmar'])):
                ?>
                <div class="msg-error" id="error-texto">
                    <span class="visually-hidden">Error:</span> <?= $errores['password_confirmar'];?>
                </div>
            <?php
            endif;
            ?>
            <input
                    type="password"
                    id="password_confirmar"
                    name="password_confirmar"
                    class="form-control"
            >
        </div>
        <button type="submit" class="button">Crear Cuenta</button>
    </form>
</section>

<script>
const passwordFields = document.querySelectorAll('[data-password]');

passwordFields.forEach(field => {
    const button = document.createElement('button');
    button.setAttribute('type', 'button');
    button.addEventListener('click', function() {
        field.type = field.type === 'password' ? 'text' : 'password';
    });
    button.appendChild(document.createTextNode('Mostrar/Ocultar'));
    field.parentNode.appendChild(button);
});
</script>