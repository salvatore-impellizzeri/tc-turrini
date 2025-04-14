<?php
$statusBarDefaults = [
    'pathway' => __dx($po, 'admin', 'plugin name'),
    'icon' => __dx($po, 'admin', 'plugin icon'),
    'search' => false
];
$statusBarSettings = array_merge($statusBarDefaults, $statusBarSettings ?? []);
?>


<div class="content__top">
    <?= $this->element('admin/activity-bar') ?>
    <?= $this->element('admin/status-bar', ['options' => $statusBarSettings]) ?>
</div>

<?= $this->fetch('content') ?>
