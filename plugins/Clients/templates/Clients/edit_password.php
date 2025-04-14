<?php
$this->extend('customer_area');
$this->assign('currentClientMenuId', 'dati');
?>


<h1 class="account__title"><?= __d('clients', 'edit password') ?></h1>

<?php
echo $this->Form->create($client, ['autocomplete' => 'off', 'class' => 'account__form']);

/*echo $this->Form->control('current_password', [
    'label' => __d('clients', 'current password'),
    'type' => 'password',
    'templateVars' => [
        'extraClass' => 'input--labelled'
    ]
]);*/

echo $this->Form->control('password', [
    'label' => __d('clients', 'new password'),
    'autocomplete' => 'new-password',
	'value' => '',
    'templateVars' => [
        'extraClass' => 'input--labelled'
    ]
]);


echo $this->Form->button(__d('clients', 'save'), ['class' => 'button button--dark']);
echo $this->Form->end();
?>
