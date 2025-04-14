<?php
$this->extend('/Admin/Common/default');

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

<div class="content__wrapper" v-bind:class="[preloading ? '' : 'loaded']">
    <div class="content__controls">
        <?php echo $this->element('admin/controls', ['options' => $controlsSettings]) ?>
    </div>

    <div class="content__main" id="app">
        <div class="box">
            <?= $this->fetch('content') ?>

            <?php if (empty($noScript)) echo $this->Table->paginator() ?>
        </div>
    </div>
</div>


<?php if (empty($noScript)) echo $this->element('admin/table-scripts', [
    'conditions' => $conditions ?? null
]) ?>
