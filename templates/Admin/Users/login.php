<?php
$this->layout = 'login';
?>
    

<div class="login-form">


    <?= $this->Form->create() ?>
    <?= $this->Form->control('username', ['required' => true, 'label' => __d('admin', 'username')]) ?>
    <?= $this->Form->control('password', ['required' => true, 'label' => __d('admin', 'password')]) ?>
	<?= $this->Form->control('remember_me', ['type' => 'hidden', 'value' => true]) ?>
    <?= $this->Form->submit(__d('admin', 'login')); ?>
    <?= $this->Form->end() ?>


</div>

<div class="powered-by">

    <div class="powered-by__logo">
        <a href="https://www.webmotion.it">
            <?= $this->Html->image('/admin-assets/img/logo-wm.svg', ['alt' => 'Webmotion']) ?>
        </a>
    </div>
</div>


<?php $this->Html->scriptStart(['block' => 'scriptBottom']) ?>
let message = document.querySelector('.message');

if (message !== null) {
    window.setTimeout(() => {
        message.classList.add('hidden');
    }, 4000);
}
<?php $this->Html->scriptEnd() ?>
