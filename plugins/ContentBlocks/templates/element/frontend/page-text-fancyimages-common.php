<div class="page-text-fancyimages <?= $extraClass ?? '' ?> page-section">
    <div class="page-text-fancyimages__grid" data-animated>

        <?php if (!empty($images['image_1']->path)): ?>
            <img src="<?= $this->Frontend->image($images['image_1']->path) ?>" alt="<?= h($images['image_1']->title) ?>">
        <?php endif; ?>

        <?php if (!empty($images['image_2']->path)): ?>
            <img src="<?= $this->Frontend->image($images['image_2']->path) ?>" alt="<?= h($images['image_2']->title) ?>">
        <?php endif; ?>

    </div>
    <div class="page-text-fancyimages__content" data-animated>
        <?php if (!empty($item->title_1)): ?>
            <span class="page-text-fancyimages__smalltitle">
                <?= $item->title_1 ?>
            </span>
        <?php endif; ?>

        <?php if (!empty($item->title_2)): ?>
            <h2 class="page-text-fancyimages__title"><?= $item->title_2 ?></h2>
        <?php endif; ?>

        <?php if (!empty($item->text_1)): ?>
            <div class="page-text-fancyimages__text"><?= $item->text_1 ?></div>
        <?php endif; ?>

        <?php if (!empty($item->title_3) && !empty($item->title_4)): ?>
            <div class="page-text-fancyimages__cta">
                <?= $this->element('cta', [
                    'label' => $item->title_3, 
                    'url' => $item->title_4,
                    'icon' => 'icons/arrow-right.svg',
                ]) ?>
            </div>
        <?php endif; ?>
    </div>
</div>