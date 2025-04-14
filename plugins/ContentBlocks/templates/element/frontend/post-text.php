<section class="post-text post-section">
    <?php if (!empty($item->title_1)): ?>
        <h2 class="post-text__title"><?= $item->title_1 ?></h2>
    <?php endif; ?>

    <div class="post-text__content">
        <?= $item->text_1 ?>
    </div>

    <?php if (!empty($item->title_3) && !empty($item->title_4)): ?>
        <div class="post-text__cta">
            <?= $this->element('cta', [
                'label' => $item->title_3, 
                'url' => $item->title_4,
                'icon' => 'icons/arrow-right.svg',
            ]) ?>
        </div>
    <?php endif; ?>
    
</section>
