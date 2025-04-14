<?php
$this->assign('bodyClass', 'body--static-header');
$this->assign('mainClass', 'main--no-padding main--full');

use Cake\Core\Configure;
?>

<div class="login-form" >
    <div class="login-form__section">
        <h2 class="login-form__title"><?= __d('clients', 'login title') ?></h2>

        <a href="<?= $this->Frontend->url('/clients/login') ?>" class="button button--full button--light"><?= __d('clients', 'login') ?></a>
    </div>

    <div class="login-form__section">
        <h2 class="login-form__title"><?= __d('clients', 'signup title')?></h2>


        <?= $this->Form->create($client, ['autocomplete' => 'off']); ?>

            <?php
            echo $this->Form->control('fullname', ['label' => __d('clients', 'fullname')]);
            echo $this->Form->control('email', ['label' => __d('clients', 'email')]);
            echo $this->Form->control('password', [
                'label' => __d('clients', 'password'),
                'templates' => [
                    'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<div class="input-hint">{{inputHint}}</div></div>',
                    'inputContainerError' => '<div class="input {{type}}{{required}} error">{{content}}{{error}}</div>',
                ],
                'templateVars' => [
                    'inputHint' => __d('clients', 'password hint')
                ]
            ]);
            echo $this->Form->control('repeat_password', ['label' => __d('clients', 'repeat password'), 'type' => 'password']);
            ?>

            <div class="form-privacy">
                <?php

                $privacyLink = $this->Frontend->seolink(__d('clients', 'privacy policy'), '/policies/view/6?popup=true', ['target' => '_blank', 'data-fancybox-iframe', 'data-type' => 'iframe']);
                echo $this->Form->control('privacy', [
                    'type' => 'checkbox',
                    'label' => $this->Html->tag('span', __d('clients', 'privacy disclaimer {0}', $privacyLink)),
                    'escape' => false
                ]);
                ?>
            </div>

            <?= $this->Form->button(__d('clients', 'proceed'), ['class' => 'button button--alt button--full'])?>

        <?= $this->Form->end() ?>





    </div>

</div>
