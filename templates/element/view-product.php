<div class="view-product pt-h">
    <div class="container-l m-auto">
        <div class="font-14 fadeFromTop" data-animated>
            Prodotti > <?= $section ?>
        </div>
        <h1 class="title-primary fadeFromTop" data-animated>
            <?= $product ?>
        </h1>
        <div class="view-product__items">
            <?php foreach ($items as $item) { ?>
                <div class="view-product__item fadeFromLeft" data-animated>
                    <div class="view-product__img">
                        <img src="<?= $item['img'] ?>" alt="<?= $item['label'] ?>">
                    </div>
                    <div class="view-product__text">
                        <h3 class="font-35"><?= $item['label'] ?></h3>
                        <p class="font-18"><?= $item['visibleText'] ?></p>
                        <div class="view-product__hiddenText">
                            <p class="font-18">
                                <?= $item['hiddenText'] ?>
                            </p>
                        </div>
                        <div class="font-14 view-product__buttons">
                            <button>
                                <?= $this->Frontend->svg('icons/download.svg'); ?>
                                <span>Scarica la scheda tecnica</span>
                            </button>
                            <button data-expand-text>
                                <span class="more-icon"></span>
                                <span class="expand-text">Maggiori dettagli</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>