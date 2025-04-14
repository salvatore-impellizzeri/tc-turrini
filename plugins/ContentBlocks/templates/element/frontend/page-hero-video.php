<div class="page-hero page-hero--video <?= !empty($item->flag_1) ? 'page-hero--light' : '' ?>" data-animated>
    <div class="page-hero__bg <?= !empty($item->flag_2) ? 'page-hero__bg--overlay' : '' ?>">
        <?php if (!empty($item->title_5) && !empty($item->title_6)): ?>
            <video autoplay muted loop playsinline poster="<?= $img ?? '' ?>" data-video-player>

            </video>
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

<?php if (!empty($item->title_5) && !empty($item->title_6)): ?>
<?= $this->Html->scriptStart(['block' => 'scriptBottom']) ?>
$(function () {
    let video = $('[data-video-player]').get(0);
    let WindowWidth = $(window).width();


    if (WindowWidth < 1080) {
        $(video).append('<source src="<?= $item->title_6 ?>" type="video/mp4">');
    } else {
        $(video).append('<source src="<?= $item->title_5 ?>" type="video/mp4">');
    }

});

<?= $this->Html->scriptEnd() ?>
<?php endif; ?>
