<div class="account__item">

    <div class="account__item__content">
        <?php if ($is_default): ?>
            <span class="account__selector account__selector--selected">
                <?= __d('clients', 'default address') ?>
            </span>
        <?php else: ?>
            <a href="<?= $this->Frontend->url('/clients/set-default-address/'.$scope.'/'.$id) ?>" class="account__selector">
                <?= __d('clients', 'set default address') ?>
            </a>
        <?php endif; ?>

        <dl>
            <dd>
                <?= $address ?>
            </dd>
        </dl>
    </div>

    <div class="account__item__actions">
        <a href="<?= $this->Frontend->url('/clients/edit-address/'.$scope.'/'.$id) ?>" class="button button--full button--light"><?= __d('clients', 'edit address') ?></a>

        <?php if (!$is_default): ?>
            <a href="<?= $this->Frontend->url('/clients/delete-address/'.$scope.'/'.$id) ?>" class="button button--full button--light"><?= __d('clients', 'delete address') ?></a>
        <?php endif; ?>


    </div>
</div>
