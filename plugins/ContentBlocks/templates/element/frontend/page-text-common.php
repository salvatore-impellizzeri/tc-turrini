<div class="page-text page-section <?= $extraClass ?? '' ?>" data-animated>
    <?php if (!empty($item->title_1)): ?>
        <span class="page-text__smalltitle"><?= $item->title_1 ?></span>
    <?php endif; ?>

    <?php if (!empty($item->title_2)): ?>
        <h2 class="page-text__title"><?= $item->title_2 ?></h2>
    <?php endif; ?>

    <?php if (!empty($item->text_1)): ?>
        <div class="page-text__content"><?= $item->text_1 ?></div>
    <?php endif; ?>

    <?php if (!empty($item->title_3) && !empty($item->title_4)): ?>
        <div class="page-text__cta">
            <?= $this->element('cta', [
                'label' => $item->title_3, 
                'url' => $item->title_4,
                'icon' => 'icons/arrow-right.svg',
            ]) ?>
        </div>
    <?php endif; ?>
</div>