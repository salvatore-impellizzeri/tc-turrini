<?php
$this->assign('bodyClass', 'body--static-header');
$this->assign('mainClass', 'main--no-padding main--full');

use Cake\Core\Configure;
?>

<div class="login-form" >
    <div class="login-form__section">
        <h2 class="login-form__title"><?= __d('clients', 'login title') ?></h2>


        <?= $this->Form->create() ?>
            <?php
            echo $this->Form->control('email', ['placeholder' => __d('clients', 'email'), 'label' => false]);
            echo $this->Form->control('password', ['placeholder' => __d('clients', 'password'), 'label' => false]);
			// NON ELIMINARE!
			echo $this->Form->control('remember_me', ['type' => 'hidden', 'value' => true]);
            echo $this->Form->button(__d('clients', 'login'), ['class' => 'button button--alt button--full']);
            ?>
        <?= $this->Form->end() ?>

        <a href="<?= $this->Frontend->url('/clients/recover-password') ?>" class="login-form__recover"><?= __d('clients', 'lost password') ?></a>


    </div>
    <div class="login-form__section">
        <h2 class="login-form__title"><?= __d('clients', 'signup title') ?></h2>

        <a href="<?= $this->Frontend->url('/clients/add') ?>" class="button button--full button--light"><?= __d('clients', 'signup') ?></a>
    </div>
</div>
