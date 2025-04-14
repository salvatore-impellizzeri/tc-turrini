<?php
if (empty($item->shop_product)) return;
$image = !empty($item->preview) ? $item->preview : $item->shop_product->preview;
$excerpt = !empty($item->excerpt) ? $item->excerpt : $item->shop_product->excerpt;
?>

<a href="<?= $this->Frontend->url('/shop-product-variants/view/'.$item->id) ?>" class="shop-product-preview">
    <div class="shop-product-preview__image">
        <?php if (!empty($image)): ?>
            <img loading="lazy" src="<?= $this->Frontend->resize($image->path, 600, 600) ?>" alt="<?= $image->title ?>">
        <?php endif; ?>
    </div>
    <div class="shop-product-preview__content">
        <span class="shop-product-preview__brand"><?= $item->shop_product->brand->title ?? '' ?></span>
        <h3 class="shop-product-preview__title"><?= $item->shop_product->title ?></h3>
        <div class="shop-product-preview__price">
            <?php if (!empty($item->display_discounted_price)): ?>
                <del><?= $item->display_price ?></del>
            <?php endif; ?>
            <strong><?= $this->Frontend->formatPrice($item->display_discounted_price ?? $item->display_price) ?></strong>
        </div>
    </div>
</a>
