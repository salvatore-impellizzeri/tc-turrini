<?php
$this->extend('customer_area');
$this->assign('currentClientMenuId', 'dati');
?>


<h1 class="account__title"><?= __d('clients', 'edit data') ?></h1>

<?php
echo $this->Form->create($client, ['class' => 'account__form']);
/*echo $this->Form->control('is_company', [
    'type' => 'radio',
    'label' => false,
    'options' => [
        0 => __d('clients', 'private'),
        1 => __d('clients', 'company')
    ]
]);*/

echo $this->Form->control('fullname', [
    'label' => __d('clients', 'fullname'),
    'templateVars' => [
        'extraClass' => 'input--labelled'
    ]
]);

echo $this->Form->button(__d('clients', 'save'), ['class' => 'button button--dark']);
echo $this->Form->end();
?>
