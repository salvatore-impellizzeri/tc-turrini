<footer class="footer">
    <div class="container-l m-auto">
        <div class="font-23 footer__more-info">
            Vuoi avere maggiori informazioni?
        </div>
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
        <div class="footer__info">
            <?= $this->element('snippet', ['id' => 1])?>
        </div>
        
        <div class="footer__menu">
            <?= $this->cell('Menu.Menu', [8]) ?>
        </div>
    
        <div class="footer__legal">
            <ul class="menu">
                <li><?= $this->Frontend->seolink(__d('policies', 'Privacy policy'), '/policies/view/1'); ?></li>
                <li><?= $this->Frontend->seolink(__d('policies', 'Cookie policy'), '/policies/view/2'); ?></li>
                <li><span id="cookie_reload"><?php echo __d('policies', 'manage cookies'); ?></span></li>
            </ul>
        </div>
    
        <div class="footer__credits">
            <a href="https://www.webmotion.it" title="Web Agency Verona">Credits</a>
        </div>
    </div>
</footer>
