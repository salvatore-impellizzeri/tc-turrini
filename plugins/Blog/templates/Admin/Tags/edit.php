<?php
$this->extend('/Admin/Common/edit');

$this->set('statusBarSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'tags'),
            'url' => ['action' => 'index']
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
]);
?>


<?= $this->Form->create($item, ['novalidate' => true, 'id' => 'superForm']) ?>
<fieldset class="input-group">
    <legend class="input-group__info">
        Generali
    </legend>
    <?php
    echo $this->Form->control('title');
    ?>
</fieldset>

<?= $this->element('admin/save'); ?>
<?= $this->Form->end() ?>
