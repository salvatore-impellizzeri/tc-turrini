<article class="news">
    <div class="news__content">
        <h1><?= $item->title ?></h1>

        <?= $item->text ?>
    </div>

    <?php if (!empty($images['gallery'])): ?>
        <div class="news_gallery">
            <?php echo $this->element('gallery', ['images' => $images['gallery']]) ?>
        </div>
    <?php endif; ?>
</article>
