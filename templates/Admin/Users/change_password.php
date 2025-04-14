<?php
$this->extend('/Admin/Common/setupEdit');
$this->set('currentMenuId', 'Users');

$this->set('controlsSettings', [
    'pathway' => [
        [
            'title' => __d('admin', 'settings users'),
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
        echo $this->Form->control('user_password', ['label' => __d('admin', 'your password'), 'extraClass' => 'span-6', 'type' => 'password']);
        echo $this->Form->control('password', ['label' => __d('admin', 'new password'), 'extraClass' => 'span-6']);
        ?>
    </fieldset>
    <?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>
