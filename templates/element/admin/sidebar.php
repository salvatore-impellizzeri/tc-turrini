<div class="sidebar">
    <a href="/admin" class="sidebar__logo">
        <?=  $this->Html->image('/admin-assets/img/logo.svg') ?>
    </a>
    <?= $this->cell('BackendMenu.Menu', [1, $loggedUser['group_id']]) ?>

</div>
