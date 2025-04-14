<div class="page-text-media <?= $extraClass ?? '' ?> page-section" data-animated >
    <div class="page-text-media__image">
        <?php if (!empty($images['image']->path)): ?>
            <img src="<?= $this->Frontend->image($images['image']->path) ?>" alt="<?= h($images['image']->title) ?>">
        <?php endif; ?>

        <?php if (!empty($images['gallery'])): ?>
            <?= $this->element('slider', [
                'images' => $images['gallery'], 
                'width' => 860 * 1.5,
                'height' => 560 * 1.5
            ]) ?>
        <?php endif; ?>

        <?php if (!empty($video)): ?>
            <video  muted playsinline loop data-play-video>
                <source src="<?= $video ?>" type="video/mp4">
            </video> 
        <?php endif; ?>
    </div>
    <div class="page-text-media__content" data-animated>
        <?php if (!empty($item->title_1)): ?>
            <span class="page-text-media__smalltitle">
                <?= $item->title_1 ?>
            </span>
        <?php endif; ?>

        <?php if (!empty($item->title_2)): ?>
            <h2 class="page-text-media__title"><?= $item->title_2 ?></h2>
        <?php endif; ?>

        <?php if (!empty($item->text_1)): ?>
            <div class="page-text-media__text"><?= $item->text_1 ?></div>
        <?php endif; ?>

        <?php if (!empty($item->title_3) && !empty($item->title_4)): ?>
            <div class="page-text-media__cta">
                <?= $this->element('cta', [
                    'label' => $item->title_3, 
                    'url' => $item->title_4,
                    'icon' => 'icons/arrow-right.svg',
                ]) ?>
            </div>
        <?php endif; ?>
    </div>
</div>