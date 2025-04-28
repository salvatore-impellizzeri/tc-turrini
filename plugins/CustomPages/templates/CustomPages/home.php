<div class="home">
    
    <!-- VIDEO -->
    <div class="container-fluid home__video">
        <video autoplay loop muted playsinline>
            <source src="img/video/video1.mp4" type="video/mp4">
            Il tuo browser non supporta il video.
        </video>
        <div class="home__video__content container-l m-auto">
            <div class="home__video__title borderAnimation" data-animated>
                <h1 class="title-primary container-title fadeFromLeft" data-animated>
                    Tecnologia e ricerca per <span class="blue">soluzioni innovative</span>
                </h1>
            </div>
            <div class="home__video__text">
                <p class="font-23 videoTextHome" data-animated>
                    Sistemi di aspirazione delle polveri e impianti 
                    di depurazione delle acque reflue: tecnologia all'avanguardia per un mondo più sostenibile.
                </p>
                <div class="home__video__buttons videoTextHome" data-animated>
                    <?= $this->element('cta', [
                        'ctaClass' => 'cta--primary',
                        'extraClass' => 'button--primary',
                        'url' => '/custom-pages/view/7',
                        'label' => 'Aspirazione polveri',
                        'icon' => 'icons/button.svg'
                    ]); ?>
                    <?= $this->element('cta', [
                        'ctaClass' => 'cta--primary',
                        'extraClass' => 'button--primary',
                        'url' => '/custom-pages/view/8',
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
                <div class="home__settori__title font-23 fadeFromLeft" data-animated>
                    Settori d'impiego
                </div>
                <div class="borderLeftAnimation" data-animated>
                    <ul class="font-86">
                        <li class="fadeFromLeft-d" data-animated>Cemento</li>
                        <li class="fadeFromLeft-d" data-animated>Marmi, graniti e pietre</li>
                        <li class="fadeFromLeft-d" data-animated>Vetro e derivati</li>
                        <li class="fadeFromLeft-d" data-animated>Ceramica</li>
                    </ul>
                </div>
            </div>
            <div class="home__settori__img fadeFromRight-d" data-animated>
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
        <div class="container-l bg-box fadeFromBottom blue m-auto azienda__content" data-animated>
            <div class="azienda__text">
                <div class="font-14">
                    La nostra azienda
                </div>
                <hr class="hr hr--blue">
                <p class="font-28">
                    La T.C. Turrini Claudio eccelle per la qualità dei materiali, la cura nella progettazione e la produzione interna completa, diventando un punto di riferimento con standard ineguagliati nel mercato italiano e internazionale.
                </p>
                <?= $this->element('cta', [
                    'ctaClass' => 'cta--primary',
                    'label' => "Scopri l'azienda",
                    'extraClass' => 'button--primary',
                    'icon' => 'icons/button.svg',
                    'url' => '/custom-pages/view/3',
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
            <div class="home__filosofia__text borderLeftLightAnimation" data-animated>
                <p class="font-32 fw-medium fadeFromLeft-d" data-animated>
                Un’impegno per l’ambiente: l’azienda si impegna nella progettazione di soluzioni sostenibili, riducendo l’impatto ambientale 
                e promuovendo l’efficienza energetica.
                </p>
            </div>
            <div class="fadeFromTopButton" data-animated>
                <?= $this->element('cta', [
                    'ctaClass' => 'cta--secondary',
                    'label' => "La nostra filosofia aziendale",
                    'extraClass' => 'button--secondary',
                    'icon' => 'icons/button.svg',
                    'url' => '/custom-pages/view/4'
                ]); ?>
            </div>
        </div>
    </div>
</div>