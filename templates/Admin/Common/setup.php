
<?php
$statusBarDefaults = [
    'pathway' => __dx($po, 'admin', 'Impostazioni'),
    'icon' => __dx($po, 'admin', 'settings'),
    'search' => false,
    'pageVar' => 'settings',
];

$statusBarSettings = array_merge($statusBarDefaults, $statusBarSettings ?? []);
?>

<div class="content__top">
    <?= $this->element('admin/activity-bar'); ?>
    <?= $this->element('admin/status-bar', ['options' => $statusBarSettings]) ?>
</div>

<div class="content__wrapper loaded">
    <div class="settings">
        <?= $this->element('admin/settings-menu'); ?>

        <div class="settings__wrapper">
            <?= $this->fetch('content') ?>
        </div>
    </div>

</div>
