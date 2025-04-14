
<article class="product">

    <?php if (!empty($images['preview'])): ?>
        <div class="product__preview">
            <img src="<?= $this->Frontend->resize($images['preview']->path, 1920) ?>" alt="<?= h($images['preview']->title) ?>">
        </div>
    <?php endif;  ?>

    <div class="product__description">
        <h1><?= $item->title ?></h1>

        <?php echo $item->text ?>
    </div>


    <?php if (!empty($images['gallery'])): ?>
        <div class="product__gallery">
            <?php echo $this->element('gallery', ['images' => $images['gallery']]) ?>
        </div>
    <?php endif; ?>


</article>
