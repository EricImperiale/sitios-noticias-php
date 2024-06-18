<section class="container">
	<h1 class="mb-1">Restablecer Contraseña</h1>

	<p class="mb-1">Escribí el email con el que te registraste. Vas a recibir un correo con instrucciones para poder definir una contraseña nueva.</p>

	<form action="acciones/enviar-email-password.php" method="post">
		<div class="form-fila">
			<label for="email">Email</label>
			<input type="email" id="email" name="email" class="form-control">
		</div>
		<button type="submit" class="button">Pedir Restablecer Contraseña</button>
	</form>
</section>