<a href="<?= $url ?>" class="product-preview">
    <div class="product-preview__img">
        <img src="<?= $img ?>" alt="Prodotto">
    </div>
    <div class="product-preview__label font-28">
        <label class="font-28">
            <?= $label ?>
        </label>
        <?= $this->element('cta', [
            'extraClass' => 'button--secondary',
            'icon' => 'icons/button.svg'
        ]); ?>
    </div>
</a>