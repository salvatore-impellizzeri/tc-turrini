<?php
$totalPages = floor($total / $productsPerPage) + 1;
if ($totalPages < 2) return;
?>
<div class="shop-pagination" data-shop-pagination>
    <?php for($i = 1; $i <= $totalPages; $i++): ?>
        <a class="shop-pagination__page" data-page="<?= $i ?>" href="<?= $this->Frontend->url('/shop-products/index?page='.$i) ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>