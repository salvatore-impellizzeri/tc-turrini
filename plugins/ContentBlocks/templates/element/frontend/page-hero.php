<div class="page-hero <?= !empty($item->flag_1) ? 'page-hero--light' : '' ?>" data-animated>
    <div class="page-hero__bg <?= !empty($item->flag_2) ? 'page-hero__bg--overlay' : '' ?>">
        <?php if (!empty($images['hero']->path) && !empty($images['hero']->responsive_images['mobile']->path)): ?>
            <picture>
                <source media="(min-width:1921px)" srcset="<?= $this->Frontend->image($images['hero']->path) ?>">
                <source media="(min-width:811px)" srcset="<?= $this->Frontend->resize($images['hero']->path, 1920) ?>">
                <img src="<?= $this->Frontend->image($images['hero']->responsive_images['mobile']->path) ?>" alt="<?= h($images['hero']->title) ?>" >
            </picture> 
        <?php endif; ?>
    </div>
    <div class="page-hero__fg">
        <div class="page-hero__wrapper" >
            <?php if (!empty($item->title_1)): ?>
                <span class="page-hero__smalltitle">
                    <?= $item->title_1 ?>
                </span>
            <?php endif; ?>

            <?php if (!empty($item->title_2)): ?>
                <h1 class="page-hero__title"><?= $item->title_2 ?></h1>
            <?php endif; ?>

            <?php if (!empty($item->title_3) && !empty($item->title_4)): ?>
                <div class="page-hero__cta">
                    <?= $this->element('cta', [
                        'label' => $item->title_3, 
                        'url' => $item->title_4,
                        'icon' => 'icons/arrow-right.svg',
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>    
    </div>
    
</div>
