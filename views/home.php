<?php
use DaVinci\Models\Noticia;

$noticias = (new Noticia)->publicadas();
?>
<div class="container-flex">
    <h1 class="visually-hidden">Página Principal</h1>
    <section id="ranking">
        <div>
            <h2>Ranking</h2>
            <p>La carrera por los playoffs.</p>
            <p>Este es la tabla de posiciones en la carrera hacia los playoffs.</p>
        </div>

        <section id="west-conference">
            <h3>Conferencia Oeste</h3>
            <ol class="positions">
                <li>Houston Rockets</li>
                <li>Golden State</li>
                <li>Portland Trailblazers</li>
                <li>Oklahoma City Thunder</li>
                <li>Utah Jazz</li>
                <li>New Orleans Pelicans</li>
                <li>San Antonio Spurs</li>
                <li>Minnesota Timberwolves</li>
                <li>Denver Nuggets</li>
                <li>Los Angeles Clippers</li>
                <li>Los Angeles Lakers</li>
                <li>Sacramento Kings</li>
                <li>Dallas Mavericks</li>
                <li>Memphis Grizzles</li>
                <li>Phoenix Suns</li>
            </ol>
        </section>

        <section id="east-conference">
            <h3>Conferencia Este</h3>
            <ol class="positions">
                <li>Toronto Raptors</li>
                <li>Boston Celtics</li>
                <li>Philadelphia 76ers</li>
                <li>Cleveland Cavaliers</li>
                <li>Indiana Pacers</li>
                <li>Miami Heat</li>
                <li>Milwaukee Bucks</li>
                <li>Washinton Wizards</li>
                <li>Detroit Pistons</li>
                <li>Charlotte Hornets</li>
                <li>New York Knicks</li>
                <li>Brooklyn Nets</li>
                <li>Chicago Bulls</li>
                <li>Orlando Magic</li>
                <li>Atlanta Hawks</li>
            </ol>
        </section>
    </section>

    <section class="noticias-home">
        <div>
            <h2>Noticias</h2>
            <p class="lead">Qué está pasando.</p>
        </div>
        <?php
        foreach($noticias as $noticia):
        ?>
        <div class="card">
            <article class="noticias-item">
                <div class="noticias-item_content card-body">
                    <a href="index.php?s=noticias-leer&id=<?= htmlspecialchars($noticia->getNoticiaId());?>"><h3><?= $noticia->getTitulo();?></h3></a>
                    <p><?= $noticia->getSinopsis();?></p>
                </div>
                <picture class="noticias-item_imagen">
                    <source srcset="imgs/big-<?= $noticia->getImagen();?>" media="all and (min-width: 46.875em)">
                    <img src="imgs/<?= $noticia->getImagen();?>" alt="<?= htmlentities($noticia->getImagenDescripcion());?>">
                </picture>
            </article>
        </div>
        <?php
        endforeach;
        ?>
    </section>
</div>
