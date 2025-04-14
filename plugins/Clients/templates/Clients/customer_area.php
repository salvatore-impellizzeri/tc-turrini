<?php
$this->assign('bodyClass', 'body--static-header');
$this->assign('mainClass', 'main--no-padding main--full');
?>

<div class="account <?= $this->Fetch('accountClass') ?>">
    <div class="account__sidebar">
        <h4 class="account__sidebar__title"><?= __d('clients', 'account') ?></h4>

        <?= $this->element('Clients.account-menu', ['current' => $this->fetch('currentClientMenuId')]) ?>
    </div>

    <div class="account__main">
        <?= $this->fetch('content') ?>

    </div>
</div>
