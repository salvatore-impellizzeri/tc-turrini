<?php
$links = [
    'Translate' => [
        'label' => __d('admin', 'settings translations'),
        'url' => ['plugin' => false, 'controller' => 'Translate', 'action' => 'index'],
        'superadmin_only' => false,
    ],
    'Menu' => [
        'label' => __d('admin', 'settings menu'),
        'url' => ['plugin' => 'Menu', 'controller' => 'Menus', 'action' => 'index'],
        'superadmin_only' => true
    ],
    'ContentBlocks' => [
        'label' => __d('admin', 'settings content blocks'),
        'url' => ['plugin' => 'ContentBlocks', 'controller' => 'ContentBlockTypes', 'action' => 'index'],
        'superadmin_only' => true
    ],
    'Users' => [
        'label' => __d('admin', 'settings users'),
        'url' => ['plugin' => false, 'controller' => 'Users', 'action' => 'index'],
        'superadmin_only' => true,
    ],
    'Groups' => [
        'label' => __d('admin', 'settings groups'),
        'url' => ['plugin' => false, 'controller' => 'Groups', 'action' => 'index'],
        'superadmin_only' => true,
    ],
    'BackendMenu' => [
        'label' => __d('admin', 'settings backend menu'),
        'url' => ['plugin' => 'BackendMenu', 'controller' => 'Menus', 'action' => 'index'],
        'superadmin_only' => true
    ],
]
?>

<div class="settings__menu">
    <?php foreach ($links as $id => $link):
        $extraClass = !empty($currentMenuId) && $currentMenuId == $id ? ' settings__menu__link--active btn btn--primary' : 'btn btn--light-outline';

        // controllo superuser
        if ($loggedUser['group_id'] != 1 && $link['superadmin_only']) continue;
        ?>
        <a href="<?= $this->Url->build($link['url']) ?>" class="settings__menu__link <?= $extraClass ?>">
            <?= $link['label'] ?>
        </a>
    <?php endforeach; ?>
</div>
