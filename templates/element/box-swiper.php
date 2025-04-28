<div class="container-l m-auto box-swiper bg-box font-69 fadeFromBottom" data-animated>
    <h2 class="font-69 fadeFromLeft" data-animated>
        <?= $title ?>
    </h2>

    <!-- Paginazione -->
    <div class="swiper-pagination font-14 fadeFromTopButton" data-animated></div>
    <hr class="hr hr--blue borderWidth" data-animated>

    <!-- Swiper -->
    <div class="swiper swiper-text fadeFromRight" data-animated>
        
        <div class="swiper-wrapper fw-light font-18">
            <?php foreach ($texts as $text): ?>
                <div class="swiper-slide"><?= $text ?></div>
            <?php endforeach; ?>
        </div>

        <!-- Bottoni -->
        <div data-swiper-button="text">
            <div class="swiper-button-prev button button--primary"></div>
            <div class="swiper-button-next button button--primary"></div>
        </div>
    </div>
</div>
