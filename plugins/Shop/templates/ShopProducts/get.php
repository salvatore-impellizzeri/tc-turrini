<?php 
if (!empty($products)) {
    foreach ($products as $product) {
        echo $this->element('Shop.shop-product-preview', ['item' => $product]);
    }
}
?>