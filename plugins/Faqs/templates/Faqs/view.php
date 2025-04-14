<article class="faq">
    <div class="faq__content">
        <h1><?= $item->title ?></h1>

        <?= $item->text ?>
    </div>

    <?php if (!empty($images['gallery'])): ?>
        <div class="faq_gallery">
            <?php echo $this->element('gallery', ['images' => $images['gallery']]) ?>
        </div>
    <?php endif; ?>
</article>
