<?php
$this->assign('bodyClass', 'body--static-header');
$this->assign('mainClass', 'main--no-padding');

$extraClass = $extraClass ?? null;
?>


<div class="article <?php echo $extraClass; ?>">
    <h1 class="article__title"><?= $title ?></h1>

    <div class="article__text">
        <?= $text ?>
    </div>
</div>
