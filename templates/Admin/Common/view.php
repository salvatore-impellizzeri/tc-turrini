<?php
$this->extend('/Admin/Common/default');

$controlsDefaults = [
    'tableControls' => false,
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'plugin name'),
            'url' => ['action' => 'index']
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
];
$controlsSettings = array_merge($controlsDefaults, $controlsSettings ?? []);
?>

<div class="content__controls">
    <?php echo $this->element('admin/controls', ['options' => $controlsSettings]) ?>
</div>

<div class="content__main" id="app">
    <?= $this->fetch('content') ?>
</div>
