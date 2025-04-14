<?php 
$this->extend('/Admin/Common/edit'); 
$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'attribute groups'),
            'url' => ['action' => 'index']
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
]);
?>

<?php echo $this->Form->create($item, ['type' => 'file', 'id' => 'superForm']); ?>
    <fieldset class="input-group">
        <legend class="input-group__info">
            Generali        
        </legend>
        <?php
        echo $this->Form->control('attribute_type_id', ['label' => __dx($po, 'admin', 'attribute_type_id'), 'extraClass' => 'span-6']);
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-6']);
        ?>
    </fieldset>

    <?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
