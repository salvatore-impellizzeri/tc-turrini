<?php
$this->extend('/Admin/Common/edit');

$this->set('statusBarSettings', [
    'icon' => 'person',
    'pathway' => [
        [
            'title' => $loggedUser['username']
        ],
        [
            'title' => __d('admin', 'set new password'),
        ]
    ]
])
?>


<?= $this->Form->create($item) ?>
    <fieldset class="input-group">
        <?php
        echo $this->Form->control('user_password', ['label' => __d('admin', 'your password'), 'extraClass' => 'span-6', 'type' => 'password']);
        echo $this->Form->control('password', ['label' => __d('admin', 'new personal password'), 'extraClass' => 'span-6']);
        ?>
    </fieldset>
    <?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>
