<?php if (empty($images['gallery'])) return; ?>

<div class="page-slider page-section" data-page-slider data-animated>
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($images['gallery'] as $image): ?>
                <div class="swiper-slide">
                    <a href="<?= $this->Frontend->resize($image->path, 1920, 1080) ?>" data-fancybox="gallery-<?= $item->id ?>">
                        <img src="<?= $this->Frontend->resize($image->path, 3000, 560) ?>" alt="<?= h($image->title) ?>">
                    </a>
                    
                </div>
            <?php endforeach; ?>
        </div>
        <span class="slider__arrow slider__arrow--prev" data-slider-arrow-prev></span>
        <span class="slider__arrow slider__arrow--next" data-slider-arrow-next></span>
    </div>
</div>