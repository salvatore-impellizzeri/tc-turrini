<?php
$this->extend('/Admin/Common/indexTree');
$this->set('statusBarSettings', [
    'pathway' => __dx($po, 'admin', 'categories')
]);
?>


<?= $this->Backend->treeList($items, [
    'maxDepth' => $maxDepth,
    // 'links' => [
    //     [
    //         'title' => __dx($po, 'admin', 'manage items'),
    //         'url' => ['controller' => 'Products', 'action' => 'index', '?' => ['product_category_id' => '$id']]
    //     ]
    // ]
]) ?>
