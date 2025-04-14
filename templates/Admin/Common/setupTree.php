<?php
$this->extend('/Admin/Common/setup');

$controlsDefaults = [
    'tableControls' => false,
    'actions' => [
        [
            'title' => __d('admin', 'add action'),
            'icon' => 'add_circle_outline',
            'url' => ['action' => 'add']
        ]
    ],
];
$controlsSettings = array_merge($controlsDefaults, $controlsSettings ?? []);
?>

<?= $this->element('admin/controls', ['options' => $controlsSettings]) ?>

<?= $this->fetch('content') ?>

<?php if (empty($noScript)) echo $this->element('admin/tree-scripts') ?>
