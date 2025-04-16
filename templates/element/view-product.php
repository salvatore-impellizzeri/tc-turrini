<div class="view-product pt-h">
    <div class="container-l m-auto">
        <div class="font-14">
            Prodotti > <?= $section ?>
        </div>
        <h1 class="title-primary">
            <?= $product ?>
        </h1>
        <div class="view-product__items">
            <?php foreach ($items as $item) { ?>
                <div class="view-product__item">
                    <div class="view-product__img">
                        <img src="<?= $item['img'] ?>" alt="<?= $item['label'] ?>">
                    </div>
                    <div class="view-product__text">
                        <h3 class="font-35"><?= $item['label'] ?></h3>
                        <p class="font-18"><?= $item['text'] ?></p>
                        <div class="font-14 view-product__buttons">
                            <button>
                                <?= $this->Frontend->svg('icons/download.svg'); ?>
                                <span>Scarica la scheda tecnica</span>
                            </button>
                            <button>
                                <?= $this->Frontend->svg('icons/more.svg') ?>
                                <span>Maggiori dettagli</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>