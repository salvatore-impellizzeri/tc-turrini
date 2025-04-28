<?php 
    $this->assign('footerClass', 'footer--contacts');
?>

<div class="contacts pt-h">
    <div class="container-l m-auto contacts__upper">
        <div class="contacts__info borderHeight" data-animated>
            <div class="fadeFromRightDelay contacts__info__wrapper" data-animated>
                <div class="footer__contact">
                    <h1 class="title-primary">
                        Contatti
                    </h1>
                </div>
                <div class="footer__info">
                    <div class="footer__info__item font-23">
                        <label class="font-14">Indirizzo</label>
                        <?= $this->element('snippet', ['id' => 1])?>
                    </div>
                    <div class="footer__info__item font-23">
                        <label class="font-14">Telefono</label>
                        <?= $this->element('snippet', ['id' => 15])?>
                    </div>
                    <div class="footer__info__item font-23">
                        <label class="font-14">Mail</label>
                        <?= $this->element('snippet', ['id' => 16])?>
                    </div>
                </div>
            </div>
        </div>
        <div class="contacts__form m-auto fadeFromLeftDelay" data-animated>
            <h2 class="font-44">
                Scrivici per maggiori informazioni
            </h2>
            <?= $this->element('Contacts.contact-form', ['id' => 1]) ?>
        </div>
    </div>
    <div class="footer__legal container-l m-auto">
            <div>
                <?= $this->element('snippet', ['id' => 18])?>
            </div>
            <div class="text-center">
                <?= $this->element('snippet', ['id' => 17])?>
            </div>
            <ul class="menu">
                <li><?= $this->Frontend->seolink(__d('policies', 'Privacy policy'), '/policies/view/1'); ?></li>
                <li>-</li>
                <li><?= $this->Frontend->seolink(__d('policies', 'Cookie policy'), '/policies/view/2'); ?></li>
                <li>-</li>
                <li><span id="cookie_reload"><?php echo __d('policies', 'manage cookies'); ?></span></li>
            </ul>
        </div>
</div>
