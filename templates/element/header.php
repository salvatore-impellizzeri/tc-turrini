<?php
use Cake\Core\Configure;

$extraClass = $extraClass ?? '';
$homeLink = ACTIVE_LANGUAGE == DEFAULT_LANGUAGE ? '/' : '/'.ACTIVE_LANGUAGE.'/';
$languages = Configure::read('Setup.languages');
?>

<header class="header <?= $extraClass ?? '' ?>">
    <a href="<?= $homeLink ?>" class="header__logo">
        <?= $this->Frontend->svg('logo.svg') ?>
    </a>
    
    <!-- DA AGGIUNGERE IMMAGINE E BOTTONE PER OGNI ITEM, 
    IL BOTTONE E' UN ELEMENT (CTA) E LO STILE DELLE IMMAGINI 
    E DEL CONTAINER LO TROVI NEL FILE submenu-progetti.less -->

    <nav class="header__menu">
        <?= $this->cell('Menu.Menu', [1]) ?>
    </nav>

    <?php if (!empty($languages)): ?>
        <div class="header__languages">
            <?= $this->element('languages'); ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($hamburgerMenu)): ?>
        <div class="header__hamburger-mobile">
            <?php echo $this->element('hamburger'); ?>
        </div>
    <?php endif; ?>

    
</header>
