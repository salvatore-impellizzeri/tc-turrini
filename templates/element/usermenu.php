<div class="usermenu">
    <a href="<?php echo $this->Frontend->url('/clients/resume'); ?>" class="usermenu__toggler<?php if(!empty($loggedClient)) echo ' usermenu__toggle--active' ?>" data-usermenu-toggler>
        <?= __d('global', 'user')?>
    </a>
    <div class="usermenu__dropdown" data-usermenu>
        <?php if (!$loggedClient): ?>
            <ul class="usermenu__menu">
                <li>
                    <a href="<?= $this->Frontend->url('/clients/login') ?>"><?= __d('clients', 'login') ?></a>
                </li>
            </ul>

        <?php else: ?>
            <ul class="usermenu__menu">
                <li>
                    <a class="usermenu__link" href="<?= $this->Frontend->url('/clients/resume') ?>">
                        <?= __d('clients', 'account') ?>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Frontend->url('/clients/logout') ?>">
                        <?= __d('clients', 'logout') ?>
                    </a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</div>
