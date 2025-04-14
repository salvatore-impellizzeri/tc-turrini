<?php
$this->extend('/Admin/Common/default');

$statusBarDefaults = [];
$this->set('statusBarSettings', array_merge($statusBarDefaults, $statusBarSettings ?? []));

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

<div class="content__wrapper loaded">
    <div class="content__controls">
        <?php echo $this->element('admin/controls', ['options' => $controlsSettings]) ?>
    </div>

    <div class="content__main" id="app">
        <?= $this->fetch('content') ?>
    </div>
</div>

<?php if (empty($noScript)) echo $this->element('admin/tree-scripts') ?>
