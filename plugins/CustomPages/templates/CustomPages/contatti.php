<?php 
    $this->assign('footerClass', 'footer--contacts');
?>

<div class="contacts pt-h">
    <div class="container-l m-auto">
        <div class="footer__cols">
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
            <div class="footer__logos">
                <img src="img/certificate1.png" alt="UKAS">
                <img src="img/certificate2.png" alt="SINCERT">
            </div>
        </div>
        <div class="contacts__form">
            <?= $this->element('Contacts.contact-form', ['id' => 1]) ?>
        </div>
    </div>
</div>
