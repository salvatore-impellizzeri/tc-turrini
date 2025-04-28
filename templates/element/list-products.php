<div class="list-products">
    <div class="list-products__background">
        <img src="<?= $background ?>" alt="background">
        <h1 class="title-primary fadeFromLeft" data-animated>
            <?= $title ?>
        </h1>     
    </div>  
    
    <div class="container-l m-auto">
        <div class="list-products__items">
        <?php
            foreach ($items as $index => $item) { ?>
            <div class="list-products__item">
                <?= $this->element('product-preview', [
                    'label' => $item['label'],
                    'img' => $item['img'],
                    'url' => $item['url'],
                ]); ?>
            </div>
        <?php } ?>
        </div>
    </div>
</div>