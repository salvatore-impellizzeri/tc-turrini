<?php
$this->extend('/Admin/Common/indexTree');

$this->set('statusBarSettings', [
    'pathway' => $menu->title
]);

$this->set('controlsSettings', [
    'actions' => [
        [
            'title' => __d('admin', 'add action'),
            'icon' => 'add_circle_outline',
            'url' => ['controller' => 'MenuItems', 'action' => 'add', '?' => ['menu_id' => $menu->id]]
        ]
    ],
]);
?>

<?= $this->Backend->treeList($items, ['maxDepth' => $maxDepth]) ?>
