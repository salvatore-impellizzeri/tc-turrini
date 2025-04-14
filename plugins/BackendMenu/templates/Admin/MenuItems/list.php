<?php
$this->extend('/Admin/Common/setupTree');
$this->set('currentMenuId', 'BackendMenu');
$this->set('controlsSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'plugin name'),
            'url' => ['controller' => 'Menus', 'action' => 'index']
        ],
        [
            'title' => $menu->title,
        ]
    ],
    'actions' => [
        [
            'title' => __d('admin', 'add action'),
            'icon' => 'add_circle_outline',
            'url' => ['controller' => 'MenuItems', 'action' => 'add', '?' => ['backend_menu_id' => $menu->id]]
        ]
    ],
]);

?>

<?= $this->Backend->treeList($items, ['maxDepth' => $maxDepth]) ?>
