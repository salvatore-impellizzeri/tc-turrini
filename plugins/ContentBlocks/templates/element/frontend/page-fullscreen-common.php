<div class="page-fullscreen page-section <?= $extraClass ?? '' ?> <?= $item->flag_1 ? 'page-fullscreen--light' : '' ?>" data-animated>
    <div class="page-fullscreen__bg <?= $item->flag_2 ? 'page-fullscreen__bg--overlay' : ''?>">
        <?php if (!empty($images['image']->path) && !empty($images['image']->responsive_images['mobile']->path)) : ?>
            <picture>
                <source media="(min-width:1921px)" srcset="<?= $this->Frontend->image($images['image']->path) ?>">
                <source media="(min-width:811px)" srcset="<?= $this->Frontend->resize($images['image']->path, 1920) ?>">
                <img src="<?= $this->Frontend->image($images['image']->responsive_images['mobile']->path) ?>" alt="<?= $images['image']->title ?>">
            </picture>
        <?php endif; ?>

        <?php if (!empty($video)) : ?>
            <video muted playsinline loop data-play-video>
                <source src="<?= $video ?>" type="video/mp4">
            </video>
        <?php endif; ?>

        <?php if (!empty($gallery)) : ?>
            <?= $this->element('slider', [
                'images' => $gallery,
                'extraClass' => 'slider--fullscreen',
                'responsive' => [
                    '1921' => [2560, 1440],
                    '1081' => [1920, 1080],
                    '0' => [1080, 810]
                ]
            ]) ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($cta) || !empty($text)) : ?>
        <div class="page-fullscreen__fg">

            <div class="page-fullscreen__wrapper">
                <?php if (!empty($text)) : ?>
                    <div class="page-fullscreen__title"><?= $text ?></div>
                <?php endif; ?>

                <?php if (!empty($cta) && !empty($url)) : ?>
                    <div class="page-fullscreen__actions">
                        <?= $this->element('cta', [
                            'label' => $cta,
                            'url' => $url,
                            'icon' => 'icons/arrow-right.svg',
                        ]) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>