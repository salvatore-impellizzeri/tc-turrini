<div class="page-hero-split" data-animated>
    <div class="page-hero-split__top">
        <div class="page-hero-split__wrapper" >
            <?php if (!empty($item->title_1)): ?>
                <span class="page-hero-split__smalltitle">
                    <?= $item->title_1 ?>
                </span>
            <?php endif; ?>

            <?php if (!empty($item->title_2)): ?>
                <h1 class="page-hero-split__title"><?= $item->title_2 ?></h1>
            <?php endif; ?>

            <?php if (!empty($item->title_3) && !empty($item->title_4)): ?>
                <div class="page-hero-split__cta">
                    <?= $this->element('cta', [
                        'label' => $item->title_3, 
                        'url' => $item->title_4,
                        'icon' => 'icons/arrow-right.svg',
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>
        
    </div>
    <div class="page-hero-split__bottom">
        <?php if (!empty($images['hero']->path) && !empty($images['hero']->responsive_images['mobile']->path)): ?>
            <picture>
                <source media="(min-width:1921px)" srcset="<?= $this->Frontend->image($images['hero']->path) ?>">
                <source media="(min-width:811px)" srcset="<?= $this->Frontend->resize($images['hero']->path, 1920) ?>">
                <img src="<?= $this->Frontend->image($images['hero']->responsive_images['mobile']->path) ?>" alt="<?= h($images['hero']->title) ?>" >
            </picture> 
        <?php endif; ?>
    </div>

    
</div>
