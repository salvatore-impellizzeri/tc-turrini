<?php
use Cake\Core\Configure;

$extraClass = $extraClass ?? '';
$homeLink = ACTIVE_LANGUAGE == DEFAULT_LANGUAGE ? '/' : '/'.ACTIVE_LANGUAGE.'/';
$languages = Configure::read('Setup.languages');
?>

<header class="header">
    <a href="<?= $homeLink ?>" class="header__logo">
        <?= $this->Frontend->svg('logo.svg') ?>
    </a>

    <?php if (!empty($showSearch)): ?>
        <div class="header__search">
            <?php echo $this->element('search'); ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($hamburgerMenu)): ?>
        <nav class="header__menu">
            <?= $this->cell('Menu.Menu', [1]) ?>
        </nav>
    <?php else: ?>
        <div class="header__hamburger">
            <?php echo $this->element('hamburger'); ?>
        </div>
        
    <?php endif; ?>


    <?php if (!empty($languages)): ?>
        <div class="header__languages">
            <?= $this->element('languages'); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($cta)): ?>
        <div class="header__cta">
            <?= $this->element('cta', [
                'label' => $cta['label'],
                'icon' => $cta['icon'],
                'url' => $cta['url']
            ]); ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($hamburgerMenu)): ?>
        <div class="header__hamburger-mobile">
            <?php echo $this->element('hamburger'); ?>
        </div>
    <?php endif; ?>

    
</header>
