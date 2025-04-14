<?php
$this->extend('/Admin/Common/setupEdit');
$this->set('currentMenuId', 'BackendMenu');

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
        echo $this->Form->control('title', ['label' => __d('admin', 'title'), 'extraClass' => 'span-12']);
        ?>
    </fieldset>
    <?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>
