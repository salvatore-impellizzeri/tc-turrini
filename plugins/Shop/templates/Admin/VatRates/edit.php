<?php
$this->extend('/Admin/Common/edit');
$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'vat rates'),
            'url' => ['action' => 'index']
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
]);
?>
<?php echo $this->Form->create($item); ?>
<fieldset class="input-group">
    <legend class="input-group__info">
        Generali
    </legend>
    <?php
    echo $this->Form->control('value', ['label' => __dx($po, 'admin', 'vat rate value'), 'extraClass' => 'span-10']);
    echo $this->Form->control('is_default', ['label' => __dx($po, 'admin', 'is_default'), 'extraClass' => 'span-2']);
    ?>
</fieldset>

<?php echo $this->element('admin/save');?>

<?= $this->Form->end() ?>
