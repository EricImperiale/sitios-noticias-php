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

$id 					= $_GET['id'];
$titulo 				= $_POST['titulo'];
$sinopsis 				= $_POST['sinopsis'];
$texto 					= $_POST['texto'];
$estado_publicacion_fk	= $_POST['estado_publicacion_fk'];
$imagen_descripcion 	= $_POST['imagen_descripcion'];
$etiquetas 				= $_POST['etiquetas'] ?? [];
$imagen 				= $_FILES['imagen'];

// 2. Validación.
$errores = [];

// Validación de título.
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

	header('Location: ../index.php?s=noticias-editar&id=' . $id);
	exit;
}

$noticia = (new Noticia)->traerPorId($id);

if(!empty($imagen['tmp_name'])) {
	$nombreImagen = time() . "-" . $imagen['name'];

    Image::make($imagen['tmp_name'])
		->fit(100, 100)
		->save(RUTA_IMGS . '/' . $nombreImagen);
	Image::make($imagen['tmp_name'])
		->fit(550, 150)
		->save(RUTA_IMGS . '/big-' . $nombreImagen);
}

// 4. Grabar.
try {
	(new Noticia)->editar($id, [
		'usuario_fk' 			=> $auth->getId(),
		'estado_publicacion_fk' => $estado_publicacion_fk,
		//'fecha_publicacion' 	=> date('Y-m-d H:i:s'),
		'titulo' 				=> $titulo,
		'sinopsis' 				=> $sinopsis,
		'texto' 				=> $texto,
		'imagen' 				=> $nombreImagen ?? $noticia->getImagen(),
		'imagen_descripcion' 	=> $imagen_descripcion,
		'etiquetas' 			=> $etiquetas,
	]);

	if(
		!empty($imagen['tmp_name']) &&
		!empty($noticia->getImagen())
	) {
		$imgChica = RUTA_IMGS . '/' . $noticia->getImagen();
		$imgGrande = RUTA_IMGS . '/big-' . $noticia->getImagen();

		if(file_exists($imgChica)) unlink($imgChica);
		if(file_exists($imgGrande)) unlink($imgGrande);
	}

	$_SESSION['mensajeFeedback'] = '¡La noticia se actualizó con éxito!';

	header("Location: ../index.php?s=noticias");
	exit;
} catch (Exception $e) {
	$_SESSION['mensajeError'] = "Ocurrió un error inesperado. La noticia no pudo ser actualizada.";
	$_SESSION['data-form'] = $_POST;
	header("Location: ../index.php?s=noticias-editar&id=" . $id);
	exit;
}