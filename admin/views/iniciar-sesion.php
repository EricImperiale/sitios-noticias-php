<?php 

$errores = $_SESSION['errores'] ?? [];
$dataForm = $_SESSION['data-form'] ?? [];
unset($_SESSION['errores'], $_SESSION['data-form']);
?>
<section class="container">
	<h1 class="mb-1">Ingresar al Panel de Administración</h1>

	<p>Ingresá tus credenciales de acceso en el formulario para continuar.</p>

	<form action="acciones/iniciar-sesion.php" method="post">
		<div class="form-fila">
			<label for="email">Email</label>
			<input
				type="email"
				id="email"
				name="email"
				class="form-control"
				value='<?= $dataForm['email'] ?? null;?>'
			>
		</div>
		<div class="form-fila">
			<label for="password">Contraseña</label>
			<input type="password" id="password" name="password" class="form-control">
		</div>
		<button type="submit" class="button">Ingresar</button>
</section>