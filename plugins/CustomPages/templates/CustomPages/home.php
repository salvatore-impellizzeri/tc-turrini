<?php 
    $this->assign('headerClass', 'home__header');
?>
<div class="home">
    
    <!-- VIDEO -->
    <div class="container-fluid home__video">
        <video autoplay loop muted playsinline>
            <source src="img/video/video1.mp4" type="video/mp4">
            Il tuo browser non supporta il video.
        </video>
        <div class="home__video__content container-l m-auto">
            <div class="home__video__title">
                <h1 class="title-primary container-title">
                    Tecnologia e ricerca per <span class="blue">soluzioni innovative</span>
                </h1>
            </div>
            <div class="home__video__text">
                <p class="font-23">
                    Sistemi di aspirazione delle polveri e impianti 
                    di depurazione delle acque reflue: tecnologia all'avanguardia per un mondo più sostenibile.
                </p>
                <div class="home__video__buttons">
                    <?= $this->element('cta', [
                        'extraClass' => 'button--primary',
                        'url' => '#',
                        'label' => 'Aspirazione polveri',
                        'icon' => 'icons/button.svg'
                    ]); ?>
                    <?= $this->element('cta', [
                        'extraClass' => 'button--primary',
                        'url' => '#',
                        'label' => 'Depurazione acque',
                        'icon' => 'icons/button.svg'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- SETTORI DI IMPIEGO -->
    <div class="bg-light home__settori blue">
        <div class="container-l m-auto home__settori__container">
            <div class="home__settori__content">
                <div class="home__settori__title font-23">
                    Settori d'impiego
                </div>
                <div>
                    <ul class="font-86">
                        <li>Cemento</li>
                        <li>Marmi, graniti e pietre</li>
                        <li>Vetro e derivati</li>
                        <li>Ceramica</li>
                    </ul>
                </div>
            </div>
            <div class="home__settori__img">
                <img src="img/img1.jpg" alt="Immagine">
            </div>
        </div>
    </div>

    <!-- PRODOTTI -->
    <div class="bg-light home__prodotti">
        <?= $this->element('prodotti'); ?>
    </div>

    <!-- AZIENDA -->
    <div class="bg-light azienda">
        <div class="container-l bg-box blue m-auto azienda__content">
            <div class="azienda__text">
                <div class="font-14">
                    La nostra azienda
                </div>
                <hr class="hr hr--blue">
                <p class="font-28">
                    La T.C. Turrini Claudio eccelle per la qualità dei materiali, la cura nella progettazione e la produzione interna completa, diventando un punto di riferimento con standard ineguagliati nel mercato italiano e internazionale.
                </p>
                <?= $this->element('cta', [
                    'label' => "Scopri l'azienda",
                    'extraClass' => 'button--primary',
                    'icon' => 'icons/button.svg',
                    'url' => '#',
                    'labelClass' => 'blue'
                ]); ?>
            </div>
            <div class="azienda__img">
                <img src="img/img2.jpg" alt="Immagine Azienda">
            </div>
        </div>
    </div>

    <!-- FILOSOFIA AZIENDALE -->
    <div class="container-fluid home__filosofia">
        <div class="home__filosofia__content">
            <img src="img/img3.jpg" alt="Background">
            <div class="home__filosofia__text">
                <p class="font-32 fw-medium">
                Un’impegno per l’ambiente: l’azienda si impegna nella progettazione di soluzioni sostenibili, riducendo l’impatto ambientale 
                e promuovendo l’efficienza energetica.
                </p>
            </div>
            <?= $this->element('cta', [
                'label' => "La nostra filosofia aziendale",
                'extraClass' => 'button--secondary',
                'icon' => 'icons/button.svg',
                'url' => '#'
            ]); ?>
        </div>
    </div>
</div>