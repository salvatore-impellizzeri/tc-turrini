<?php if (empty($products->count())): ?>
    <div class="search__empty">
        <?= __d('global', 'no results found') ?>
    </div>
<?php else: ?>
    <?php foreach ($products as $product): ?>

        <a class="search-result" href="<?= $this->Frontend->url('/products/view/'.$product->id) ?>">
            <div class="search-result__image">
                <?php if (!empty($product->preview->path)): ?>
                    <img src="<?= $this->Frontend->resize($product->preview->path, 100) ?>" >
                <?php endif; ?>
            </div>
            <div class="search-result__title">
                <?= $product->title ?>
            </div>
        </a>
    <?php endforeach; ?>

<?php endif; ?>
