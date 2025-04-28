<?php 
    $this->assign('headerClass', 'header--invert');
?>

<div class="bg-light pt-h blue sostenibilità pb-f">
    <div class="container-l m-auto">
        <h1 class="title-primary mb-title fadeFromLeft" data-animated>
            Cos'è la sostenibilità?
        </h1>
        <div>
            <?= $this->element('img-text', [
                'img' => 'img/img3.jpg',
                'text' => "è un atteggiamento globale che coniuga la crescita dello stile di vita in accordo con l’ambiente che ci circonda, tramite il riciclo e il riutilizzo delle materie prime che il nostro pianeta ci offre. La depurazione dell’acqua che i nostri clienti adottano nelle proprie aziende è il loro gesto verso un futuro sostenibile. La scelta di materiali ecocompatibili è il nostro.",
                'extraClass' => '',
            ]); ?>
            <?= $this->element('img-text', [
                'video' => 'img/video/video2.mp4',
                'text' => "La TC Turrini Claudio con orgoglio fondare le proprie radici aziendali nel settore della carpenteria e della lavorazione dei metalli, perché nessuno meglio di chi ha lavorato direttamente la materia prima da oltre trent’anni ne conosce proprietà e qualità.",
                'extraClass' => 'invert',
            ]); ?>
        </div>
        <?= $this->element('box-swiper', [
            'title' => "I vantaggi dell'acciaio inossidabile",
            'texts' => [
                "L'acciaio inox è un materiale riciclabile al 100%, resistente alla corrosione, durevole e igienico, e gli impatti ambientali derivanti dal suo utilizzo sono quasi inesistenti. L’acciaio inossidabile inoltre ha un primato ambientale invidiabile: economizza l’utilizzo dell’energia primaria; risparmia le risorse ambientali non rinnovabili; riduce gli scarti.",
                "L'acciaio inox è un materiale riciclabile al 100%, resistente alla corrosione, durevole e igienico, e gli impatti ambientali derivanti dal suo utilizzo sono quasi inesistenti. L’acciaio inossidabile inoltre ha un primato ambientale invidiabile: economizza l’utilizzo dell’energia primaria; risparmia le risorse ambientali non rinnovabili; riduce gli scarti.",
                "L'acciaio inox è un materiale riciclabile al 100%, resistente alla corrosione, durevole e igienico, e gli impatti ambientali derivanti dal suo utilizzo sono quasi inesistenti. L’acciaio inossidabile inoltre ha un primato ambientale invidiabile: economizza l’utilizzo dell’energia primaria; risparmia le risorse ambientali non rinnovabili; riduce gli scarti.",
                "L'acciaio inox è un materiale riciclabile al 100%, resistente alla corrosione, durevole e igienico, e gli impatti ambientali derivanti dal suo utilizzo sono quasi inesistenti. L’acciaio inossidabile inoltre ha un primato ambientale invidiabile: economizza l’utilizzo dell’energia primaria; risparmia le risorse ambientali non rinnovabili; riduce gli scarti.",
            ]
        ]); ?>
    </div>
</div>