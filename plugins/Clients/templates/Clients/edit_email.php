<?php
$this->extend('customer_area');
$this->assign('currentClientMenuId', 'dati');
?>


<h1 class="account__title"><?= __d('clients', 'edit email') ?></h1>

<?php
echo $this->Form->create($client, ['class' => 'account__form']);

echo $this->Form->control('email', [
    'label' => __d('clients', 'email'),
    'templateVars' => [
        'extraClass' => 'input--labelled'
    ]
]);

echo $this->Form->button(__d('clients', 'save'), ['class' => 'button button--dark']);
echo $this->Form->end();
?>
