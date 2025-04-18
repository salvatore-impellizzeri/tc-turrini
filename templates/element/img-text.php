<div class="img-text <?= $extraClass ?? '' ?>">
    <div class="img-text__img <?= $imgClass ?? '' ?>">
        <?php if (!empty($img)): ?>
            <img src="<?= $img ?>" alt="Immagine">
        <?php elseif (!empty($video)): ?>
            <video src="<?= $video ?>" autoplay loop muted playsinline></video>
        <?php endif; ?>
    </div>
    <div class="img-text__text">
        <p class="<?= $textClass ?? 'font-35' ?>">
            <?= $text ?>
        </p>
    </div>
</div>
