<?php 
use DaVinci\Models\Noticia;
$noticia = (new Noticia)->traerPorId($_GET['id']);
?>
<section class="container">
	<h1 class="mb-1">Confirmación Necesaria</h1>
	<p class="mb-1">Para eliminar la noticia es necesario confirmar la acción. A continuación se muestran los datos de la noticia. ¿Estás seguro/a que querés eliminarla?</p>

    <article class="noticias-item mb-1">
        <div class="noticias-item_content">
            <h2><?= $noticia->getTitulo();?></h2>
            <p><?= $noticia->getSinopsis();?></p>
        </div>
        <picture class="noticias-item_imagen">
            <source srcset="../imgs/big-<?= $noticia->getImagen();?>" media="all and (min-width: 46.875em)">
            <img src="../imgs/<?= $noticia->getImagen();?>" alt="<?= $noticia->getImagenDescripcion();?>">
        </picture>

        <p><?= $noticia->getTexto();?></p>
    </article>

    <hr class="mb-1">

    <form action="acciones/noticias-eliminar.php?id=<?= $noticia->getNoticiaId();?>" method="post">
		<button class="button button-error" type="submit">Sí, eliminar</button>
	</form>
</section>