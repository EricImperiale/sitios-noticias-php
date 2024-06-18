<?php
use DaVinci\Auth\Auth;
use DaVinci\Models\Noticia;
use Intervention\Image\ImageManagerStatic as Image;

require_once __DIR__ . '/../../bootstrap/init.php';

$auth = new Auth;
if(!$auth->autenticado()) {
	$_SESSION['mensajeError'] = "Para realizar esta acción es necesario iniciar sesión.";
	header("Location: ../index.php?s=iniciar-sesion");
	exit;
}

$titulo 				= $_POST['titulo'];
$sinopsis 				= $_POST['sinopsis'];
$texto 					= $_POST['texto'];
$imagen_descripcion 	= $_POST['imagen_descripcion'];
$estado_publicacion_fk	= $_POST['estado_publicacion_fk'];
$etiquetas				= $_POST['etiquetas'] ?? []; // Ponemos un default, ya que los checkboxes, si ninguno está tildado, no se envían.
$imagen 				= $_FILES['imagen'];

$errores = [];

// Validación de título.
// empty() => null, "", [], false, 0.
if(empty($titulo)) {
	$errores['titulo'] = "El título no puede quedar vacío.";
} else if(strlen($titulo) < 2) {
	$errores['titulo'] = "El título debe tener al menos 2 caracteres.";
}

if(empty($sinopsis)) {
	$errores['sinopsis'] = "La sinopsis no puede quedar vacía.";
}

if(empty($texto)) {
	$errores['texto'] = "El texto no puede quedar vacío.";
}

if(count($errores) > 0) {
	$_SESSION['errores'] = $errores;
	$_SESSION['data-form'] = $_POST;
	header('Location: ../index.php?s=noticias-publicar');
	exit;
}

if(!empty($imagen['tmp_name'])) {
	$nombreImagen = time() . "-" . $imagen['name'];
	Image::make($imagen['tmp_name'])
		->fit(100, 100)
		->save(RUTA_IMGS . '/' . $nombreImagen);
	Image::make($imagen['tmp_name'])
		->fit(550, 150)
		->save(RUTA_IMGS . '/big-' . $nombreImagen);

	// Guardamos el archivo en la ubicación final, con ayuda de la
	// función move_uploaded_file.
	// move_uploaded_file($imagen['tmp_name'], __DIR__ . '/../../imgs/' . $nombreImagen);
}

try {
	(new Noticia)->crear([
		'usuario_fk' 			=> $auth->getId(),
		'estado_publicacion_fk' => $estado_publicacion_fk,
		'fecha_publicacion' 	=> date('Y-m-d H:i:s'),
		'titulo' 				=> $titulo,
		'sinopsis' 				=> $sinopsis,
		'texto' 				=> $texto,
		'imagen' 				=> $nombreImagen ?? null,
		'imagen_descripcion' 	=> $imagen_descripcion,
		'etiquetas' 			=> $etiquetas,
	]);

	$_SESSION['mensajeFeedback'] = '¡La noticia se publicó con éxito!';
	header("Location: ../index.php?s=noticias");
	exit;
} catch (Exception $e) {
	$_SESSION['data-form'] = $_POST;
	$_SESSION['mensajeError'] = "Ocurrió un error inesperado. La noticia no pudo publicarse. " . $e->getMessage();
	header("Location: ../index.php?s=noticias-publicar");
	exit;
}