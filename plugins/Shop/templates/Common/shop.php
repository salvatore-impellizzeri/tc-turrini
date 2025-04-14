<div class="shop">
    <div class="shop__sidebar">
        sidebar
    </div>
    <div class="shop__main">
        <div class="shop__grid" data-shop-products>
            <?= $this->fetch('content'); ?>
        </div>
        <?= $this->element('Shop.shop-pagination', ['total' => $productsCount, 'productsPerPage' => $productsPerPage]); ?>
    </div>
</div>
