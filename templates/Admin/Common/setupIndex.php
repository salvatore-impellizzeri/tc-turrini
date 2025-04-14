<?php
$this->extend('/Admin/Common/setup');

$controlsDefaults = [
    'tableControls' => true,
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


<div class="box">
    <?= $this->fetch('content') ?>
    <?php if (empty($noScript)) echo $this->Table->paginator() ?>
</div>


<?php if (empty($noScript)) echo $this->element('admin/table-scripts') ?>
