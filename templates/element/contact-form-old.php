<?php
// backup pre form dinamiche


use Cake\Datasource\FactoryLocator;

$contactsTable = FactoryLocator::get('Table')->get('Contacts.Contacts');
$form = $contactsTable->newEmptyEntity();

echo $this->Form->create($form, array('url' => ['plugin' => 'contacts', 'controller' => 'contacts', 'action' => 'req'], 'class' => 'antispam contact-form')); ?>

<div class="input-grid">
	<?php
	echo $this->Form->control('fullname', ['label' => __d('contacts', 'fullname'), 'placeholder' => __d('contacts', 'fullname')]);
	// echo $this->Form->control('company', ['label' => __d('contacts', 'company'), 'placeholder' => __d('contacts', 'fullname')]);
	// Se si cambia il name dell'input bisogna sostiturlo anche su ContactsController 
	echo $this->Form->control('email', ['label' => __d('contacts', 'email'), 'placeholder' => __d('contacts', 'fullname')]); 
	echo $this->Form->control('phone', ['label' => __d('contacts', 'phone'), 'placeholder' => __d('contacts', 'fullname')]);
	?>
</div>

<?php
// echo $this->Form->control('subject', ['label' => __d('contacts', 'subject'), 'placeholder' => __d('contacts', 'subject')]);
echo $this->Form->control('message', ['label' => __d('contacts', 'message'), 'placeholder' => __d('contacts', 'message')]);
?>

<div class="form-footer">
	<div class="form-privacy">
		<?php
		$privacyLink = $this->Frontend->seolink(__d('contacts', 'privacy policy'), '/policies/view/5', ['target' => '_blank']);
		$label =  "<span>" . __d('contacts', 'privacy disclaimer {0}', $privacyLink) . "</span>";

		echo $this->Form->control('privacy', ['label' => $label, 'type' => 'checkbox', 'escape' => false])
		?>
	</div>
	<div class="form-submit">
		<?= $this->element("cta", [
			'label' => __d('contacts', 'send'),
			'icon' => 'icons/arrow-right.svg',
		]) ?>
	</div>
</div>

<?php

echo $this->Form->end();
?>
