<div class="page-cta-image">

    <div class="page-cta-image__grid">
        <div class="page-cta-image__wrapper">
            <?php if (!empty($item->text_1)): ?>
                <div class="page-cta-image__content"><?= $item->text_1 ?></div>
            <?php endif; ?>
            
            <?php if (!empty($item->title_3) && !empty($item->title_4)): ?>
                <div class="page-cta-image__actions">
                    <?= $this->element('cta', [
                        'label' => $item->title_3, 
                        'url' => $item->title_4,
                        'icon' => 'icons/arrow-right.svg',
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($images['image'])): ?>
            <div class="page-cta-image__image">
                <img src="<?= $this->Frontend->image($images['image']->path) ?>" alt="<?= h($images['image']->title) ?>">
            </div>
        <?php endif; ?>
    </div>

    

    
</div>