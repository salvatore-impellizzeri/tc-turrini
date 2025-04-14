<?php 
$this->extend('/Admin/Common/edit'); 
$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'color groups'),
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
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-8']);
        echo $this->Form->control('code', ['label' => __dx($po, 'admin', 'color code'), 'extraClass' => 'span-4', 'type' => 'color', 'default' => '#ff0000']);
        echo $this->Backend->belongsToMany('attributes', $item, [
            'options' => $attributes,
            'label' => __dx($po, 'admin', 'connected colors'),
            'url' => ['controller' => 'Attributes', 'action' => 'colorCheckbox']
        ]);
        ?>
        <?php if ($notConnectedAttributes->count()): ?>
            <div class="input span-12">
                <label>
                    <div class="label-heading"><?= __dx($po, 'admin', 'not connected attributes') ?></div>
                </label>
                <?= implode(', ', $notConnectedAttributes->toArray()) ?>
            </div>
        <?php endif; ?>

    </fieldset>

    <?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
