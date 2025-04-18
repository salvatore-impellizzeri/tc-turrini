<div class="submenu-prodotti bg-light">
    <?php 
        $prodotti = [
            [
                'name' => 'Aspirazione polveri',
                'img' => 'img/prodotto.png'
            ],
            [
                'name' => 'Depurazione acque',
                'img' => 'img/prodotto.png'
            ],
        ];
    ?>
    <?php foreach ($prodotti as $prodotto) { ?>
        <div class="submenu-prodotti__prodotto">
            <h1 class="fw-medium">
                <?= $prodotto['name'] ?>
            </h1>
            <div class="submenu-prodotti__lower">
                <?= $this->element('cta', [
                    'extraClass' => 'button--primary',
                    'label' => '',
                    'url' => '#',
                    'icon' => 'icons/button.svg'
                ]); ?>
                <div class="submenu-prodotti__img">
                    <img src="<?= $prodotto['img'] ?>" alt="<?= $prodotto['name'] ?>">
                </div>
            </div>
        </div>
    <?php } ?>
</div>