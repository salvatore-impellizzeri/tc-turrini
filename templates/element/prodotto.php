<a href="<?= $this->Frontend->url($url); ?>" class="prodotto">
    <div class="prodotto__img">
        <img src="img/prodotto.png" alt="prodotto">
    </div>
    <div class="blue prodotto__text">
        <h2 class="font-86">
            <?= $title ?>
        </h2>
        <p>
            <?= $text ?>  
        </p>
        <?= $this->element('cta', [
            'extraClass' => 'button--primary',
            'label' => '',
            'icon' => 'icons/button.svg'
        ]); ?>
    </div>
</a>