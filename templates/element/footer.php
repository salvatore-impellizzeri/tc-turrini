<footer class="footer <?= $extraClass ?>">
    <div class="container-l m-auto">
        <div class="font-23 footer__more-info">
            Vuoi avere maggiori informazioni?
        </div>
        <a href="<?= $this->Frontend->url('/custom-pages/view/11'); ?>">
            <div class="footer__contact">
                <h1 class="title-primary">
                    Contattaci
                </h1>
                <?= $this->element('cta', [
                    'extraClass' => 'button--secondary',
                    'url' => '#',
                    'icon' => 'icons/button.svg'
                ]); ?>
            </div>
        </a>
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
        <div class="footer__legal">
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
        <!--     
        <div class="footer__credits">
            <a href="https://www.webmotion.it" title="Web Agency Verona">Credits</a>
        </div> -->
    </div>
</footer>
