<div class="page-cta">

    <?php if (!empty($item->text_1)): ?>
        <div class="page-cta__content"><?= $item->text_1 ?></div>
    <?php endif; ?>

    <?php if (!empty($item->title_3) && !empty($item->title_4)): ?>
        <div class="page-cta__actions">
            <?= $this->element('cta', [
                'label' => $item->title_3, 
                'url' => $item->title_4,
                'icon' => 'icons/arrow-right.svg',
            ]) ?>
        </div>
    <?php endif; ?>
</div>