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
        echo $this->Form->control('username', ['label' => __d('admin', 'username'), 'extraClass' => 'span-6']);
        echo $this->Form->control('group_id', ['label' => __d('admin', 'users group'), 'options' => $groups, 'extraClass' => 'span-5']);
        echo $this->Form->control('active', ['label' => __d('admin', 'active'), 'type' => 'checkbox', 'default' => true, 'extraClass' => 'span-1']);        
        echo $this->Form->control('email', ['label' => __d('admin', 'email'), 'extraClass' => 'span-6']);
        ?>
    </fieldset>
    <?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>
