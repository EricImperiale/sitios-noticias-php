<?php
use DaVinci\Auth\Auth;
use DaVinci\Models\Noticia;

$auth = new Auth();

$noticia = (new Noticia)->traerPorId($_GET['id']);

$dataPost = $_SESSION['data-post'] ?? null;
$errores = $_SESSION['errores'] ?? null;
unset($_SESSION['data-post'], $_SESSION['errores']);

if(!$noticia) {
    require_once __DIR__ . '/404.php';
} else {
?>
<section class="container">
    <article class="noticias-item">
        <div class="noticias-item_content">
            <h1><?= $noticia->getTitulo();?></h1>
            <p><?= $noticia->getSinopsis();?></p>
            <div class="mb-1">
                <?php
                foreach($noticia->getEtiquetas() as $etiqueta):
                ?>
                <span class="badge"><?= $etiqueta->getNombre();?></span>
                <?php
                endforeach;
                ?>
            </div>
        </div>
        <picture class="noticias-item_imagen">
            <source srcset="imgs/big-<?= $noticia->getImagen(); ?>" media="all and (min-width: 46.875em)">
            <img src="imgs/<?= $noticia->getImagen();?>" alt="<?= $noticia->getImagenDescripcion(); ?>">
        </picture>

        <p><?= $noticia->getTexto();?></p>
    </article>

    <?php if ($auth->autenticado()): ?>
    <div id="noticias-form">
        <h2>Deja tu comentario</h2>

        <form action="acciones/noticias-comentar.php" method="post">
            <div class="form-fila">
                <input
                        type="hidden"
                        name="id"
                        value='<?= $noticia->getNoticiaId();?>'
                >
                <label for="comentario">Comentario</label>
                <?php
                if (isset($errores['comentario'])):
                ?>
                <div class="msg-error" id="error-texto">
                    <span class="visually-hidden">Error:</span> <?= $errores['comentario'];?>
                 </div>
                <?php
                endif;
                ?>
                <textarea
                        id="comentario"
                        name="comentario"
                        class="form-control"
                ><?= $dataPost['comentario'] ?? null; ?></textarea>
            </div>

            <div class="form-fila">
                <button class="button">Comentar</button>
            </div>
        </form>
    </div>

    <div class="noticias-comentarios">
        <h3>Comentarios (<?= $noticia->getCantidadComentarios();?>)</h3>
        <?php
        if (count($noticia->getComentarios()) > 0):
        ?>
            <?php
            foreach ($noticia->getComentarios() as $comentario):
                ?>
                <div class="noticias-comentarios_comentario">
                    <div class="noticias-comentarios_contenido">
                        <p class="noticias-comentarios_texto"><?= htmlspecialchars($comentario->getComentario());?></p>
                        <small class="noticias-comentarios_fecha">Publicado el: <?= htmlspecialchars($comentario->getFechaPublicacion()); ?> por <?= htmlspecialchars($comentario->getUsername() ?? $comentario->getEmail());?></small>
                    </div>
                </div>
            <?php
            endforeach;
            ?>
        <?php
        else:
        ?>
        <p>No hay comentarios para mostrar.</p>
        <?php
        endif;
        ?>
    </div>

    <!-- Si el usuario no está autenticado -->
    <?php else: ?>
    <div id="noticias-form">
        <h2>Deja tu comentario</h2>
        <p>Tenés que ingresar a tu cuenta para dejar un comentario. <a href="index.php?s=iniciar-sesion">Iniciar Sesión.</a></p>
    </div>
    <?php endif; ?>
</section>
<?php
}
?>
