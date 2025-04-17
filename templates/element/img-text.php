<div class="img-text <?= $extraClass ?? '' ?>">
    <div class="img-text__img">
        <?php if (!empty($img)): ?>
            <img src="<?= $img ?>" alt="Immagine">
        <?php elseif (!empty($video)): ?>
            <video src="<?= $video ?>" autoplay loop muted playsinline></video>
        <?php endif; ?>
    </div>
    <div class="img-text__text">
        <p class="font-35">
            <?= $text ?>
        </p>
    </div>
</div>
