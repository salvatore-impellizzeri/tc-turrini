<?php
$this->assign('bodyClass', 'body--static-header');
$this->assign('mainClass', 'main--no-padding main--full');

use Cake\Core\Configure;
?>

<div class="login-form" >
    <div class="login-form__section">
        <h2 class="login-form__title"><?= __d('clients', 'lost password')?></h2>
		<?php if ($this->request->is(['post', 'put'])) { ?>
			<div>
				<?= __d('clients', 'lost password message after')?>
				<br><br>
			</div>        
		<?php } else { ?>
		    <?= $this->Form->create(null, ['novalidate' => false]); ?>
            <?php echo $this->Form->control('email', ['required' => 'required', 'placeholder' => __d('clients', 'email'), 'label' => false]); ?>
			<div>
				<?= __d('clients', 'lost password message before')?>
				<br><br>
			</div>
            <?= $this->Form->button(__d('clients', 'proceed'), ['class' => 'button button--alt button--full'])?>

			<?= $this->Form->end() ?>
		<?php } ?>


    </div>

</div>

<?= $this->element('google-login') ?>
