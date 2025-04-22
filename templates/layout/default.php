<!DOCTYPE html>
<html lang="<?php echo ACTIVE_LANGUAGE ?>">
<?php echo $this->element('head') ?>

<body class="body <?php echo $this->fetch('bodyClass') ?>">

    <?php
    echo $this->element('header', [
        'extraClass' => $this->fetch('headerClass') ?? false,
        'showSearch' => false, //booleano se mostrare la ricerca
        'hamburgerMenu' => true, //boolean se mostrare il menu hamburger o esteso
        'cta' => [
            'label' => __d('global', 'get in touch'),
            'icon' => 'icons/arrow-right.svg',
            'url' => '#'
        ]
    ]);
    ?>


    <main class="main">
        <?php echo $this->fetch('content') ?>
    </main>

    <?php echo $this->element('footer') ?>

	<?php echo $this->element('cookie'); ?>
    <?php // echo $this->element('preload'); ?>
    <?php echo $this->element('mobile-menu'); ?>

    
    <?php echo $this->element('popup', ['showPopup' => !empty($showPopup)]); ?>
    <?php echo $this->element('image-popup', ['showPopup' => !empty($showPopup)]); ?>

    <?php // POPUP NEWSLETTER: per aprirlo mettere link con href #newsletterPopup e assicurarsi che lo snippet sia pubblicato ?>
    <?php echo $this->element('newsletter-popup'); ?>

    <?php echo $this->Flash->render() ?>
</body>


<?php
echo $this->AssetCompress->script('frontend');
echo $this->Html->scriptBlock(sprintf('var csrfToken = %s;', json_encode($this->request->getAttribute('csrfToken'))));
echo $this->Html->scriptBlock(sprintf('var baseUrl = %s;', ACTIVE_LANGUAGE != DEFAULT_LANGUAGE ? "'/" . ACTIVE_LANGUAGE . "'"  : "''"));
echo $this->fetch('scriptBottom');
?>
</html>
