<?php 
$this->extend('/Admin/Common/edit'); 

$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'attribute groups'),
            'url' => ['controller' => 'AttributeGroups', 'action' => 'index']
        ],
        [
            'title' => $attributeGroup->title ?? $item->attribute_group->title,
            'url' => ['action' => 'list', $attributeGroup->id ?? $item->attribute_group_id]
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
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-12']);
        echo $this->Form->hidden('attribute_group_id', ['value' => $this->request->getQuery('attribute_group_id') ?? $item->attribute_group_id]);
        ?>
    </fieldset>

    <?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
