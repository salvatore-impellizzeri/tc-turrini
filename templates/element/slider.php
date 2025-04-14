<?php if(empty($images)) return; ?>

<div class="slider <?= $extraClass ?? '' ?>" data-slider>
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($images as $image): ?>
                <div class="swiper-slide">
                    <div class="slider__image">
                        <?php if (empty($responsive)): ?>
                            <img src="<?= $this->Frontend->resize($image->path, $width, $height) ?>" alt="">
                        <?php else : ?>
                            <picture>
                                <?php foreach ($responsive as $breakPoint => $size): ?>
                                    <?php if (intval($breakPoint) > 0): ?>
                                        <source media="(min-width:<?= $breakPoint ?>px)" srcset="<?= $this->Frontend->resize($image->path, $size[0], $size[1]) ?>">
                                    <?php else: ?>
                                        <img src="<?= $this->Frontend->resize($image->path, $size[0], $size[1]) ?>" alt="<?= h($image->title) ?>" >
                                    <?php endif; ?>   
                                <?php endforeach; ?>
                            </picture> 
                        <?php endif; ?>
                        
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="slider__pagination" data-slider-pagination></div>
    <span class="slider__arrow slider__arrow--prev" data-slider-arrow-prev></span>
    <span class="slider__arrow slider__arrow--next" data-slider-arrow-next></span>
</div>