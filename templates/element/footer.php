<footer class="footer">

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

    <div class="footer__social">
        <?= $this->cell('Menu.Menu', [7]) ?>
    </div>
    <div class="footer__credits">
        <a href="https://www.webmotion.it" title="Web Agency Verona">Credits</a>
    </div>
</footer>
