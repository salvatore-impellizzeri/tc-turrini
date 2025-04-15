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
    <div class="bg-light home__settori">
        <div class="container-l m-auto home__settori__container">
            <div class="home__settori__content">
                <div class="home__settori__title font-23 blue">
                    Settori d'impiego
                </div>
                <div>
                    <ul class="font-86 blue">
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
</div>