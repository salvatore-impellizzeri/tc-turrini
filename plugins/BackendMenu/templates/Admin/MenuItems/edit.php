<?php
$this->extend('/Admin/Common/setupEdit');
$this->set('currentMenuId', 'BackendMenu');

$this->set('controlsSettings', [
    'pathway' => [
        [
            'title' => __dx($po, 'admin', 'plugin name'),
            'url' => ['controller' => 'Menus', 'action' => 'index']
        ],
        [
            'title' => $menu->title ?? $item->menu->title,
            'url' => ['action' => 'list', $menu->id ?? $item->backend_menu_id]
        ],
        [
            'title' => __d('admin', $this->request->getParam('action')),
        ]
    ]
]);
?>

<?= $this->Form->create($item) ?>
    <fieldset class="input-group">
        <?php
        echo $this->Form->control('title', ['label' => __dx($po, 'admin', 'title'), 'extraClass' => 'span-8']);  
        echo $this->Form->control('superadmin_only', ['label' => __dx($po, 'admin', 'superadmin_only'), 'extraClass' => 'span-3']);      
        echo $this->Form->control('published', ['label' => __dx($po, 'admin', 'published'), 'extraClass' => 'span-1']);
        echo $this->Form->control('parent_id', ['empty' => __dx($po, 'admin', 'nothing'), 'label' => __dx($po, 'admin', 'parent'), 'extraClass' => 'span-4']);
        echo $this->Form->control('url', ['label' => __dx($po, 'admin', 'url'), 'extraClass' => 'span-4']);
        echo $this->Form->control('icon', ['label' => __dx($po, 'admin', 'icon'), 'extraClass' => 'span-4']);        
        echo $this->Form->hidden('backend_menu_id', ['value' => $this->request->getQuery('backend_menu_id')]);
        ?>
    </fieldset>
    <?php echo $this->element('admin/save');?>
<?= $this->Form->end() ?>
