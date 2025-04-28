<a href="<?= $this->Frontend->url($url); ?>" class="product-preview borderLeftAnimation <?= $extraClass ?? '' ?>" data-animated>
    <div class="product-preview__img fadeFromBottomImg" data-animated>
        <img src="<?= $img ?>" alt="Prodotto">
    </div>
    <div class="product-preview__wrapper font-28 borderWidth" data-animated>
        <div class="product-preview__label">
            <div class="productPreviewCta product-preview__label__elements" data-animated>
                <label>
                    <?= $label ?>
                </label>
                <div>
                    <?= $this->element('cta', [
                        'extraClass' => 'button--secondary',
                        'icon' => 'icons/button.svg'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</a>