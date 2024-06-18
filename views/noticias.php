<?php
use DaVinci\Models\Noticia;

$noticia = new Noticia();

$paginaActual = $_GET['pagina'] ?? 1;
$limit = 3;
$offset = ($paginaActual - 1) * $limit;
$paginas = ceil($noticia->totalNoticias() / $limit);

$noticias = $noticia->paginadas($limit, $offset);
?>
<section class="container">
    <div>
        <h1>Noticias</h1>
        <p class="lead">Qué está pasando.</p>
    </div>
    <?php
    foreach($noticias as $noticia):
    ?>
    <div class="card">
        <article class="noticias-item">
            <div class="noticias-item_content card-body">
                <a href="index.php?s=noticias-leer&id=<?= $noticia->getNoticiaId();?>"><h2><?= htmlspecialchars($noticia->getTitulo());?></h2></a>
                <div class="noticias-item_etiquetas">
                <?php
                foreach($noticia->getEtiquetas() as $etiqueta):
                ?>
                    <span class="badge"><?= $etiqueta->getNombre(); ?></span>
                <?php
                endforeach;
                ?>
                <p>Escrito por <?= htmlspecialchars($noticia->getUsuario()->getEmail());?></p>
                <p><?= htmlspecialchars($noticia->getSinopsis());?></p>
            </div>
            </div>
            <picture class="noticias-item_imagen">
                <source srcset="imgs/big-<?= htmlspecialchars($noticia->getImagen());?>" media="all and (min-width: 46.875em)">
                <img src="imgs/<?= htmlspecialchars($noticia->getImagen());?>" alt="<?= htmlspecialchars($noticia->getImagenDescripcion());?>">
            </picture>
        </article>
    </div>
    <?php
    endforeach;
    ?>

    <div class="paginador">
        <ul class="paginador-lista">
            <?php
            for ($i = 0; $i < $paginas; $i++):
            ?>
            <li><a href="index.php?s=noticias&pagina=<?= $i + 1; ?>"><?= $i + 1; ?></a></li>
            <?php
            endfor;
            ?>
        </ul>
    </div>
</section>
