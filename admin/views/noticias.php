<?php 
use DaVinci\Models\Noticia;

$noticias = (new Noticia)->todo();
?>

<section class="container">
	<h1 class="mb-1">Administración de Noticias</h1>

	<div class="mb-1"><a href="index.php?s=noticias-publicar">Publicar una Noticia</a></div>

	<table class="table">
		<thead>
			<tr>
				<th>ID</th>
				<th>Estado</th>
				<th>Fecha Publicación</th>
				<th>Título</th>
				<th>Sinopsis</th>
				<th>Imagen</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		foreach($noticias as $noticia):
		?>
			<tr>
				<td><?= htmlspecialchars($noticia->getNoticiaId());?></td>
				<td><?= htmlspecialchars($noticia->getEstadoPublicacion()->getNombre());?></td>
				<td><?= htmlspecialchars($noticia->getFechaPublicacion());?></td>
				<td><?= htmlspecialchars($noticia->getTitulo());?></td>
				<td><?= htmlspecialchars($noticia->getSinopsis());?></td>
				<!-- TODO: Verificar si existe la imagen antes de ponerla. -->
				<td><img src="<?= '../imgs/' . htmlspecialchars($noticia->getImagen());?>" alt="<?= htmlspecialchars($noticia->getImagenDescripcion());?>"></td>
				<td>
					<a href="index.php?s=noticias-editar&id=<?= $noticia->getNoticiaId();?>" class="button">Editar</a>
					<a href="index.php?s=noticias-eliminar&id=<?= $noticia->getNoticiaId();?>" class="button button-error">Eliminar</a>
					<!--<form action="acciones/noticias-eliminar.php?id=<?= $noticia->getNoticiaId();?>" method="post" class="form-eliminar">
						<button class="button button-error" type="submit">Eliminar</button>
					</form>-->
				</td>
			</tr>
		<?php 
		endforeach;
		?>
		</tbody>
	</table>
</section>

