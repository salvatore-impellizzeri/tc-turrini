<div class="container-l m-auto box-swiper bg-box font-69">
    <h2 class="font-69">
        <?= $title ?>
    </h2>

    <!-- Paginazione -->
    <div class="swiper-pagination font-14"></div>
    <hr class="hr hr--blue">

    <!-- Swiper -->
    <div class="swiper">
        
        <div class="swiper-wrapper fw-light font-18">
            <?php foreach ($texts as $text): ?>
                <div class="swiper-slide"><?= $text ?></div>
            <?php endforeach; ?>
        </div>

        <!-- Bottoni -->
        <div class="box-swiper__buttons">
            <div class="swiper-button-prev button button--primary"></div>
            <div class="swiper-button-next button button--primary"></div>
        </div>
    </div>
</div>
