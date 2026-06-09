<?php
use Auth\App\Entity\DleSong;

/** @var DleSong[] $dleSongs */

?>


<?php foreach($dleSongs as $dleSong): ?>

        <div class="music-popular-wrapper">
            <div class="music-popular__item">
                <div class="popular-play">
                    <div class="btn_player">
                        <button aria-label="Воспроизвести"
                                data-url="<?= $dleSong->getUrl() ?>"
                                data-song-id="<?= $dleSong->getId() ?>"
                                data-song-image=""
                                class="popular-play__item">
                            <span class="icon-music-player-play icon-js">

                            </span>
                        </button>
                    </div>
                    <div class="popular-play-name">
                        <a href="/pop_music/2551629-vintazh-dura.html"
                           class="popular-play-author"><?= $dleSong->getTitle() ?></a>
                        <div class="popular-play-composition"><a href="/artist/vintazh.html"><?= $dleSong->getArtistName() ?></a></div>
                    </div>
                </div>
                <div class="popular-download">
                    <div class="popular-download-date"><?= $dleSong->getBitrate() ?> kbps</div>
                    <div class="popular-download-number"><?= $dleSong->getDuration() ?></div>
                    <a href="/pop_music/2551629-vintazh-dura.html" class="popular-download-link"><span
                                class="icon-download-arrow-with-bar"></span><span
                                class="popular-download-text">Скачать</span></a></div>
            </div>
            <div class="popular-progress"></div>
        </div>

<?php endforeach; ?>