<article class="service">
    <div class="service__content">
        <h1><?= $item->title ?></h1>

        <?= $item->text ?>
    </div>

    <?php if (!empty($images['gallery'])): ?>
        <div class="service_gallery">
            <?php echo $this->element('gallery', ['images' => $images['gallery']]) ?>
        </div>
    <?php endif; ?>
</article>
