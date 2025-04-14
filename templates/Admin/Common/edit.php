<?php
$this->extend('/Admin/Common/default');

$statusBarDefaults = [
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

$this->set('statusBarSettings', array_merge($statusBarDefaults, $statusBarSettings ?? []));

$controlsDefaults = [
    'tableControls' => false,
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
