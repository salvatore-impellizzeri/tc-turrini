<a href="<?= $url ?>" class="product-preview">
    <div class="product-preview__image">
        <?php if (!empty($image)): ?>
            <img src="<?= $this->Frontend->crop($image, 600, 600) ?>" alt="">
        <?php endif; ?>
    </div>
    <div class="product-preview__content">
        <h3 class="product-preview__content__title"><?= $title ?></h3>
        <?= $excerpt ?>
    </div>
</a>
