<?php
$this->extend('/Admin/Common/setupEdit');

$this->set('controlsSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'plugin name'),
            'url' => ['action' => 'index']
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
]);
?>


<?= $this->Form->create($item) ?>

<fieldset class="input-group">
    <?php
    echo $this->Form->control('title', ['label' => __dx($po, 'admin', 'title'), 'extraClass' => 'span-4']);
    echo $this->Form->control('layout', ['label' => __dx($po, 'admin', 'layout'), 'extraClass' => 'span-4']);
    echo $this->Form->control('plugin', ['label' => __dx($po, 'admin', 'plugin'), 'extraClass' => 'span-3']);
    echo $this->Form->control('published', ['type' => 'checkbox', 'label' => __dx($po, 'admin', 'plugin'), 'extraClass' => 'span-1']);
    ?>

    <?php
    echo $this->element('admin/uploader/icon', [
        'scope' => 'icon'
    ]);
    ?>
</fieldset>
<?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>
