<?php
use Cake\Core\Configure;
use Cake\Routing\Router;
?>

<div class="activity-bar">
    <div class="activity-bar__label">
        <?= Configure::read('Setup.sitename') ?>

        <a href="/" class="activity-bar__action activity-bar__website" target="_blank">
            <span><?= __d('admin', 'to website') ?></span>
            <?= $this->Backend->materialIcon('home') ?>
        </a>
    </div>

    <div class="activity-bar__user">

        <span class="activity-bar__user__toggler">
            <?= $this->Backend->materialIcon('person') ?>
            <?= $loggedUser['username'] ?>
            <span class="drop-icon"><?= $this->Backend->materialIcon('keyboard_arrow_down') ?></span>
        </span>

        <div class="activity-bar__user__dropdown">
            <a href="<?= Router::url(['plugin' => false, 'controller' => 'Users', 'action' => 'setNewPassword']) ?>">
                <?= $this->Backend->materialIcon('password') ?>
                <span><?= __d('admin', 'set new password') ?></span>

            </a>
            <a href="<?= Router::url(['plugin' => false, 'controller' => 'Users', 'action' => 'logout']) ?>" >
                <?= $this->Backend->materialIcon('logout') ?>
                <span><?= __d('admin', 'logout') ?></span>

            </a>
        </div>
    </div>



</div>
