<div class="prodotti p-prodotti">
    <div class="container-m m-auto">
        <?php if (isset($title)): ?>
            <h2 class="font-44 prodotti__title">
                <?= $title ?>
            </h2>
        <?php endif ?>
        <?= $this->element('prodotto', [
            'title' => 'Aspirazione polveri',
            'text' => 'Sistemi per industrie e ambienti produttivi',
        ]); ?>
        <hr class="hr hr--blue">
        <?= $this->element('prodotto', [
            'title' => 'Impianti depurazione acque',
            'text' => 'Soluzioni innovative per il trattamento delle acque reflue',
        ]); ?>
    </div>
</div>