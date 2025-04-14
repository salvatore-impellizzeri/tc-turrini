<?php
$this->extend('customer_area');
$this->assign('currentClientMenuId', 'dati');
?>


<h1 class="account__title"><?= __d('clients', 'personal data') ?></h1>


<div class="account__container">
    <div class="account__item">
        <div class="account__item__content">
            <div class="account__item__content__grid">
                <dl>
                    <dt><?= __d('clients', 'fullname') ?></dt>
                    <dd><?= $loggedClient['fullname'] ?></dd>
                </dl>

                <dl>
                    <dt><?= __d('clients', 'email') ?></dt>
                    <dd><?= $loggedClient['email'] ?></dd>

                    <dt><?= __d('clients', 'created') ?></dt>
                    <dd><?= $loggedClient['created'] ?></dd>

                </dl>
            </div>
        </div>

        <div class="account__item__actions">
            <a href="<?= $this->Frontend->url('/clients/edit') ?>" class="button button--full button--light"><?= __d('clients', 'edit data') ?></a>
            <a href="<?= $this->Frontend->url('/clients/edit-email') ?>" class="button button--full button--light"><?= __d('clients', 'edit email') ?></a>
            <a href="<?= $this->Frontend->url('/clients/edit-password') ?>" class="button button--full button--light"><?= __d('clients', 'edit password') ?></a>
        </div>
    </div>
</div>

<div class="account__actions">
    <a href="<?= $this->Frontend->url('/clients/delete') ?>" class="account__editlink" onclick="return confirm('<?php echo __d('clients', 'confirm delete'); ?>');"><?= __d('clients', 'delete account') ?></a>	
</div>
