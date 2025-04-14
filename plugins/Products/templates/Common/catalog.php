<?php
$this->assign('bodyClass', 'body--catalog')
?>

<div class="catalog">
    <div class="catalog__sidebar">
        <?= $this->element('Products.category-menu', ['current' => $currentCategory ?? null]) ?>
    </div>
    <div class="catalog__main">
        <?=
        $this->fetch('content');
        ?>
    </div>
</div>
