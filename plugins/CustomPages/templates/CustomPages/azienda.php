  <div class="bg-light">
    <div class="container-fluid container-video">
        <video autoplay loop muted playsinline>
            <source src="img/video/video1.mp4" type="video/mp4">
            Il tuo browser non supporta il video.
        </video>
    </div>
    <div class="container-l bg-box m-auto container-on-video">
        <div class="container-m m-auto">
            <h1 class="font-55 mb-2em fadeFromBottomS" data-animated>
                La T.C. Turrini Claudio dal 1994 produce sistemi per l'aspirazione delle polveri e impianti di depurazione delle acque reflue provenienti dalle lavorazioni di marmo, granito, vetro, cemento e ceramica. 
            </h1>
            <?= $this->element('img-text', [
                'text' => "La qualità dei materiali, l'attenzione in fase di progettazione e la capacità di produrre i propri macchinari dalla lamiera piana fino al prodotto ultimato all'interno dei propri due stabilimenti, sono i punti di forza che hanno portato la T.C. Turrini Claudio a raggiungere standard qualitativi ineguagliati nel settore, rendendola punto di riferimento nel mercato internazionale. Grazie ad uno studio tecnico interno che sfrutta l’utilizzo di software di progettazione tridimensionale di ultima generazione e ad una costante ricerca nel campo dell’automazione industriale, la T.C. Turrini Claudio si proietta verso le nuove sfide con la solidità dell’esperienza e l’entusiasmo delle idee.",
                'img' => 'img/img6.jpg',
                'extraClass' => 'img-text--smaller',
            ]); ?>
            <?= $this->element('list', [
                'title' => 'La storia aziendale',
                'items' => [
                    [
                        'big' => '1980',
                        'text' => "La T.C. Turrini Claudio nasce all’inizio degli anni ‘80 come carpenteria metallica, fondata dall’attuale titolare Claudio Turrini, all’interno del crescente polo industriale di Villafranca di Verona.",
                    ],
                    [
                        'big' => '1994',
                        'text' => "A partire dal 1994 l’azienda si evolve iniziando la produzione di sistemi per l’aspirazione delle polveri e di impianti di depurazione delle acque reflue provenienti dalle lavorazioni di marmo, granito, vetro, cemento e ceramica.",
                    ],
                    [
                        'big' => 'xxxx',
                        'text' => "La T.C. Turrini Claudio nasce all’inizio degli anni ‘80 come carpenteria metallica, fondata dall’attuale titolare Claudio Turrini, all’interno del crescente polo industriale di Villafranca di Verona.",
                    ],
                ],
            ]); ?>
            <div class="swiper-container m-auto fadeFromTop" data-animated data-swiper-button="single-slide">
                <div class="swiper swiper-single-slide">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="img/img7.jpg" alt="Immagine Slider Azienda">
                        </div>
                        <div class="swiper-slide">
                            <img src="img/img7.jpg" alt="Immagine Slider Azienda"> 
                        </div>
                        <div class="swiper-slide">
                            <img src="img/img7.jpg" alt="Immagine Slider Azienda">
                        </div>
                    </div>
                </div>
                <div class="swiper-button-prev button button--primary on-edge-left"></div>
                <div class="swiper-button-next button button--primary on-edge-right"></div>   
            </div>
        </div>
    </div>
    <div class="container-l m-auto">
        <?= $this->element('prodotti', [
            'title' => "Soluzioni per la qualità dell'aria e dell'acqua"
        ]); ?>
    </div>
</div>